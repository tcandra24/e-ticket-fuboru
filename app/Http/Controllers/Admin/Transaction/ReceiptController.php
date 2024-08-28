<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Registration;

class ReceiptController extends Controller
{
    public function update($event_id, $registration_number)
    {
        try {
            $event = Event::where('id', $event_id)->first();

            app($event->model_path)->where('event_id', $event->id)
            ->where('registration_number', $registration_number)
            ->update([
                'is_valid' => true,
            ]);

            return redirect()->route('transaction.registrations.show', $event_id)->with('success', 'Data Registrasi Berhasil Diubah');
        } catch (\Exception $e) {
            return redirect()->route('transaction.registrations.show', $event_id)->with('error', $e->getMessage());
        }
    }

    public function show($event_id, $registration_number)
    {
        try {
            $registration = Registration::with('receipts')->where('event_id', $event_id)->where('registration_number', $registration_number)->first();
            return view('admin.transactions.registrations.validation', [
                'registration' => $registration,
                'event_id' => $event_id,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
