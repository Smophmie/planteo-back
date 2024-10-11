<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::orderBy('name', 'asc')->get();
        return $plants;
    }

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

        // Plant::create($data);
        return "Plante créée avec succès";
    }

    public function show(string $id)
    {
        $plant = Plant::find($id);
        return $plant;
    }

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

    public function destroy(string $id)
    {
        $plant = Plant::find($id);
        $plant->delete();
        return 'La plante a été supprimée';
    }

    public function getPlantsByName(Request $request){
        $search = $request->query('search');
        $plants = Plant::where('name', 'LIKE', "%{$search}%")->get();
        return response()->json($plants);
    }

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
