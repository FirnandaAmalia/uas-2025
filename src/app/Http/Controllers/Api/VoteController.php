<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VoteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/votes",
     *     operationId="getVotes",
     *     tags={"Votes"},
     *     summary="Get list of votes",
     *     description="Returns all votes",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Vote")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $votes = Vote::with('idea:id,title')->get();

        return response()->json([
            'success' => true,
            'data'    => $votes,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/votes",
     *     operationId="storeVote",
     *     tags={"Votes"},
     *     summary="Store a new vote",
     *     description="Creates a new vote record",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"idea_id"},
     *             @OA\Property(property="idea_id", type="integer", example=1),
     *             @OA\Property(property="voter_name", type="string", nullable=true, example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vote created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Vote")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'idea_id'    => 'required|exists:ideas,id',
            'voter_name' => 'nullable|string|max:255',
        ]);

        $vote = Vote::create($validated);

        return response()->json([
            'success' => true,
            'data'    => $vote,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/votes/{vote}",
     *     operationId="getVoteById",
     *     tags={"Votes"},
     *     summary="Get vote by ID",
     *     description="Returns a single vote",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Vote")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Vote not found")
     * )
     */
    public function show(Vote $vote): JsonResponse
    {
        $vote->load('idea:id,title');

        return response()->json([
            'success' => true,
            'data'    => $vote,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/votes/{vote}",
     *     operationId="updateVote",
     *     tags={"Votes"},
     *     summary="Update existing vote",
     *     description="Updates a vote by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="idea_id", type="integer", example=1),
     *             @OA\Property(property="voter_name", type="string", nullable=true, example="Jane Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vote updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Vote")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Vote not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, Vote $vote): JsonResponse
    {
        $validated = $request->validate([
            'idea_id'    => 'sometimes|required|exists:ideas,id',
            'voter_name' => 'nullable|string|max:255',
        ]);

        $vote->update($validated);

        return response()->json([
            'success' => true,
            'data'    => $vote,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/votes/{vote}",
     *     operationId="deleteVote",
     *     tags={"Votes"},
     *     summary="Delete a vote",
     *     description="Deletes a vote by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vote deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vote deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Vote not found")
     * )
     */
    public function destroy(Vote $vote): JsonResponse
    {
        $vote->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vote deleted',
        ]);
    }
}
