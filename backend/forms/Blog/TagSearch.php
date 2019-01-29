<?php

namespace backend\forms\Blog;

use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use shop\entities\Blog\Tag;

/**
 * TagSearch class
 * search tags by given params
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class TagSearch extends Model
{
    public $id;
    public $name;
    public $slug;

    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'slug'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = $this->query();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');

            return $dataProvider;
        }

        $query
            ->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }

    private function query(): ActiveQuery
    {
        return Tag::find();
    }
}
