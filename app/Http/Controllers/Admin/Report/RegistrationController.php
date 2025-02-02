<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\GroupSeat;

class RegistrationController extends Controller
{
    public function index(){
        $events = Event::where('is_active', true)->get();
        return view('admin.reports.registrations.index', [ 'events' => $events]);
    }

    public function show($id)
    {
        try {
            $event = Event::with(['forms' => function($query){
                $query->where('multiple', false)->orderBy('id');
            }])->where('id', $id)->first();

            $allForm = $event->forms->map(function($item){
                return [
                    'name' => str_replace('[]', '', $item->name),
                    'label' => $item->label,
                    'model_path' => $item->model_path,
                    'relation_method_name' => $item->relation_method_name,
                ];
            });

            $headers = [...$allForm];
            $fields = [...$allForm->map(function($item){
                return $item['name'];
            })];

            array_push($fields, 'is_scan');
            array_push($fields, 'is_valid');

            $objectFields = [...$allForm->map(function($item){
                return [
                    'name' => $item['name'],
                    'label' => $item['label'],
                    'model_path' => $item['model_path'],
                    'relation_method_name' => $item['relation_method_name'],
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

            array_unshift($fields, 'registration_number');

            $registrations = app($event->model_path)->select(...$fields)->when(request()->search, function($query){
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
            ->where('event_id', $id)->paginate(10);

            $groupSeats = GroupSeat::where('name', '<>', 'undangan')->where('event_id', $event->id)->get();

            return view('admin.reports.registrations.show', [
                'fields' => $objectFields,
                'registrations' => $registrations,
                'event' => $event,
                'groupSeats' => $groupSeats,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('report.registrations.show', $event)->with('error', $e->getMessage());
        }
    }
}
