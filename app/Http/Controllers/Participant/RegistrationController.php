<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Jobs\DeleteUnvalidateRegistrationJob;

use App\Models\Event;
use App\Models\FormField;
use App\Models\GroupSeat;

class RegistrationController extends Controller
{
    public function create($event_id)
    {
        $event = Event::with('forms')->where('id', $event_id)->first();
        $forms = $event->forms->map(function($form) use ($event_id) {
            $objectStd = new \stdClass();
            $objectStd->id = $form->id;
            $objectStd->name = $form->name;
            $objectStd->label = $form->label;
            $objectStd->type = $form->type;
            $objectStd->multiple = $form->multiple;

            $objectStd->model = $form->model_path ? $form->model_path::where('is_active', true)
                ->where('event_id', $event_id)->orderBy('name')->get()
                :
                null;

            return $objectStd;
        });

        $groupSeats = GroupSeat::whereHas('event', function($query) use ($event_id){
            $query->where('id', $event_id);
        })
        // ->withCount([
        //     'registration as registration_count' => function ($query) {
        //         // $query->where('is_valid', true);
        //     },
        // ])
        ->withCount(['seats as filled_seats_count' => function ($query) {
            $query->whereHas('registrations');
        }])
        ->where('name', '<>', 'undangan')->get();

        return view('participant.registration.create', [ 'event' => $event, 'forms' => $forms, 'groupSeats' => $groupSeats ]);
    }

    public function store(Request $request)
    {
        $event = Event::where('id', $request->event_id)->first();

        $forms = FormField::select('name', 'validation_rule', 'validation_message', 'multiple')->whereHas('event', function($query) use ($request){
            $query->where('event_id', $request->event_id);
        })->get();

        $rules = [];
        $ruleMessage = [];
        $inputField = [];

        foreach($forms as $form){
            $fieldKey = str_replace('[]', '', $form->name);

            $rules[$fieldKey] = $form->validation_rule;
            $ruleMessage[$fieldKey . '.' . $form->validation_rule] = $form->validation_message;

            if(!$form->multiple){
                $inputField[$fieldKey] = $request->post($fieldKey);
            }
        }

        $request->validate($rules, $ruleMessage);

        try {
            $groupSeat = GroupSeat::select('name', 'quota', 'price') ->withCount(['seats as filled_seats_count' => function ($query) {
                $query->whereHas('registrations');
            }])->where('id', $request->group_seat_id)->first();
            $avaliableQuota = $groupSeat->quota - $groupSeat->filled_seats_count;
            $qty = $request->qty;

            if($avaliableQuota === 0){
                return redirect()
                    ->route('create.registrations.participant', $request->event_id)
                    ->with('error', 'Kuota Grup Kursi ' . $groupSeat->name . ' sudah penuh');
            } elseif($avaliableQuota < $qty){
                return redirect()
                    ->route('create.registrations.participant', $request->event_id)
                    ->with('error', 'Kuota Grup Kursi ' . $groupSeat->name . ' hanya tersisa ' . $avaliableQuota);
            }

            $participant = Auth::guard('participant')->user();
            $token = hash_hmac('sha256', Crypt::encryptString(Str::uuid() . Carbon::now()->getTimestampMs() . $participant->name), $participant->id . $participant->name);

            $length = 5;
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
            }

            QrCode::size(200)->style('round')->eye('circle')->generate($token, Storage::path('public/qr-codes/') . 'qr-code-' . $token . '.svg');
            $registration_max = app($event->model_path)->withTrashed()->where('event_id', $event->id)->count() + 1;
            $counter = app($event->model_path)->withTrashed()->count() + 1;
            $registration_number = 'EVENT-' . Str::upper($random) . '-'. str_pad($registration_max, 5, '0', STR_PAD_LEFT);

            $inputField['registration_number'] = $registration_number;
            $inputField['participant_id'] = $participant->id;
            $inputField['event_id'] = $request->event_id;
            $inputField['price'] = $groupSeat->price;
            $inputField['total'] = $request->qty * $groupSeat->price;
            $inputField['token'] = $token;
            $inputField['counter'] = $counter;

            $registration = app($event->model_path)->create($inputField);

            DeleteUnvalidateRegistrationJob::dispatch($registration)->delay(now()->addMinutes(30));

            return redirect()->route('show.transactions.participant', ['event_id' => $request->event_id, 'no_registration' => $registration->registration_number])->with('success', 'Registrasi Berhasil Disimpan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
