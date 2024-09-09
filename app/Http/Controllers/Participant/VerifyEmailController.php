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
        $participant = Participant::where('token', $token)->first();
        if(!$participant){
            abort(404);
        }

        if($participant->hasVerifiedEmail()){
            abort(404);
        }

        $participant->markEmailAsVerified();

        return redirect()->route('login.participant')->with('login-info', 'Email Berhasil diverifikasi, silahkan login');
    }
}
