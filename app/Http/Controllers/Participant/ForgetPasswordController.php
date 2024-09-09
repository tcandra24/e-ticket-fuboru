<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Mail\ForgetPasswordParticipantMail;
use App\Jobs\SendMailJob;

use App\Models\Participant;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function index()
    {
        return view('participant.forget-password.index');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
        ]);

        try {
            $email = $request->email;
            $participant = Participant::where('email', $email)->first();

            if(!$participant){
                throw new \Exception('Email Belum Terdaftar');
            }

            if(!$participant->hasVerifiedEmail()){
                throw new \Exception('Email Belum Terverikasi');
            }

            $token = hash_hmac('sha256', Crypt::encryptString(Str::uuid() . Carbon::now()->getTimestampMs() . $participant->name), $participant->email . $participant->name);

            Participant::where('email', $email)->update([
                'token' => $token
            ]);

            // Mail::to($participant->email)->send(new ForgetPasswordParticipantMail('Lupa Password', $participant->name, $token));
            $data = [
                'email' => $participant->email,
                'template' => (new ForgetPasswordParticipantMail('Reset Password', $participant->name, $token))
            ];
            dispatch(new SendMailJob($data));
            return redirect()->route('login.participant')->with('login-info', 'Silahkan cek email untuk ganti password anda');
        } catch (\Exception $e) {
            return back()->with('forget-password-error', $e->getMessage());
        }
    }
}
