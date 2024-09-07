@extends('layouts.participant')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-5 col-sm-12 col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-12">
                                        <h4 class="card-title mb-9 fw-semibold text-center">
                                            Terimakasih Telah Membeli Tiket Event
                                        </h4>
                                        <p class="text-center text-uppercase">
                                            <b>{{ $event->name }}</b>
                                        </p>
                                        <img src="{{ asset('/storage/images/thankyou.svg') }}" class="img-fluid my-5 p-2"
                                            alt="Terimakasih">
                                        <div class="d-flex" style="gap: 5px;">
                                            <a class="btn btn-primary" href="{{ route('participant.index') }}">
                                                Halaman Utama
                                            </a>
                                            <a class="btn btn-secondary"
                                                href="{{ route('show.transactions.participant', ['event_id' => $event->id, 'no_registration' => $no_registration]) }}">
                                                Ubah/Tambah Foto Bukti Transfer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
