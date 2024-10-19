<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Event;
use App\Models\RegistrationSeat;
use App\Models\Seat;
use App\Models\FormField;
use App\Models\GroupSeat;
use App\Models\Participant;
use App\Models\Receipt;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function index(){
        $events = Event::where('is_active', true)->get();
        return view('admin.transactions.registrations.index', [ 'events' => $events]);
    }

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

        // $seats = Seat::where('group_seat_id', $registration->group_seat_id)->get();
        // $seatAlreadyBook = RegistrationSeat::with(['seat' => function($query) use ($registration){
        //     $query->where('group_seat_id', $registration->group_seat_id);
        // }])->get();

        return view('admin.transactions.registrations.create', [ 'event' => $event, 'forms' => $forms ]);
    }

    public function store(Request $request, $event_id)
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
            $groupSeat = GroupSeat::select('name', 'quota', 'price')->withCount([
                'registration as registration_count' => function ($query) {
                    $query->where('is_valid', true);
                },
            ])->where('id', $request->group_seat_id)->first();
            $avaliableQuota = $groupSeat->quota - $groupSeat->registration_count;
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

            $participant = Participant::where('email', 'umum@gmail.com')->first();
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
            $inputField['is_valid'] = true;
            $inputField['counter'] = $counter;

            $registration = app($event->model_path)->create($inputField);

            return redirect()->route('transaction.registrations.detail', ['event_id' =>  $request->event_id, 'registration_number' => $registration->registration_number])->with('success', 'Registrasi Berhasil Disimpan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $event = Event::with(['forms' => function($query){
                $query->orderBy('id');
            }])->where('id', $id)->first();

            $allForm = $event->forms->map(function($item){
                return [
                    'name' => str_replace('[]', '', $item->name),
                    'label' => $item->label,
                    'model_path' => $item->model_path,
                    'relation_method_name' => $item->relation_method_name,
                    'is_multiple' => $item->multiple ? true : false,
                ];
            });

            $objectFields = [...$allForm->map(function($item){
                return [
                    'name' => $item['name'],
                    'label' => $item['label'],
                    'model_path' => $item['model_path'],
                    'relation_method_name' => $item['relation_method_name'],
                    'is_multiple' => $item['is_multiple'],
                ];
            })];

            array_push($objectFields, [
                'name' => 'is_scan',
                'label' => 'Status Scan',
                'model_path' => null,
                'relation_method_name' => null,
                'is_multiple' => false,
            ]);

            array_push($objectFields, [
                'name' => 'is_valid',
                'label' => 'Status Verifikasi',
                'model_path' => null,
                'relation_method_name' => null,
                'is_multiple' => false,
            ]);

            array_push($objectFields, [
                'name' => 'seats',
                'label' => 'Kursi',
                'model_path' => '\App\Models\RegistrationSeat',
                'relation_method_name' => 'seats',
                'is_multiple' => true,
            ]);

            array_unshift($objectFields, [
                'name' => 'registration_number',
                'label' => 'Nomer Registrasi',
                'model_path' => null,
                'relation_method_name' => null,
                'is_multiple' => false,
            ]);

            $registrations = app($event->model_path)->select('*')->when(request()->search, function($query){
                if (request()->filter === 'email') {
                    $query->whereRelation('participant', 'email', 'LIKE', '%' . request()->search . '%');
                } else {
                    $query->where(request()->filter, 'LIKE', '%' . request()->search . '%');
                }
            }) ->when(request()->scan, function($query){
                $query->where('is_scan', request()->scan == 'true' ? true : false);
            })
            ->when(request()->valid, function($query){
                $query->where('is_valid', request()->valid == 'true' ? true : false);
            })
            ->when(request()->group_seats, function($query){
                $query->where('group_seat_id', request()->group_seats);
            })
            ->when(request()->seats, function($query){
                $query->whereHas('seats', function($query){
                    $query->whereIn('id', request()->seats);
                });
            })
            ->where('event_id', $id)->paginate(10);

            $groupSeats = GroupSeat::where('name', '<>', 'undangan')->where('event_id', $event->id)->get();
            $seats = Seat::whereHas('groupSeat', function($query) use ($event){
                $query->where('event_id', $event->id);
            })->get();

            $defaultSeats = [];
            if(request()->has('seats')){
                $defaultSeats = request()->seats;
            }

            return view('admin.transactions.registrations.show', [
                'fields' => $objectFields,
                'registrations' => $registrations,
                'event' => $event,
                'groupSeats' => $groupSeats,
                'seats' => $seats,
                'defaultSeats'=> $defaultSeats
            ]);
        } catch (\Exception $e) {
            return redirect()->route('transaction.registrations.show', $event)->with('error', $e->getMessage());
        }
    }

    public function destroy($event_id, $registration_number)
    {
        try {
            $event = Event::where('id', $event_id)->first();
            DB::transaction(function() use ($event, $event_id, $registration_number){
                $registration = app($event->model_path)->where('event_id', $event_id)->where('registration_number', $registration_number)->first();
                RegistrationSeat::where('registration_id', $registration->id)->delete();

                foreach($registration->receipts()->get() as $receipt){
                    if(Storage::disk('local')->exists('public/images/receipt/'. basename($receipt->file))){
                        Storage::disk('local')->delete('public/images/receipt/'. basename($receipt->file));
                    }
                }

                Receipt::where('registration_id', $registration->id)->delete();
                $registration->delete();
            });

            return redirect()->route('transaction.registrations.show', $event_id)->with('success', 'Data Registrasi Berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('transaction.registrations.show', $event_id)->with('error', $e->getMessage());
        }
    }

    public function update($event_id, $registration_number)
    {
        try {
            $event = Event::where('id', $event_id)->first();

            app($event->model_path)->where('event_id', $event->id)
            ->where('registration_number', $registration_number)
            ->update([
                'is_scan' => true,
                'scan_date' => Carbon::now(),
            ]);

            return redirect()->route('transaction.registrations.show', $event_id)->with('success', 'Data Registrasi Berhasil Diubah');
        } catch (\Exception $e) {
            return redirect()->route('transaction.registrations.show', $event_id)->with('error', $e->getMessage());
        }
    }

    public function detail($event_id, $registration_number)
    {
        try {
            $event = Event::where('id', $event_id)->first();
            $registration = app($event->model_path)->with(['seats', 'receipts'])->where('event_id', $event_id)->where('registration_number', $registration_number)->first();

            $seats = Seat::where('group_seat_id', $registration->group_seat_id)->get();
            $seatAlreadyBook = RegistrationSeat::with(['seat' => function($query) use ($registration){
                $query->where('group_seat_id', $registration->group_seat_id);
            }])->get();

            $seatAlreadyBook = $seatAlreadyBook->map(function($item){
                if($item->seat) {
                    $objectStd = new \stdClass();
                    $objectStd->id = $item->seat_id;
                    $objectStd->name = $item->seat->name;

                    return $objectStd;
                }
            });

            return view('admin.transactions.registrations.detail', [ 'registration' => $registration, 'seats' => $seats, 'seatAlreadyBook' => $seatAlreadyBook]);
        } catch (\Exception $e) {
            return redirect()->route('transaction.registrations.show', $event_id)->with('error', $e->getMessage());
        }
    }
}
