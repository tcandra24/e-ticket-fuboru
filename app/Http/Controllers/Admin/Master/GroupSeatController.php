<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GroupSeat;
use App\Models\Event;
use App\Models\Schedule;

class GroupSeatController extends Controller
{
    public function index()
    {
        $groupSeats = GroupSeat::with('event')
        // ->withCount([
        //     'registration as registration_count' => function ($query) {
        //         // $query->where('is_valid', true);
        //     },
        // ])
        // ->withCount('seats')
        ->withCount(['seats as filled_seats_count' => function ($query) {
            $query->whereHas('registrations');
        }])
        ->paginate(10);

        return view('admin.masters.group-seats.index', ['groupSeats' => $groupSeats]);
    }

    public function create()
    {
        $events = Event::where('is_active', true)->get();
        $schedules = Schedule::where('is_active', true)->get();

        return view('admin.masters.group-seats.create', [ 'events' => $events, 'schedules' => $schedules ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'quota' => 'required',
            'event_id' => 'required',
            'schedule_id' => 'required',
            'price' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi',
            'quota.required' => 'Kuota wajib diisi',
            'event_id.required' => 'Event wajib diisi',
            'schedule_id.required' => 'Jadwal wajib diisi',
            'price.required' => 'Harga wajib diisi'
        ]);

        try {

            GroupSeat::create([
                'name' => $request->name,
                'quota' => $request->quota,
                'event_id' => $request->event_id,
                'schedule_id' => $request->schedule_id,
                'price' => $request->price
            ]);

            return redirect()->route('group-seats.index')->with('success', 'Grup Kursi Berhasil Disimpan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(GroupSeat $groupSeat)
    {
        try {
            $events = Event::where('is_active', true)->get();
            $schedules = Schedule::where('is_active', true)->get();

            return view('admin.masters.group-seats.edit', [ 'groupSeat' => $groupSeat, 'events' => $events, 'schedules' => $schedules ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, GroupSeat $groupSeat)
    {
        $request->validate([
            'name' => 'required',
            'quota' => 'required',
            'event_id' => 'required',
            'schedule_id' => 'required',
            'price' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi',
            'quota.required' => 'Kuota wajib diisi',
            'event_id.required' => 'Event wajib diisi',
            'schedule_id.required' => 'Jadwal wajib diisi',
            'price.required' => 'Harga wajib diisi'
        ]);

        try {
            $groupSeat->update([
                'name' => $request->name,
                'quota' => $request->quota,
                'event_id' => $request->event_id,
                'schedule_id' => $request->schedule_id,
                'price' => $request->price
            ]);

            return redirect()->route('group-seats.index')->with('success', 'Grup Kursi Berhasil Diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $groupSeat = GroupSeat::findOrFail($id);
            $groupSeat->delete();

            return redirect()->route('group-seats.index')->with('success', 'Grup Kursi Berhasil Dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
