<?php
namespace cii\helpers;


class Plotter {
	public static function plotDatetime($query, $attribute, $range, $steps) {
        $data = [];

        for($i = 0; $i < $steps; $i++) {
            $datetime = new \DateTime('now', new \DateTimeZone('UTC'));
            $datetime->sub(new \DateInterval('P' . $i . $range));

            $endDate = $datetime->format('Y-m-d');

            $datetime->sub(new \DateInterval('P1' . $range));
            $startDate = $datetime->format('Y-m-d');

            $data[$startDate] = $query
                ->andWhere($attribute . ' >= :startDate', ['startDate' => $startDate . ' 00:00:00'])
                ->andWhere($attribute . ' <= :endDate', ['endDate' => $endDate . ' 23:59:59'])
                ->count();
        }

        $data = array_reverse($data);
        return $data;
    }

    public static function plotByValues($query, $attribute, $values) {
        $data = [];

        foreach($values as $key => $value) {
            if($value === null) {
                $data[$key] = $query
                    ->where($attribute . ' IS NULL')
                    ->count();
            }else {
                $data[$key] = $query
                    ->andWhere([$attribute => $value])
                    ->count();
            }
        }

        return $data;
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
    		$ret[UTC::strtotime($key) * 1000] = $value;
    	}

    	return $ret;
    }
}
