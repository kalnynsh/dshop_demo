<?php

namespace api\specs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Cart",
 *     type="object",
 *     title="Cart",
 *     description="Customer cart",
 *     @OA\Property(property="weight", type="integer"),
 *     @OA\Property(property="amount", type="integer"),
 *     @OA\Property(
 *         title="Items",
 *         description="List of cart items",
 *         property="items",
 *         type="array",
 *         @OA\Items(
 *          type="object",
 *          @OA\Property(property="id", type="string"),
 *          @OA\Property(property="quantity", type="integer"),
 *          @OA\Property(property="price", type="integer"),
 *          @OA\Property(property="cost", type="integer"),
 *          @OA\Property(
 *              property="product",
 *              type="object",
 *              @OA\Property(property="id", type="integer"),
 *              @OA\Property(property="code", type="string"),
 *              @OA\Property(property="name", type="string"),
 *              @OA\Property(property="thumbnail", type="string"),
 *              @OA\Property(
 *                  property="_links",
 *                  type="object",
 *                  ref="#/components/properties/_links"
 *             )
 *         ),
 *         @OA\Property(
 *              description="Modification of product",
 *              property="modification",
 *              type="object",
 *              @OA\Property(property="id", type="integer"),
 *              @OA\Property(property="code", type="string"),
 *              @OA\Property(property="name", type="string"),
 *              @OA\Property(
 *                  property="_links",
 *                  type="object",
 *                  @OA\Property(
 *                      property="quantity",
 *                      type="object",
 *                      @OA\Property(property="href", type="string")
 *                  )
 *             )
 *         )
 *     )),
 *     @OA\Property(
 *          property="cost",
 *          type="object",
 *          @OA\Property(property="origin", type="integer"),
 *          @OA\Property(
 *              property="discounts",
 *              type="array",
 *              @OA\Items(
 *                  type="object",
 *                  @OA\Property(property="name", type="string"),
 *                  @OA\Property(property="value", type="integer")
 *              )
 *          ),
 *         @OA\Property(property="total", type="integer"),
 *     ),
 *     @OA\Property(
 *          property="_links",
 *          type="object",
 *          @OA\Property(
 *              property="self",
 *              type="object",
 *              @OA\Property(property="href", type="string")
 *          )
 *     )
 * )
 */
