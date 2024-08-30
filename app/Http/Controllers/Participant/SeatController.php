<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Seat;
use App\Models\Event;

class SeatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'seats' => 'required',
        ], [
            'seats.required' => 'Kursi wajib diisi',
        ]);

        try {
            $registration_number = $request->registration_number;
            $event_id = $request->event_id;

            $event = Event::where('id', $event_id)->first();

            $registration = app($event->model_path)->where('event_id', $event_id)->where('registration_number', $registration_number)->first();
            $seats = Seat::select('id')->whereIn('id', $request->seats)->get();

            $registration->seats()->sync($seats);

            return redirect()->route('transaction.registrations.detail', ['event_id' => $event_id, 'registration_number' => $registration_number])->with('success', 'Perubahan Kursi Berhasil Disimpan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
