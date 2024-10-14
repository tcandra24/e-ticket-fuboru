<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        try {
            $events = Event::select('name', 'slug', 'image', 'description')->where('is_active', true)->get();

            return response()->json([
                'success' => true,
                'message' => 'Data Event',
                'data' => $events,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message'=> $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function show($slug)
    {
        try {
            $event = Event::with(['registration', 'registration.seats'])->where('slug', $slug)->where('is_active', true)->first();

            return response()->json([
                'success' => true,
                'message' => 'Data Detail Event',
                'data' => $event,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message'=> $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}
