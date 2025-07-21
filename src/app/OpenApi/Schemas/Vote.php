<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Vote",
 *     title="Vote",
 *     type="object",
 *     required={"id", "idea_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="idea_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="voter_name",
 *         type="string",
 *         nullable=true,
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-07-22T08:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-07-22T08:30:00Z"
 *     )
 * )
 */
class Vote
{
    // This class is used only for OpenAPI annotations; no methods or properties are required.
}
