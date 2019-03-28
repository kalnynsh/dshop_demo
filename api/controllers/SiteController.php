<?php

namespace api\controllers;

use yii\rest\Controller;

/**
 *  @OA\Info(
 *   title="Shop API",
 *   description="HTTP JSON API",
 *   version="1.0.0"
 *  )
 */
/**
 *  @OA\Server(
 *      description="E-shop host",
 *      url=API_HOST
 *  )
 */
/**
 *  @OA\SecurityScheme(
 *   type="oauth2",
 *   name="Authorization",
 *   securityScheme="password_auth",
 *      @OA\Flow(
 *          flow="password",
 *          authorizationUrl=API_TOKEN_URL,
 *          scopes={
 *              "read:products": "read products infomation",
 *              "read:cart": "read infomation about products in cart",
 *              "write:cart": "add or remove products to cart",
 *              "read:wishlist": "read infomation about products in wishlist",
 *              "write:wishlist": "add or remove products to wishlist"
 *          }
 *      )
 *  )
 *
 *  @OA\SecurityScheme(
 *      type="apiKey",
 *      in="header",
 *      securityScheme="Bearer",
 *      name="AuthorizationToken"
 *  )
 */
 /**
 *  @OA\Get(
 *       path="/api",
 *       summary="Home page",
 *       @OA\RequestBody(
 *           @OA\MediaType(
 *              mediaType="application/json"
 *          )
 *       ),
 *       @OA\RequestBody(
 *           @OA\MediaType(
 *              mediaType="application/xml"
 *          )
 *       ),
 *      @OA\Response(
 *           response=200,
 *           description="HTTP JSON API"
 *       )
 *   )
 */
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
class SiteController extends Controller
{
    /**
     * @OA\Get(
     *      path="/",
     *      tags={"Info"},
     *      summary="Show api version number",
     *      @OA\Response(
     *          response="200",
     *          description="API version",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="version",
     *                  type="string"
    *               )
     *          )
     *      )
     * )
     */
    public function actionIndex(): array
    {
        return [
            'version' => '1.0.0',
        ];
    }
}
