<?php

namespace backend\forms\Blog;

use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use shop\helpers\Blog\PostHelper;
use shop\helpers\Blog\CategoriesHelper;
use shop\entities\Blog\Post\Post;

/**
 * PostSearch class
 * search posts by given params
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 */
class PostSearch extends Model
{
    public $id;
    public $title;
    public $status;
    public $category_id;

    public function rules(): array
    {
        return [
            [['id', 'status', 'category_id'], 'integer'],
            ['title', 'safe'],
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
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');

            return $dataProvider;
        }

        $query
            ->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['category_id' => $this->category_id])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }

    public function categoriesList(): array
    {
        return CategoriesHelper::list('title');
    }

    public function statusList(): array
    {
        return PostHelper::statusList();
    }

    private function query(): ActiveQuery
    {
        return Post::find();
    }
}
