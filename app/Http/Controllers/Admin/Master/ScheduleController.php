<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Schedule;
use App\Models\Event;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('event')->paginate(10);
        return view('admin.masters.schedules.index', ['schedules' => $schedules]);
    }

    public function create()
    {
        $events = Event::where('is_active', true)->get();
        return view('admin.masters.schedules.create', [ 'events' => $events ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required',
            'event_id' => 'required'
        ], [
            'name.required' => 'Nama wajib diisi',
            'date.required' => 'Tanggal wajib diisi',
            'event_id.required' => 'Event wajib diisi'
        ]);

        try {
            Schedule::create([
                'name' => $request->name,
                'date' => $request->date,
                'event_id' => $request->event_id
            ]);

            return redirect()->route('schedules.index')->with('success', 'Jadwal Berhasil Disimpan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Schedule $schedule)
    {
        try {
            $events = Event::where('is_active', true)->get();

            return view('admin.masters.schedules.edit', [ 'schedule' => $schedule, 'events' => $events ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required',
            'event_id' => 'required'
        ], [
            'name.required' => 'Nama wajib diisi',
            'date.required' => 'Tanggal wajib diisi',
            'event_id.required' => 'Event wajib diisi'
        ]);

        try {
            $schedule->update([
                'name' => $request->name,
                'date' => $request->date,
                'event_id' => $request->event_id
            ]);

            return redirect()->route('schedules.index')->with('success', 'Jadwal Berhasil Diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();

            return redirect()->route('schedules.index')->with('success', 'Jadwal Berhasil Dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
