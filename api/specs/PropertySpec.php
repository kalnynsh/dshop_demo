<?php

namespace api\specs;

use OpenApi\Annotations as OA;

 /**
 *  @OA\Property(
 *      property="_links",
 *      name="other_links",
 *      description="Reference to other",
 *      type="object",
 *      additionalProperties={
 *          property="self",
 *          type="object",
 *          name="self_link",
 *          description="Reference to yourself",
 *          @OA\Property(
 *              property="href",
 *              type="string"
 *          )
 *      }
 * )
 */
