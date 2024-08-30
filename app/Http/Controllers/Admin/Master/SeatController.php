<?php

namespace App\Http\Controllers\Admin\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Seat;
use App\Models\GroupSeat;

class SeatController extends Controller
{
    public function index()
    {
        $seats = Seat::paginate(10);
        return view('admin.masters.seats.index', ['seats' => $seats]);
    }

    public function create()
    {
        $groupSeats = GroupSeat::all();

        return view('admin.masters.seats.create', ['groupSeats' => $groupSeats]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'group_seat_id' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi',
            'group_seat_id.required' => 'Group Kursi wajib diisi',
        ]);

        try {

            Seat::create([
                'name' => $request->name,
                'group_seat_id' => $request->group_seat_id
            ]);

            return redirect()->route('seats.index')->with('success', 'Kursi Berhasil Disimpan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Seat $seat)
    {
        try {
            $groupSeats = GroupSeat::all();

            return view('admin.masters.seats.edit', [ 'seat' => $seat, 'groupSeats' => $groupSeats]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Seat $seat)
    {
        $request->validate([
            'name' => 'required',
            'group_seat_id' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi',
            'group_seat_id.required' => 'Group Kursi wajib diisi',
        ]);

        try {
            $seat->update([
                'name' => $request->name,
                'group_seat_id' => $request->group_seat_id
            ]);

            return redirect()->route('seats.index')->with('success', 'Kursi Berhasil Diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $seat = Seat::findOrFail($id);
            $seat->delete();

            return redirect()->route('seats.index')->with('success', 'Kursi Berhasil Dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
