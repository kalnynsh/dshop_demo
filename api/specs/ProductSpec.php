<?php

namespace api\specs;

use OpenApi\Annotations as OA;

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
*   @OA\Property(
*     property="price",
*     ref="#/components/schemas/ProductPrice"
*   ),
*   @OA\Property(
*     property="thumbnail",
*     type="string"
*   ),
*    @OA\Property(
*        property="_links",
*        type="object",
*        @OA\Property(
*          ref="#/components/properties/_links"
*        )
*    ),
*    required={"id"}
* )
*/

/**
* @OA\Schema(
*    schema="ProductView",
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
*        property="categories",
*        type="object",
*        @OA\Property(
*           property="main",
*           ref="#/components/schemas/ProductCategory"
*       ),
*        @OA\Property(
*           property="other",
*           type="object",
*           additionalProperties={
*               ref="#/components/schemas/ProductCategory"
*           }
*       )
*    ),
*   @OA\Property(
*     property="brand",
*     ref="#/components/schemas/ProductBrand"
*   ),
*   @OA\Property(
*     property="tags",
*     type="object",
*     additionalProperties={
*       ref="#/components/schemas/ProductTag"
*     }
*   ),
*   @OA\Property(
*     property="photos",
*     type="object",
*     additionalProperties={
*       ref="#/components/schemas/ProductPhoto"
*     }
*   ),
*    @OA\Property(
*        property="_links",
*        type="object",
*        @OA\Property(
*          ref="#/components/properties/_links"
*        )
*    ),
*    required={"id"}
* )
*/

/**
*  @OA\Schema(
*       title="Modification",
*       description="Modifications data",
*       schema="ProductModification",
*       type="object",
*       @OA\Property(
*           property="modification",
*           type="integer",
*           required=false
*       ),
*       @OA\Property(
*           property="quantity",
*           type="integer",
*           required=true,
*       ),
*       example={"modification": 12, "quantity": 3}
*  )
*/

/**
*  @OA\Schema(
*       title="Quantity",
*       description="Quantity",
*       schema="Quantity",
*       type="object",
*       @OA\Property(
*           property="quantity",
*           type="integer",
*           required=true,
*       ),
*       example={"quantity": 3}
*  )
*/
