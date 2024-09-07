<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Receipt;
use App\Models\Registration;

class ReceiptController extends Controller
{
    public function store(Request $request, $event_id, $no_registration)
    {
        try {
            $file = $request->file('file');
            $file->storeAs('public/images/receipt', $file->hashName());

            $registrations = Registration::where('registration_number', $no_registration)->where('event_id', $event_id)->first();

            Receipt::create([
                'file' => $file->hashName(),
                'registration_id' => $registrations->id
            ]);

            return redirect()->route('finish.transactions.participant', ['event_id' => $event_id, 'no_registration' => $no_registration]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id, $event_id, $no_registration)
    {
        try {
            $receipt = Receipt::findOrFail($id);
            $receipt->delete();

            if(Storage::disk('local')->exists('public/images/receipt/'. basename($receipt->file))){
                Storage::disk('local')->delete('public/images/receipt/'. basename($receipt->file));
            }

            return redirect()->route('show.transactions.participant', ['event_id' => $event_id, 'no_registration' => $no_registration])
                ->with('success', 'Bukti Pembayaran Berhasil Dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
