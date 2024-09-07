<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Participant;

class VerifyEmailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $token = $request->token;
        $participant = Participant::where('verify_email_token', $token);
        if(!$participant->exists()){
            abort(404);
        }

        $participant = $participant->first();
        $participant->markEmailAsVerified();

        return redirect()->route('login.participant')->with('login-info', 'Email Berhasil diverifikasi, silahkan login');
    }
}
