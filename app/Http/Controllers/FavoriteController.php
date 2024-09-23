<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Plant;

class FavoriteController extends Controller
{
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
