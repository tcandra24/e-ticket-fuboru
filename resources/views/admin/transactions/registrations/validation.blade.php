@extends('layouts/dashboard')

@section('title')
    Validasi Transaksi
@endsection

@section('page-style')
    <link href="{{ asset('assets/libs/lightbox/css/lightbox.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Validasi Transaksi</h5>
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show m-2">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <strong>Error!</strong> {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                        <span><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
            @endif

            @if (count($registration->receipts) > 0)
                <form method="POST" id="form-upload"
                    action="{{ route('transaction.receipt.update', ['event_id' => $event_id, 'registration_number' => $registration->registration_number]) }}">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        @foreach ($registration->receipts as $receipt)
                            <div class="col-lg-4 d-flex align-items-stretch">
                                <div class="mb-3 w-100">
                                    <a href="{{ $receipt->file }}" data-lightbox="{{ $registration->registration_number }}">
                                        <img class="img-fluid" src="{{ $receipt->file }}"
                                            alt="{{ $registration->registration_number }}">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
                <button id="btn-submit" class="btn btn-primary"
                    {{ count($registration->receipts) < 1 ? 'disabled' : '' }}>Validasi</button>
            @else
                <div class="alert alert-danger alert-dismissible fade show m-2">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                        fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <strong>Tidak Ada Data Gambar
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                            <span><i class="fa-solid fa-xmark"></i></span>
                        </button>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/lightbox/js/lightbox.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $('#btn-submit').on('click', function() {
            Swal.fire({
                title: "Yakin Validasi Transaksi ?",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#5d87ff",
                confirmButtonText: "Yes",
                closeOnConfirm: !1
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-upload').submit()
                }
            })

        })
    </script>
@endsection
