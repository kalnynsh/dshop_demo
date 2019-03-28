<?php

namespace api\specs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *    schema="ProductPhoto",
 *    type="object",
 *    title="Photo",
 *    description="Products photo",
 *    @OA\Property(
 *        title="Thumbnail",
 *        description="Photos thumbnail",
 *        property="thumbnail",
 *        type="string"
 *    ),
 *    @OA\Property(
 *        title="Origin",
 *        description="Origin photo",
 *        property="origin",
 *        type="string"
 *    ),
 * )
 */
