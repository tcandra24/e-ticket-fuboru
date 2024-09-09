<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Jobs\SendMailJob;

use App\Mail\VerifyParticipantMail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function index()
    {
        return view('participant.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        try {

            $credentials = $request->only('email', 'password');
            if (!Auth::guard('participant')->attempt($credentials, $request->remember)) {
                throw new \Exception('Login Gagal, Username/Password salah');
            }

            $participant = Auth::guard('participant')->user();
            if (!$participant->hasVerifiedEmail()) {
                Auth::guard('participant')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                throw new \Exception('Login Gagal, Email belum terverifikasi');
            }

            $request->session()->regenerate();
            return redirect()->intended('/');
        } catch (\Exception $e) {
            return back()->with('login-error', $e->getMessage());
        }
    }

    public function register()
    {
        return view('participant.auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'name.required' => 'Nama wajib diisi',
            'password.required' => 'Password wajib diisi',
            'email.unique' => 'Email sudah digunakan',
        ]);

        try {
            $token = hash_hmac('sha256', Crypt::encryptString(Str::uuid() . Carbon::now()->getTimestampMs() . $request->name), $request->email . $request->name);

            $user = Participant::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'no_hp'     => $request->no_hp,
                'password'  => Hash::make($request->password),
                'token' => $token
            ]);

            // Mail::to($user->email)->send(new VerifyParticipantMail('Silahkan Verifikasi Email', $user->name, $token));
            $data = [
                'email' => $user->email,
                'template' => (new VerifyParticipantMail('Silahkan Verifikasi Email', $user->name, $token))
            ];
            dispatch(new SendMailJob($data));
            return redirect()->route('login.participant')->with('login-info', 'Silahkan verifikasi email anda');
        } catch (\Exception $e) {
            return back()->with('register-error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('participant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.participant');
    }
}
