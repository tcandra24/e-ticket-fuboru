@extends('layouts.email')

@section('content')
    <h2 class="text-center">Verifikasi Email</h2>
    <p>Peserta {{ $name }},</p>
    <p>Terimakasih telag melakukan pendaftaran ke website kami. Untuk menyelesaikan pendaftaran Anda, Harap
        verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>
    <div class="text-center">
        <a href="{{ route('participant.verify-email', $token) }}" target="_blank"
            class="btn btn-primary btn-action">Verifikasi</a>
    </div>
    <p>Jika Anda belum membuat akun, tidak ada tindakan lebih lanjut yang diperlukan.</p>
@endsection
