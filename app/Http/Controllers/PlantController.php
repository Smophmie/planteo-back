<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;

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

        Plant::create($request->all());
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
        
        $plant = Plant::find($id);
        $plant->update($request->all());
        return "La plante a bien été mise à jour.";
    }

    public function destroy(string $id)
    {
        $plant = Plant::find($id);
        $plant->delete();
        return 'La plante a été supprimée';
    }

    public function getPlantsBySowingPeriod(int $month){
        $month = '%'.$month.'%';
        $plants = Plant::where('sowing_period', 'like', $month)->get();
        return $plants;
    }

    public function getPlantsByPlantingPeriod(int $month){
        $month = '%'.$month.'%';
        $plants = Plant::where('planting_period', 'like', $month)->get();
        return $plants;
    }

    public function getPlantsByHarvestPeriod(int $month){
        $month = '%'.$month.'%';
        $plants = Plant::where('harvest_period', 'like', $month)->get();
        return $plants;
    }
}
