@extends('layouts/auth')

@section('content')
    <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
        <img src="{{ asset('assets/images/logo.png') }}" width="180" alt="">
    </a>
    <p class="text-center">Lupa Password E-Tiket</p>
    <form method="POST" action="{{ route('store.forget-password.participant') }}">
        @csrf
        @if (session()->has('forget-password-error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('forget-password-error') }}
            </div>
        @endif
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email"
                class="form-control {{ $errors->has('email') ? 'border border-danger' : '' }}" value="" id="email"
                aria-describedby="email">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Kirim Email</button>
    </form>
@endsection
