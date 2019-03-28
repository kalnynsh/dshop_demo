<?php

namespace api\specs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *    schema="ProductPrice",
 *    type="object",
 *    title="Price",
 *    description="Product price",
 *    @OA\Property(
 *        title="New",
 *        description="Price is new",
 *        property="new",
 *        type="integer"
 *    ),
 *    @OA\Property(
 *        title="Old",
 *        description="Price is old",
 *        property="old",
 *        type="integer"
 *    )
 * )
 */
