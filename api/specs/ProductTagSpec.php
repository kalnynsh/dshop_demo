<?php

namespace api\specs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *    schema="ProductTag",
 *    type="object",
 *    title="Products tag",
 *    description="Products tag",
 *    @OA\Property(
 *        title="ID",
 *        description="Tag ID",
 *        property="id",
 *        type="integer",
 *        readOnly="true"
 *    ),
 *    @OA\Property(
 *        title="Name",
 *        description="Tag name",
 *        property="name",
 *        type="string"
 *    ),
 *    @OA\Property(
 *        title="Links",
 *        description="Tag links",
 *        property="_links",
 *        type="object",
 *        @OA\Property(
 *          ref="#/components/properties/_links"
 *        )
 *    )
 * )
 */
