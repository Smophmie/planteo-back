<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Plant;

/**
 * @OA\Tag(
 *     name="Favorites",
 *     description="API Endpoints for managing user favorites"
 * )
 * @OA\Schema(
 *     schema="Favorite",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="plant_id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1)
 * )
 */
class FavoriteController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/favorites",
     *     summary="Add a plant to favorites",
     *     tags={"Favorites"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="plant_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plant added to favorites",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plante ajoutée à vos favoris.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Plant already in favorites",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cette plante fait déjà partie de vos favoris")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'plant_id' => 'required|exists:plants,id',
        ]);

        $user = Auth::user();
        $plant = Plant::find($request->plant_id);

        if ($user->favorites()->where('plant_id', $plant->id)->exists()) {
            return response()->json(['message' => 'Cette plante fait déjà partie de vos favoris'], 409);
        }

        $user->favorites()->attach($plant);

        return response()->json(['message' => 'Plante ajoutée à vos favoris.'], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/favorite/{id}",
     *     summary="Check if a plant is in favorites",
     *     tags={"Favorites"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the plant"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favorite status",
     *         @OA\JsonContent(
     *             @OA\Property(property="isFavorite", type="boolean")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function isFavorite($id)
    {
        $user = Auth::user();

        $isFavorite = $user->favorites()->where('plant_id', $id)->exists();

        return response()->json(['isFavorite' => $isFavorite]);
    }

    /**
     * @OA\Delete(
     *     path="/api/favorites",
     *     summary="Remove a plant from favorites",
     *     tags={"Favorites"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="plant_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plant removed from favorites",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plante supprimée de vos favoris.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plant not found in favorites",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="La plante n'est pas dans vos favoris")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'plant_id' => 'required|exists:plants,id',
        ]);

        $user = Auth::user();
        $plant = Plant::find($request->plant_id);

        if (!$user->favorites()->where('plant_id', $plant->id)->exists()) {
            return response()->json(['message' => 'La plante n\'est pas dans vos favoris'], 404);
        }

        $user->favorites()->detach($plant);

        return response()->json(['message' => 'Plante supprimée de vos favoris.'], 200);
    }
}
