<?php

namespace api\specs;

use OpenApi\Annotations as OA;

/**
 *  @OA\Property(
 *      property="self",
 *      name="self_link",
 *      description="Reference to yourself",
 *      type="object",
 *      @AO\Property(
 *          property="href",
 *          type="string"
 *      )
 * )
 */

 /**
 *  @OA\Property(
 *      property="_links",
 *      name="other_links",
 *      description="Reference to other",
 *      type="object",
 *      @OA\Property(
 *          ref="#/components/properties/self"
 *      )
 * )
 */
