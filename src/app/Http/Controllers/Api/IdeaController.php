<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class IdeaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/ideas",
     *     operationId="getIdeas",
     *     tags={"Ideas"},
     *     summary="Get list of ideas",
     *     description="Returns all ideas with vote count",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Idea")
     *             )
     *         )
     *     )
     * )
     */

    public function index(): JsonResponse
    {
        $ideas = Idea::withCount('votes')->get();

        return response()->json([
            'success' => true,
            'data' => $ideas,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/ideas",
     *     operationId="storeIdea",
     *     tags={"Ideas"},
     *     summary="Store a new idea",
     *     description="Creates a new idea",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="New Idea"),
     *             @OA\Property(property="description", type="string", example="Idea description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Idea created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Idea")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $idea = Idea::create($validated);

        return response()->json([
            'success' => true,
            'data' => $idea,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/ideas/{idea}",
     *     operationId="getIdeaById",
     *     tags={"Ideas"},
     *     summary="Get idea by ID",
     *     description="Returns a single idea",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Idea ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Idea")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Idea not found")
     * )
     */
    public function show(Idea $idea): JsonResponse
    {
        $idea->loadCount('votes');

        return response()->json([
            'success' => true,
            'data' => $idea,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/ideas/{idea}",
     *     operationId="updateIdea",
     *     tags={"Ideas"},
     *     summary="Update existing idea",
     *     description="Updates an idea by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Idea ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Idea"),
     *             @OA\Property(property="description", type="string", example="Updated description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Idea updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Idea")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Idea not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, Idea $idea): JsonResponse
    {
        $validated = $request->validate([
            'title'       => ['required','string','max:255', Rule::unique('ideas')->ignore($idea->id)],
            'description' => 'nullable|string',
        ]);

        $idea->update($validated);

        return response()->json([
            'success' => true,
            'data' => $idea,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/ideas/{idea}",
     *     operationId="deleteIdea",
     *     tags={"Ideas"},
     *     summary="Delete an idea",
     *     description="Deletes an idea by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Idea ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Idea deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Idea deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Idea not found")
     * )
     */
    public function destroy(Idea $idea): JsonResponse
    {
        $idea->delete();

        return response()->json([
            'success' => true,
            'message' => 'Idea deleted',
        ]);
    }
}
