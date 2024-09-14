<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index()
    {
        try {
            $events = Event::with('registration')->where('is_active', true)->get();

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
}
