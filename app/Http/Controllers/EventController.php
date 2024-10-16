<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', Auth::id())->get();
        return response()->json($events);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'plant_id' => 'required|exists:plants,id',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'description' => 'nullable|string|max:255',
        ]);

        $event = Event::create([
            'title' => $request->title,
            'user_id' => Auth::id(),
            'plant_id' => $request->plant_id,
            'start' => $request->start,
            'end' => $request->end,
            'description' => $request->description,
        ]);

        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Event::find($id);

        if (!$event || $event->user_id !== Auth::id()) {
            return response()->json(['message' => 'Evènement non trouvé ou non autorisé.'], 403);
        }

        return response()->json($event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
