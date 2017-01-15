<?php
namespace cii\helpers;


use DateTime;
use DateInterval;
use DateTimeZone;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Plotter {
	public static function plotByDatetime($query, $attribute, $range, $steps, $aggregator = 'count', $aggregatorOptions = []) {
        $data = [];

        for($i = 0; $i < $steps; $i++) {
            $datetime = new DateTime('now', new DateTimeZone('UTC'));
            $datetime->sub(new DateInterval('P' . $i . $range));

            $endDate = $datetime->format('Y-m-d');

            $datetime->sub(new DateInterval('P1' . $range));
            $startDate = $datetime->format('Y-m-d');

            $query
                ->andWhere($attribute . ' >= :startDate', ['startDate' => $startDate . ' 00:00:00'])
                ->andWhere($attribute . ' <= :endDate', ['endDate' => $endDate . ' 23:59:59'])
            ;

            $data[$startDate] = call_user_func_array([$query, $aggregator], $aggregatorOptions);
        }

        $data = array_reverse($data);
        return $data;
    }

    public static function plotByValues($query, $attribute, $values, $aggregator = 'count') {
        $data = [];

        foreach($values as $key => $value) {
            if($value === null) {
                $data[$key] = $query
                    ->where($attribute . ' IS NULL')
                    ->$aggregator();
            }else {
                $data[$key] = $query
                    ->andWhere([$attribute => $value])
                    ->$aggregator();
            }
        }

        return $data;
    }


    public static function plotByTableRelation($main, $related, $attribute, $displayAttribute = 'name') {
        $query = new Query();
        $values = $query
            ->select(['t.' . $displayAttribute . ' as label', 'COUNT(gm.id) as count'])
            ->from($main . ' as t')
            ->leftJoin($related . ' as gm', 'gm.' . $attribute . ' = t.id')
            ->groupBy('gm.' . $attribute)
            ->orderBy('count')
            ->limit(10)
            ->all()
        ;
        
        $ret = ArrayHelper::map($values, 'label', 'count');

        return $ret;
    }


    public static function plotByDateSegments($query, $attribute, $aggregator = 'count') {
        $ret = [];
        $dates = self::plotByDatetime($query, $attribute, 'D', 7, $aggregator);
        $i = 0;
        foreach($dates as $date) {
            $i++;
            $ret[$i . ' days'] = $date;
        }

        $dates = self::plotByDatetime($query, $attribute, 'W', 4, $aggregator);
        $i = 0;
        foreach($dates as $date) {
            //skip first entry
            if($i != 0) {
                $ret[$i . ' weeks'] = $date;
            }

            $i++;
        }

        $dates = self::plotByDatetime($query, $attribute, 'M', 6, $aggregator);
        $i = 0;
        foreach($dates as $date) {
            //skip first entry
            if($i != 0) {
                $ret[$i . ' months'] = $date;
            }

            $i++;
        }


        $datetime = new DateTime('now', new DateTimeZone('UTC'));
        $datetime->sub(new DateInterval('P6M'));
        
        $ret['Rest'] = $query
                ->andWhere($attribute . ' <= :endDate', ['endDate' => $datetime->format('Y-m-d H:i:s') . ' 23:59:59'])
                ->orWhere($attribute . ' IS NULL')
                ->$aggregator();

        return $ret;
    }

    public static function transformToFlotSegments($data) {
        $ret = [];
        foreach($data as $key => $value) {
            $ret[] = [
                'label' => $key,
                'data' => $value
            ];
        }

        return $ret;
    }

    public static function transformToFlotDatetime($data) {
    	$ret = [];

    	foreach($data as $key => $value) {
            $idx = UTC::strtotime($key) * 1000;
            if(is_null($value)) {
                $ret[$idx] = 0;
            }else {
        		$ret[$idx] = $value;
            }
    	}

    	return $ret;
    }
}
