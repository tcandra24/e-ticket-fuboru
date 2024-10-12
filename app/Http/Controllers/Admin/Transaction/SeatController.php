<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\RegistrationSeat;

class SeatController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $event_id = $request->event_id;

            $event = Event::where('id', $event_id)->first();
            $registrations = app($event->model_path)->where('event_id', $event_id)->get();

            $seatAlreadyBook = RegistrationSeat::whereIn('registration_id', $registrations->pluck('id')->toArray())->get();

            $seatAlreadyBook = $seatAlreadyBook->map(function($item){
                if($item->seat) {
                    $objectStd = new \stdClass();
                    $objectStd->id = $item->seat_id;
                    $objectStd->name = $item->seat->name;

                    return $objectStd;
                }
            });

            return view('admin.transactions.registrations.seats', [ 'seatAlreadyBook' => $seatAlreadyBook]);
        } catch (\Exception $e) {
            return redirect()->route('transaction.registrations.show', $event_id)->with('error', $e->getMessage());
        }
    }
}
