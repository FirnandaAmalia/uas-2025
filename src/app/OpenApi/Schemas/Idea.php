<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Idea",
 *     title="Idea",
 *     type="object",
 *     required={"id", "title"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Contoh Judul Ide"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         example="Deskripsi singkat tentang ide ini."
 *     ),
 *     @OA\Property(
 *         property="vote_count",
 *         type="integer",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-07-21T12:34:56Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-07-21T12:34:56Z"
 *     )
 * )
 */
class Idea
{
    
}
