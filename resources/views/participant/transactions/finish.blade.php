@extends('layouts.participant')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-12">
                                        <h4 class="card-title fw-semibold text-center">
                                            Terima kasih telah membeli Tiket
                                        </h4>
                                        <p class="text-center text-uppercase">
                                            <b>Drama Musikal SAILOR'S BIBLE</b>
                                        </p>
                                        <p class="text-center">
                                            QR code yang berisi nomer kursi akan dikirimkan melalui nomer WA yang terdaftar
                                            saat pembelian pada hari H-3.
                                            <br>
                                            Mohon disimpan dengan baik dan ditukarkan dengan tanda masuk pada hari H di meja
                                            registrasi yang tersedia.
                                        </p>
                                        <p class="text-center">
                                            Apabila ada pertanyaan seputar ticketing, dapat menguhubungi:
                                            <br>
                                            <b>0812-2958-2025</b>
                                            <br>
                                            <b>0813-3773-2732</b>
                                        </p>
                                        <p class="text-center">
                                            Acara ini dipersembahkan oleh:
                                        </p>
                                        <div class="d-flex justify-content-center" style="gap: 5px;">
                                            <img src="{{ asset('/assets/images/presented-3.png') }}" width="150"
                                                class="img-fluid p-2" alt="Logo 3">
                                            <img src="{{ asset('/assets/images/presented-2.png') }}" width="150"
                                                class="img-fluid p-2" alt="Logo 2">
                                        </div>
                                        <div class="d-flex justify-content-center" style="gap: 5px;">
                                            <img src="{{ asset('/assets/images/presented-1.png') }}" width="250"
                                                class="img-fluid p-2" alt="Logo 1">
                                        </div>
                                        <div class="d-flex justify-content-center my-3" style="gap: 5px;">
                                            <a class="btn btn-primary" href="{{ route('participant.index') }}">
                                                Beranda
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
