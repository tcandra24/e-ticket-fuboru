@extends('layouts.participant')

@section('page-style')
    <link href="{{ asset('assets/libs/lightbox/css/lightbox.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row justify-content-center w-100">
        <div class="col-10">
            @if ($registration->is_valid === 'Terverifikasi')
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    <strong>Selamat!</strong> Anda Terdaftar Dalam Acara
                    <strong>{{ $event->name }}</strong>.
                    Mohon QrCode dan Nomer Registrasi disimpan dengan baik.
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="d-block w-100 my-2">
                                    <div class="d-flex justify-content-center">
                                        <div class="d-flex flex-column">
                                            <img src="{{ asset('/storage/qr-codes/qr-code-' . $registration->token . '.svg') }}"
                                                width="250" height="250" alt="">
                                            <a href="{{ route('download.transactions.participant', ['event_id' => $event->id, 'no_registration' => $registration->registration_number]) }}"
                                                target="_blank" rel=”nofollow” class="btn btn-primary mt-3">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="d-flex flex-row flex-wrap align-items-stretch m-2">
                                    @foreach ($fields as $field)
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="p-2">
                                                <h4>{{ $field->title }}: </h4>
                                                @if (is_array($field->value) || is_object($field->value))
                                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                                        @foreach ($field->value as $value)
                                                            <span
                                                                class="badge bg-success rounded-3 fw-semibold">{{ $value->name }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p>{{ $field->value }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @if ($receiptIsExists)
                    <div class="alert alert-info alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                            fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        <strong>Bukti Transfer</strong> sudah diterima dan sedang diproses.
                    </div>
                @else
                    <div class="alert alert-danger alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                            fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        <strong>Mohon Tuggu!</strong> Pendaftaran <strong>{{ $event->name }}</strong> anda diproses.
                        Silahkan Melakukan Pembayaran
                    </div>
                @endif
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-8 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="fw-bold">
                                        {{ $event->name }}
                                    </h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="d-block w-100 my-2">
                                                <div class="d-flex justify-content-center">
                                                    <div class="d-flex flex-column">
                                                        <img src="{{ asset('/storage/qr-codes/dummy.png') }}" width="250"
                                                            style=" filter: blur(15px);" height="250" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="d-flex flex-row flex-wrap align-items-stretch m-2">
                                                @foreach ($fields as $field)
                                                    <div class="col-lg-6 col-sm-6">
                                                        <div class="p-2">
                                                            <h4>{{ $field->title }}: </h4>
                                                            @if (is_array($field->value) || is_object($field->value))
                                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                                    @foreach ($field->value as $value)
                                                                        <span
                                                                            class="badge bg-success rounded-3 fw-semibold">{{ $value->name }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p>{{ $field->value }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="fw-bold">
                                        Tagihan
                                    </h2>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-4">
                                        Rincian Tagihan
                                    </h5>

                                    <div class="row">
                                        <div class="d-flex justify-content-between">
                                            <h6>Harga</h6>
                                            <p>{{ $registration->qty }} x Rp. {{ number_format($registration->price, 0) }}
                                            </p>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <h6>Kode Bayar</h6>
                                            <p>Rp.{{ number_format(1, 0) }}</p>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between">
                                            <h6>Total</h6>
                                            <p>Rp. {{ number_format($registration->total + 1) }}</p>
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <span>
                                            <i> Silahkan Transfer Tagihan Ke Rek Ini BCA <strong>8620726002</strong> Atas
                                                Nama
                                                <strong>BGKP Santo Yakobus</strong>
                                            </i>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <button class="btn btn-danger btn-cancel"
                                            data-no-registration="{{ $registration->registration_number }}">Batalkan
                                            Pesanan</button>
                                        <form id="form-cancel-registration" method="POST"
                                            action="{{ route('destroy.transactions.participant', ['event_id' => $event->id, 'no_registration' => $registration->registration_number]) }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            Upload Bukti Transfer <span class="fw-semibold">{{ $event->name }}</span>
                        </h5>
                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                    stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                    class="me-2">
                                    <polygon
                                        points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                    </polygon>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                                <strong>Error!</strong> {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                    <span><i class="fa-solid fa-xmark"></i></span>
                                </button>
                            </div>
                        @endif

                        <div class="row mb-2">
                            @if (count($receipts) > 0)
                                @foreach ($receipts as $receipt)
                                    <div class="col-lg-4">
                                        <a href="{{ $receipt->file }}"
                                            data-lightbox="{{ $registration->registration_number }}">
                                            <img class="card-img-bottom rounded object-fit-cover"
                                                src="{{ $receipt->file }}"
                                                alt="{{ $registration->registration_number }}">
                                        </a>
                                        <button class="btn btn-danger btn-delete my-3"
                                            data-id="{{ $receipt->id }}">Hapus</button>
                                        <form id="form-delete-receipt-{{ $receipt->id }}" method="POST"
                                            action="{{ route('destroy.registrations.receipt', ['id' => $receipt->id, 'event_id' => $event->id, 'no_registration' => $registration->registration_number]) }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info alert-dismissible fade show">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                        stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                        class="me-2">
                                        <polyline points="9 11 12 14 22 4"></polyline>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    </svg>
                                    Bukti Pembayaran masih kosong
                                </div>
                            @endif
                        </div>

                        <form method="POST"
                            action="{{ route('store.registrations.receipt', ['event_id' => $event->id, 'no_registration' => $registration->registration_number]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="row">
                                <div class="col-lg-4 d-flex align-items-stretch">
                                    <div class="mb-3 w-100">
                                        <label for="upload-data" class="form-label">Upload File</label>
                                        <input type="file" name="file" accept="image/png, image/jpeg, image/jpg"
                                            class="form-control" id="upload-data" aria-describedby="file"
                                            placeholder="Masukan File" required>
                                    </div>
                                </div>
                            </div>

                            <span>
                                <p> Mohon upload file berjenis png, jpg atau jpeg</p>
                            </span>

                            <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/lightbox/js/lightbox.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $('.btn-delete').on('click', function() {
            const id = $(this).attr('data-id')

            Swal.fire({
                title: "Yakin Hapus Gambar ?",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#5d87ff",
                confirmButtonText: "Yes",
                closeOnConfirm: !1
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-delete-receipt-' + id).submit()
                }
            })

        })

        $('.btn-cancel').on('click', function() {
            const content = $(this).attr('data-no-registration')

            Swal.fire({
                title: "Yakin Batalkan Pesanan ?",
                text: content,
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#5d87ff",
                confirmButtonText: "Yes",
                closeOnConfirm: !1
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-cancel-registration').submit()
                }
            })

        })
    </script>
@endsection
