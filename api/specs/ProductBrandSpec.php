<?php

namespace api\specs;

/**
 * @OA\Schema(
 *    schema="ProductBrand",
 *    type="object",
 *    title="Products brand",
 *    description="Products brand",
 *    @OA\Property(
 *        title="ID",
 *        description="Brand ID",
 *        property="id",
 *        type="integer",
 *        readOnly="true"
 *    ),
 *    @OA\Property(
 *        title="Name",
 *        description="Brand name",
 *        property="name",
 *        type="string"
 *    ),
 *    @OA\Property(
 *        title="Links",
 *        description="Brand links",
 *        property="_links",
 *        type="object",
 *        @OA\Property(
 *          ref="#/components/properties/_links"
 *        )
 *    ),
 *    required={"id"}
 * )
 */
