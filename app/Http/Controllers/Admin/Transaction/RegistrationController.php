<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Models\Event;
use App\Models\RegistrationSeat;
use App\Models\Seat;

class RegistrationController extends Controller
{
    public function index(){
        $events = Event::where('is_active', true)->get();
        return view('admin.transactions.registrations.index', [ 'events' => $events]);
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

            array_unshift($objectFields, [
                'name' => 'registration_number',
                'label' => 'Nomer Registrasi',
                'model_path' => null,
                'relation_method_name' => null,
                'is_multiple' => false,
            ]);

            $registrations = app($event->model_path)->select('*')->when(request()->search, function($query){
                if (request()->filter === 'email') {
                    $query->whereRelation('user', 'name', 'LIKE', '%' . request()->search . '%');
                } else {
                    $query->where(request()->filter, 'LIKE', '%' . request()->search . '%');
                }
            }) ->when(request()->scan, function($query){
                $query->where('is_scan', request()->scan == 'true' ? true : false);
            })
            ->when(request()->valid, function($query){
                $query->where('is_valid', request()->valid == 'true' ? true : false);
            })
            ->where('event_id', $id)->paginate(10);

            return view('admin.transactions.registrations.show', [
                'fields' => $objectFields,
                'registrations' => $registrations,
                'event' => $event
            ]);
        } catch (\Exception $e) {
            return redirect()->route('transaction.registrations.show', $event)->with('error', $e->getMessage());
        }
    }

    public function destroy($event_id, $registration_number)
    {
        try {
            $event = Event::where('id', $event_id)->first();

            $registration = app($event->model_path)->where('event_id', $event_id)->where('registration_number', $registration_number);
            $registration->delete();

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
            $registration = app($event->model_path)->with('seats')->where('event_id', $event_id)->where('registration_number', $registration_number)->first();

            $seats = Seat::where('group_seat_id', $registration->group_seat_id)->get();
            $seatAlreadyBook = RegistrationSeat::with(['seat' => function($query) use ($registration){
                $query->where('group_seat_id', $registration->group_seat_id);
            }])->get();

            // return response()->json($registration->group_seat_id);

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
