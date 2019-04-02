<?php

namespace api\specs;

use OpenApi\Annotations as OA;

/**
 *  @OA\Schema(
 *      schema="ErrorModel",
 *      type="object",
 *      required={"code", "message"},
 *      @OA\Property(
 *          property="code",
 *          type="integer",
 *          format="int32",
 *          title="Code",
 *          description="The error code"
 *      ),
  *      @OA\Property(
 *          property="message",
 *          type="string",
 *          title="Message",
 *          description="The error description"
 *      )
 *  )
  */
/**
 * @AO\Schema(
 *      schema="ResultResponse",
 *      title="Result",
 *      description="Result of request",
 *      type="object",
 *      @AO\Property(
 *          property="result",
 *          type="integer"
 *      ),
 *      @AO\Property(
 *          property="message",
 *          type="string"
 *     )
 * )
 */
