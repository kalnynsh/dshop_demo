<?php

namespace api\specs;

/**
* @OA\Schema(
*    schema="ProductItem",
*    type="object",
*    @OA\Property(
*        title="ID",
*        description="Products ID",
*        property="id",
*        type="integer",
*        readOnly="true"
*    ),
*    @OA\Property(
*        title="Code",
*        description="Products code",
*        property="code",
*        type="string"
*    ),
*    @OA\Property(
*        title="Name",
*        description="Products name",
*        property="name",
*        type="string"
*    ),
*    @OA\Property(
*        property="category",
*        ref="#/components/schemas/ProductCategory"
*    ),
*   @OA\Property(
*     property="brand",
*     ref="#/components/schemas/ProductBrand"
*   ),
*    required={"id"}
* )
*/
