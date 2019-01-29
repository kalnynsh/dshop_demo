<?php

namespace shop\entities\Content\queries;

use yii\db\ActiveQuery;
use paulzi\nestedsets\NestedSetsQueryTrait;

class PageQuery extends ActiveQuery
{
    use NestedSetsQueryTrait;
}
