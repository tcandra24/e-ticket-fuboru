<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RegistrationExport;
use App\Models\Event;

class UtilityController extends Controller
{
    public function export(Request $request, $event_id)
    {
        $event = Event::with(['forms' => function($query){
            $query->where('multiple', false)->orderBy('id');
        }])->where('id', $event_id)->first();

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

        array_unshift($objectFields, [
            'name' => 'registration_number',
            'label' => 'Nomer Registrasi',
            'model_path' => null,
            'relation_method_name' => '',
        ]);

        array_push($objectFields, [
            'name' => 'created_at',
            'label' => 'Tanggal Buat',
            'model_path' => null,
            'relation_method_name' => '',
        ]);

        array_push($objectFields, [
            'name' => 'token',
            'label' => 'Token',
            'model_path' => null,
            'relation_method_name' => '',
            'is_multiple' => false,
        ]);

        array_push($objectFields, [
            'name' => 'seats',
            'label' => 'Kursi',
            'model_path' => '\App\Models\RegistrationSeat',
            'relation_method_name' => 'seats',
            'is_multiple' => true,
        ]);

        array_push($objectFields, [
            'name' => 'is_scan',
            'label' => 'Status Scan',
            'model_path' => null,
            'relation_method_name' => '',
        ]);

        $registrations = app($event->model_path)->select('*')->when(request()->search, function($query){
            if (request()->filter === 'email') {
                $query->whereRelation('participant', 'name', 'LIKE', '%' . request()->search . '%');
            } else {
                $query->where(request()->filter, 'LIKE', '%' . request()->search . '%');
            }
        }) ->when(request()->scan, function($query){
            $query->where('is_scan', request()->scan == 'true' ? true : false);
        })
        ->when(request()->group_seats, function($query){
            $query->where('group_seat_id', request()->group_seats);
        })
        ->where('fullname', '<>', '')
        ->where('event_id', $event_id)->get();

        return Excel::download(new RegistrationExport($registrations, $objectFields), 'registrations-' . $event->slug .'.xlsx');
    }
}
