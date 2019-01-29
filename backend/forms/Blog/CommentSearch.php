<?php

namespace backend\forms\Blog;

use yii\data\ActiveDataProvider;
use yii\base\Model;
use shop\services\manage\Blog\CommentManageService;
use shop\entities\Blog\Post\Post;
use shop\entities\Blog\Post\Comment;

/**
 * CommentSearch class
 * search posts by given params
 *
 * @property int    $id
 * @property int    $post_id
 * @property string $text
 * @property bool   $active
 * @property CommentManageService $comments
 * @property $yiiApp
 */
class CommentSearch extends Model
{
    public $id;
    public $post_id;
    public $text;
    public $active;

    private $comments;
    private $yiiApp;

    public function __construct(CommentManageService $comments, $config = [])
    {
        parent::__construct($config);
        $this->comments = $comments;
        $this->yiiApp = \Yii::$app;
    }

    public function rules(): array
    {
        return [
            [['id', 'post_id'], 'integer'],
            ['text', 'safe'],
            ['active', 'boolean'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = $this->comments->getCommentQuery();

        $dataProvider = $this->getProvider([
            'query' => $query,
            'key' => function (Comment $comment) {
                return [
                    'post_id' => $comment->post_id,
                    'id' => $comment->id,
                ];
            },
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
            ->andFilterWhere(['post_id' => $this->post_id])
            ->andFilterWhere(['active' => $this->active])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }

    public function activeList(): array
    {
        return [
            0 => $this->yiiApp->formatter->asBoolean(false),
            1 => $this->yiiApp->formatter->asBoolean(true),
        ];
    }

    private function getProvider(array $data): ActiveDataProvider
    {
        return new ActiveDataProvider($data);
    }
}
