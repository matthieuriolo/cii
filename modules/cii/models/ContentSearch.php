<?php

namespace app\modules\cii\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\cii\models\Content;

/**
 * ContentSearch represents the model behind the search form about `app\modules\core\models\Content`.
 */
class ContentSearch extends Content
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'enabled'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Content::find();


        $query->joinWith([
            'classname as classname',
            'classname.package.extension as package'
        ]);
        $query->andFilterWhere([
            'package.enabled' => true,
        ]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'name',
                    
                    'classname' => [
                        'asc' => ['classname.path' => SORT_ASC],
                        'desc' => ['classname.path' => SORT_DESC],
                    ],
                    'created',
                    'enabled'
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'enabled' => $this->enabled,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
