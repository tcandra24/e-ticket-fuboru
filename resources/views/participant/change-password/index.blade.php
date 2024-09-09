@extends('layouts/auth')

@section('content')
    <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
        <img src="{{ asset('assets/images/logo.png') }}" width="180" alt="">
    </a>
    <p class="text-center">Lupa Password E-Tiket</p>
    <form method="POST" action="{{ route('store.change-password.participant', request()->token) }}">
        @csrf
        @if (session()->has('change-password-error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('forget-password-error') }}
            </div>
        @endif
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password"
                class="form-control {{ $errors->has('password') ? 'border border-danger' : '' }}" id="password">
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Ganti Password</button>
    </form>
@endsection
