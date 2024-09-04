<?php

namespace App\Http\Controllers\Sandbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Registration;

class TestingController extends Controller
{
    public function index()
    {
        Registration::where('registration_number',  'EVENT-1X1VI-00014')->first()->forceDelete();
    }
}
