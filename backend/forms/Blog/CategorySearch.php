<?php

namespace backend\forms\Blog;

use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use shop\entities\Blog\Category;

/**
 * CategorySearch class
 * search tags by given params
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 */
class CategorySearch extends Model
{
    public $id;
    public $name;
    public $slug;
    public $title;

    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'slug', 'title'], 'safe'],
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
                'defaultOrder' => ['sort' => SORT_ASC],
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
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }

    private function query(): ActiveQuery
    {
        return Category::find();
    }
}
