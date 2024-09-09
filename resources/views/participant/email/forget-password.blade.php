@extends('layouts.email')

@section('content')
    <h2 class="text-center">Lupa Password</h2>
    <p>Peserta {{ $name }},</p>
    <p>Silahkan reset password email Anda dengan mengklik tombol di bawah ini:</p>
    <div class="text-center">
        <a href="{{ route('index.change-password.participant', $token) }}" target="_blank"
            class="btn btn-primary btn-action">Ganti
            Password</a>
    </div>
    <p>Jika Anda belum membuat akun, tidak ada tindakan lebih lanjut yang diperlukan.</p>
@endsection
