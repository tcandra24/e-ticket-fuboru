@extends('layouts.participant')

@section('content')
    <div class="row justify-content-center w-100">
        @if (session()->has('success'))
            <div class="col-lg-4 col-md-6 col-12">
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    <strong>Success!</strong> {{ session()->get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                        <span>
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    </button>
                </div>
            </div>
        @endif
        @if (count($registrations) > 0)
            @foreach ($registrations as $registration)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="d-block w-100 m-3">
                                        <div class="d-flex justify-content-center">
                                            <div class="d-flex flex-column">
                                                <a
                                                    href="{{ route('show.transactions.participant', ['event_id' => $registration->event_id, 'no_registration' => $registration->registration_number]) }}">
                                                    <img src="{{ $registration->event->image }}" class="object-fit-cover"
                                                        width="150" height="200"
                                                        alt="{{ $registration->registration_number }}">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="d-flex flex-row flex-wrap align-items-stretch m-3">
                                        <div class="col-lg-12 col-sm-12">
                                            <div class="p-2">
                                                <h4>Nomer Registration: </h4>
                                                <a
                                                    href="{{ route('show.transactions.participant', ['event_id' => $registration->event_id, 'no_registration' => $registration->registration_number]) }}">
                                                    <p>{{ $registration->registration_number }}</p>
                                                </a>
                                                <h4>Event: </h4>
                                                <p>{{ $registration->event->name }}</p>
                                                <h4>Status: </h4>
                                                <span
                                                    class="badge {{ $registration->is_valid === 'Terverifikasi' ? 'bg-success' : 'bg-danger' }} rounded-3 fw-semibold">
                                                    {{ $registration->is_valid }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-lg-4 col-md-6 col-12">
                <div class="alert alert-info alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                        fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    Registrasi masih kosong
                </div>
            </div>
        @endif
    </div>
@endsection
