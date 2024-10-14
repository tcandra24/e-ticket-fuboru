<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\UpdateRegisterData;

use App\Models\Registration;
use Carbon\Carbon;

class ScanController extends Controller
{
    public function scan(Request $request)
    {
        try {
            $token = $request->token;

            $registration = Registration::where('token', $token);

            if (!$registration->exists()) {
                throw new \Exception('Token tidak ditemukan');
            }

            if ($registration->first()->is_scan === 'Sudah Scan') {
                throw new \Exception('User Sudah Scan QrCode');
            }

            $registration->update([
                'is_scan' => true,
                'scan_date' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Scan berhasil',
                'data' => $registration->with(['seats'])->first()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message'=> $e->getMessage(),
            ], 400);
        }
    }

    // public function manualCheckIn(Request $request)
    // {
    //     try {
    //         $noRegistration = $request->noRegistration;
    //         // $event = $request->event;

    //         // if($event === 'mechanic-gathering') {
    //         //     $registration = RegistrationMechanic::where('registration_number', $noRegistration);
    //         // } else {
    //         //     $registration = Registration::where('registration_number', $noRegistration);
    //         // }

    //         $registration = RegistrationMechanic::where('registration_number', $noRegistration);

    //         if (!$registration->exists()) {
    //             throw new \Exception('Nomer Registrasi tidak ditemukan');
    //         }

    //         if ($registration->first()->is_scan) {
    //             throw new \Exception('User Sudah Scan QrCode');
    //         }

    //         $registration->update([
    //             'is_scan' => true,
    //             'scan_date' => Carbon::now()
    //         ]);

    //         event(new UpdateRegisterData('scan-manual', $registration->first()));

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Check In berhasil'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message'=> $e->getMessage()
    //         ], 400);
    //     }
    // }
}
