<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\Participant;
use Carbon\Carbon;

class ChangePasswordController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->token;
        $participant = Participant::where('token', $token)->first();
        if(!$participant){
            abort(404);
        }

        return view('participant.change-password.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Password wajib diisi',
        ]);

        try {
            $token = $request->token;
            $participant = Participant::where('token', $token)->first();

            if(!$participant){
                abort(404);
            }

            $newToken = hash_hmac('sha256', Crypt::encryptString(Str::uuid() . Carbon::now()->getTimestampMs() . $participant->name), $participant->email . $participant->name);
            Participant::where('token', $token)->update([
                'password' => Hash::make($request->password),
                'token' => $newToken
            ]);

            return redirect()->route('login.participant')->with('login-info', 'Password berhasil diganti');
        } catch (\Exception $e) {
            return back()->with('change-password-error', $e->getMessage());
        }

    }
}
