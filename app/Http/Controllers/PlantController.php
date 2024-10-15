<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

/**
 * @OA\Tag(
 *     name="Plants",
 *     description="API Endpoints for managing plants"
 * )
 * @OA\Schema(
 *     schema="Plant",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Haricots verts"),
 *     @OA\Property(property="description", type="string", example="Les haricots verts sont des légumes savoureux et croquants, parfaits pour des salades ou des plats cuisinés."),
 *     @OA\Property(property="image", type="string", example="https://example.com/image.jpg"),
 *     @OA\Property(property="type", type="string", example="Légume vert"),
 *     @OA\Property(property="sowing_period", type="string", example="03, 04"),
 *     @OA\Property(property="planting_period", type="string", example="05, 06"),
 *     @OA\Property(property="harvest_period", type="string", example="07, 08, 09"),
 *     @OA\Property(property="soil", type="string", example="Sol léger"),
 *     @OA\Property(property="watering", type="string", example="Arrosage important pendant la floraison"),
 *     @OA\Property(property="exposure", type="string", example="Plein soleil"),
 *     @OA\Property(property="maintenance", type="string", example="Biner régulièrement, pailler pour maintenir l'humidité, et butter les plants lorsqu'ils atteignent 10-15 cm")
 * )
 */
class PlantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/plants",
     *     summary="Get all plants",
     *     tags={"Plants"},
     *     @OA\Response(
     *         response=200,
     *         description="List of plants",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Plant"))
     *     )
     * )
     */
    public function index()
    {
        $plants = Plant::orderBy('name', 'asc')->get();
        return $plants;
    }

    /**
     * @OA\Post(
     *     path="/api/plants",
     *     summary="Create a new plant",
     *     tags={"Plants"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plant created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plante créée avec succès"),
     *             @OA\Property(property="plant", ref="#/components/schemas/Plant")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image',
            'type' => 'nullable|string|max:255',
            'description' => 'required|string',
            'sowing_period' => 'nullable|string|max:255',
            'planting_period' => 'nullable|string|max:255',
            'harvest_period' => 'required|string|max:255',
            'soil' => 'required',
            'watering' => 'required',
            'exposure' => 'required',
            'maintenance' => 'required',
        ], [], [
            'sowingPeriod' => 'sowing period',
            'plantingPeriod' => 'planting period'
        ]);

        if (!$request->filled('sowing_period') && !$request->filled('planting_period')) 
        {
        return back()->withErrors([
            'sowingPeriod' => 'Either sowing period or planting period must be provided.',
            'plantingPeriod' => 'Either sowing period or planting period must be provided.'
        ])->withInput();
        }

        $data = $request->all();

        $plant = Plant::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'description' => $data['description'],
            'sowing_period' => $data['sowing_period'],
            'planting_period' => $data['planting_period'],
            'harvest_period' => $data['harvest_period'],
            'soil' => $data['soil'],
            'watering' => $data['watering'],
            'exposure' => $data['exposure'],
            'maintenance' => $data['maintenance'],
        ]); 

        if ($request->hasFile('image')) {
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true 
                ]
            ]);

            $filePath = request()->file('image')->getRealPath();

            $uploadResult = (new UploadApi())->upload($filePath, [
                'folder' => 'plants/' . $plant->id, 
            ]);

            $plant->update(['image' => $uploadResult['secure_url']]);
        }

        return "Plante créée avec succès";
    }

    /**
     * @OA\Get(
     *     path="/api/plants/{id}",
     *     summary="Get a plant by ID",
     *     tags={"Plants"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the plant"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plant details",
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plant not found")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $plant = Plant::find($id);
        return $plant;
    }

    /**
     * @OA\Put(
     *     path="/api/plants/{id}",
     *     summary="Update a plant by ID",
     *     tags={"Plants"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the plant"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plant updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="La plante a bien été mise à jour."),
     *             @OA\Property(property="plant", ref="#/components/schemas/Plant")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plant not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sowing_period' => 'nullable|string|max:255',
            'planting_period' => 'nullable|string|max:255',
            'harvest_period' => 'required|string|max:255',
            'soil' => 'required',
            'watering' => 'required',
            'exposure' => 'required',
            'maintenance' => 'required',
        ]);

        if (!$request->filled('sowing_period') && !$request->filled('planting_period')) 
        {
            return back()->withErrors([
                'sowingPeriod' => 'Either sowing period or planting period must be provided.',
                'plantingPeriod' => 'Either sowing period or planting period must be provided.'
            ])->withInput();
        }

        $data = $request->all();


        $plant = Plant::find($id);

        if ($request->hasFile('image')) {
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true 
                ]
            ]);

            $filePath = request()->file('image')->getRealPath();

            $uploadResult = (new UploadApi())->upload($filePath, [
                'folder' => 'plants/' . $id, 
            ]);

            $plant->update(['image' => $uploadResult['secure_url']]);
        }
        
        $plant->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'description' => $data['description'],
            'sowing_period' => $data['sowing_period'],
            'planting_period' => $data['planting_period'],
            'harvest_period' => $data['harvest_period'],
            'soil' => $data['soil'],
            'watering' => $data['watering'],
            'exposure' => $data['exposure'],
            'maintenance' => $data['maintenance'],
        ]);
        return "La plante a bien été mise à jour.";
    }

    /**
     * @OA\Delete(
     *     path="/api/plants/{id}",
     *     summary="Delete a plant by ID",
     *     tags={"Plants"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the plant"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plant deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="La plante a été supprimée")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plant not found")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(string $id)
    {
        $plant = Plant::find($id);
        $plant->delete();
        return 'La plante a été supprimée';
    }

    /**
     * @OA\Get(
     *     path="/api/plantsbyname",
     *     summary="Search plants by name",
     *     tags={"Plants"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Name of the plant"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of plants",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Plant"))
     *     )
     * )
     */
    public function getPlantsByName(Request $request){
        $search = $request->query('search');
        $plants = Plant::where('name', 'LIKE', "%{$search}%")->get();
        return response()->json($plants);
    }

    /**
     * @OA\Get(
     *     path="/api/plantsbyperiod/{month}/{periodType}",
     *     summary="Get plants by period type and month",
     *     tags={"Plants"},
     *     @OA\Parameter(
     *         name="month",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Month as a number (01-12)"
     *     ),
     *     @OA\Parameter(
     *         name="periodType",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Type of period (sowing, planting, harvest)"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of plants",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Plant"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid period type",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid period type")
     *         )
     *     )
     * )
     */
    public function getPlantsByPeriod(int $month, string $periodType){
        $month = '%'.$month.'%';
    
        switch ($periodType) {
            case 'sowing':
                $plants = Plant::where('sowing_period', 'like', $month)->get();
                break;
            case 'planting':
                $plants = Plant::where('planting_period', 'like', $month)->get();
                break;
            case 'harvest':
                $plants = Plant::where('harvest_period', 'like', $month)->get();
                break;
            default:
                return response()->json(['error' => 'Invalid period type'], 400);
        }
    
        return $plants;
    }
}
