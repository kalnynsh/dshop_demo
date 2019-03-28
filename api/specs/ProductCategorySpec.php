<?php

namespace api\specs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *    schema="ProductCategory",
 *    type="object",
 *    title="Products category",
 *    description="Products category",
 *    @OA\Property(
 *        title="ID",
 *        description="Category ID",
 *        property="id",
 *        type="integer",
 *        readOnly="true"
 *    ),
 *    @OA\Property(
 *        title="Name",
 *        description="Category name",
 *        property="name",
 *        type="string"
 *    ),
 *    @OA\Property(
 *        title="Links",
 *        description="Category links",
 *        property="_links",
 *        type="object",
 *        @OA\Property(
 *          ref="#/components/properties/_links"
 *        )
 *    ),
 *    required={"id"}
 * )
 */
