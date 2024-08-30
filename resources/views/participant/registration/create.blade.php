@extends('layouts.participant')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2-bootstrap-5-theme.min.css') }}" />
    <style>
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            box-shadow: unset !important;
        }

        .btn-vvip {
            background: #ecbee6;
            border-color: #ecbee6;
            color: #201f21;
        }

        .btn-vvip:hover {
            background: #c0a0bc;
            border-color: #c0a0bc;
            color: #dadada;
        }

        .btn-reguler {
            background: #f7f12c;
            border-color: #f7f12c;
            color: #201f21;
        }

        .btn-reguler:hover {
            background: #c2bd22;
            border-color: #c2bd22;
            color: #dadada;
        }

        .btn-padding {
            padding: 5px !important;
        }

        .btn-light {
            background-color: #c2c2c2 !important
        }

        .btn-light:disabled {
            background-color: #ffffff !important;
            border-color: #ffffff !important;
        }

        .stage {
            height: 50px;
            background-color: #333;
            color: #fff;
            text-align: center;
            line-height: 50px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="row justify-content-center w-100">
        <div class="col-10">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-2">
                        Input Data Registrasi <span class="fw-semibold">{{ $event->name }}</span>
                    </h5>
                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
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

                    <div class="d-flex mb-3">
                        <div class="col-lg-6">
                            {!! $event->description !!}
                            <h4 class="mt-3">
                                Harga tiket
                            </h4>
                            <p class="mb-0">
                                VVIP : Rp. 350.000
                                </h6>
                            <p class="mb-0">
                                VIP : Rp. 200.000
                            </p>
                            <p class="mb-0">
                                Reguler : 100.000
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('store.registrations.participant') }}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="row">
                            @foreach ($forms as $form)
                                <div class="col-lg-4 d-flex align-items-stretch">
                                    <div class="mb-3 w-100">
                                        <label for="{{ $form->label }}"
                                            class="form-label">{{ ucwords($form->label) }}</label>
                                        @if ($form->type === 'text' || $form->type === 'email' || $form->type === 'number')
                                            <input type="{{ $form->type }}" name="{{ $form->name }}"
                                                class="form-control" id="{{ $form->label }}"
                                                aria-describedby="{{ $form->label }}"
                                                placeholder="Masukan {{ $form->label }}">
                                        @elseif($form->type === 'select')
                                            <select class="form-control select2-elements" name="{{ $form->name }}"
                                                id="{{ $form->label }}" {{ $form->multiple ? 'multiple' : '' }}>
                                                <option value="">Pilih {{ $form->label }}</option>
                                                @foreach ($form->model as $data)
                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($form->type === 'textarea')
                                            <textarea class="form-control" name="{{ $form->name }}" id="{{ $form->label }}" cols="30" rows="10"></textarea>
                                        @endif

                                        @error($form->name)
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="button" class="btn btn-info my-2" data-bs-toggle="modal"
                            data-bs-target="#modal-mapping">
                            Mapping Tempat Duduk
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-mapping" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Mapping Tempat Duduk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-row p-2 w-100" style="gap: 40px;">
                            <div class="d-flex flex-column bd-highlight" style="gap: 5px;">
                                <div class="d-flex flex-row bd-highlight" style="gap: 5px; opacity: 0;">
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">X1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X9</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X10</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">W1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W9</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">V1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V9</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">U1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U9</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">T1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T9</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">S1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S9</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">R1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R9</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">Q1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q9</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">P1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P9</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">O1</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O2</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O3</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O4</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O5</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O6</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O7</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O8</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O9</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">N1</button>
                                    <button type="button" class="btn btn-padding btn-light">N2</button>
                                    <button type="button" class="btn btn-padding btn-light">N3</button>
                                    <button type="button" class="btn btn-padding btn-light">N4</button>
                                    <button type="button" class="btn btn-padding btn-light">N5</button>
                                    <button type="button" class="btn btn-padding btn-light">N6</button>
                                    <button type="button" class="btn btn-padding btn-light">N7</button>
                                    <button type="button" class="btn btn-padding btn-light">N8</button>
                                    <button type="button" class="btn btn-padding btn-light">N9</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">M1</button>
                                    <button type="button" class="btn btn-padding btn-light">M2</button>
                                    <button type="button" class="btn btn-padding btn-light">M3</button>
                                    <button type="button" class="btn btn-padding btn-light">M4</button>
                                    <button type="button" class="btn btn-padding btn-light">M5</button>
                                    <button type="button" class="btn btn-padding btn-light">M6</button>
                                    <button type="button" class="btn btn-padding btn-light">M7</button>
                                    <button type="button" class="btn btn-padding btn-light">M8</button>
                                    <button type="button" class="btn btn-padding btn-light">M9</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight" style="gap: 5px; opacity: 0;">
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight" style="gap: 5px; opacity: 0;">
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight" style="gap: 5px; opacity: 0;">
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight" style="gap: 5px; opacity: 0;">
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">J1</button>
                                    <button type="button" class="btn btn-padding btn-light">J2</button>
                                    <button type="button" class="btn btn-padding btn-light">J3</button>
                                    <button type="button" class="btn btn-padding btn-light">J4</button>
                                    <button type="button" class="btn btn-padding btn-light">J5</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J6</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J7</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">I1</button>
                                    <button type="button" class="btn btn-padding btn-light">I2</button>
                                    <button type="button" class="btn btn-padding btn-light">I3</button>
                                    <button type="button" class="btn btn-padding btn-light">I4</button>
                                    <button type="button" class="btn btn-padding btn-light">I5</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I6</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I7</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">H1</button>
                                    <button type="button" class="btn btn-padding btn-light">H2</button>
                                    <button type="button" class="btn btn-padding btn-light">H3</button>
                                    <button type="button" class="btn btn-padding btn-light">H4</button>
                                    <button type="button" class="btn btn-padding btn-light">H5</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H6</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H7</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">G1</button>
                                    <button type="button" class="btn btn-padding btn-light">G2</button>
                                    <button type="button" class="btn btn-padding btn-light">G3</button>
                                    <button type="button" class="btn btn-padding btn-light">G4</button>
                                    <button type="button" class="btn btn-padding btn-light">G5</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G6</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G7</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">F1</button>
                                    <button type="button" class="btn btn-padding btn-light">F2</button>
                                    <button type="button" class="btn btn-padding btn-light">F3</button>
                                    <button type="button" class="btn btn-padding btn-light">F4</button>
                                    <button type="button" class="btn btn-padding btn-light">F5</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F6</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F7</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">E1</button>
                                    <button type="button" class="btn btn-padding btn-light">E2</button>
                                    <button type="button" class="btn btn-padding btn-light">E3</button>
                                    <button type="button" class="btn btn-padding btn-light">E4</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E5</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E6</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E7</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">D1</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D2</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D3</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D4</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D5</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D6</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D7</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">C1</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C2</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C3</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C4</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C5</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C6</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C7</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary">B1</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B2</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B3</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B4</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B5</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B6</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B7</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary">A1</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A2</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A3</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A4</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A5</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A6</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A7</button>
                                </div>
                            </div>
                            {{-- =========================== --}}
                            <div class="d-flex flex-column bd-highlight" style="gap: 5px;">
                                <div class="d-flex flex-row bd-highlight" style="gap: 5px; opacity: 0;">
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">X11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X21</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X23</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">W11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W21</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W23</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">V11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V21</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">U11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U21</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">T11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T21</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">S11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S21</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">R11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R21</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">Q11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q21</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">P10</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P21</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">O10</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O11</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O12</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O13</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O14</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O15</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O16</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O17</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O18</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O19</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O20</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O21</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">N10</button>
                                    <button type="button" class="btn btn-padding btn-light">N11</button>
                                    <button type="button" class="btn btn-padding btn-light">N12</button>
                                    <button type="button" class="btn btn-padding btn-light">N13</button>
                                    <button type="button" class="btn btn-padding btn-light">N14</button>
                                    <button type="button" class="btn btn-padding btn-light">N15</button>
                                    <button type="button" class="btn btn-padding btn-light">N16</button>
                                    <button type="button" class="btn btn-padding btn-light">N17</button>
                                    <button type="button" class="btn btn-padding btn-light">N18</button>
                                    <button type="button" class="btn btn-padding btn-light">N19</button>
                                    <button type="button" class="btn btn-padding btn-light">N20</button>
                                    <button type="button" class="btn btn-padding btn-light">N21</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">M10</button>
                                    <button type="button" class="btn btn-padding btn-light">M11</button>
                                    <button type="button" class="btn btn-padding btn-light">M12</button>
                                    <button type="button" class="btn btn-padding btn-light">M13</button>
                                    <button type="button" class="btn btn-padding btn-light">M14</button>
                                    <button type="button" class="btn btn-padding btn-light">M15</button>
                                    <button type="button" class="btn btn-padding btn-light">M16</button>
                                    <button type="button" class="btn btn-padding btn-light">M17</button>
                                    <button type="button" class="btn btn-padding btn-light">M18</button>
                                    <button type="button" class="btn btn-padding btn-light">M19</button>
                                    <button type="button" class="btn btn-padding btn-light">M20</button>
                                    <button type="button" class="btn btn-padding btn-light">M21</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">L8</button>
                                    <button type="button" class="btn btn-padding btn-light">L9</button>
                                    <button type="button" class="btn btn-padding btn-light">L10</button>
                                    <button type="button" class="btn btn-padding btn-light">L11</button>
                                    <button type="button" class="btn btn-padding btn-light">L12</button>
                                    <button type="button" class="btn btn-padding btn-light">L13</button>
                                    <button type="button" class="btn btn-padding btn-light">L14</button>
                                    <button type="button" class="btn btn-padding btn-light">L15</button>
                                    <button type="button" class="btn btn-padding btn-light">L16</button>
                                    <button type="button" class="btn btn-padding btn-light">L17</button>
                                    <button type="button" class="btn btn-padding btn-light">L18</button>
                                    <button type="button" class="btn btn-padding btn-light">L19</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">K8</button>
                                    <button type="button" class="btn btn-padding btn-light">K9</button>
                                    <button type="button" class="btn btn-padding btn-light">K10</button>
                                    <button type="button" class="btn btn-padding btn-light">K11</button>
                                    <button type="button" class="btn btn-padding btn-light">K12</button>
                                    <button type="button" class="btn btn-padding btn-light">K13</button>
                                    <button type="button" class="btn btn-padding btn-light">K14</button>
                                    <button type="button" class="btn btn-padding btn-light">K15</button>
                                    <button type="button" class="btn btn-padding btn-light">K16</button>
                                    <button type="button" class="btn btn-padding btn-light">K17</button>
                                    <button type="button" class="btn btn-padding btn-light">K18</button>
                                    <button type="button" class="btn btn-padding btn-light">K19</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight" style="gap: 5px; opacity: 0;">
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight" style="gap: 5px; opacity: 0;">
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">J9</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J10</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J11</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J12</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J13</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J14</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J15</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J16</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J17</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J18</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J19</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">I9</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I10</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I11</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I12</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I13</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I14</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I15</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I16</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I17</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I18</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I19</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">H9</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H10</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H11</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H12</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H13</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H14</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H15</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H16</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H17</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H18</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H19</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">G9</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G10</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G11</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G12</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G13</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G14</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G15</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G16</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G17</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G18</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G19</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">F9</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F10</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F11</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F12</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F13</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F14</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F15</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F16</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F17</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F18</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F19</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">E8</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E9</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E10</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E11</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E12</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E13</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E14</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E15</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E16</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E17</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E18</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">D8</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D9</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D10</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D11</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D12</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D13</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D14</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D15</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D16</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D17</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D18</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">C8</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C9</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C10</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C11</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C12</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C13</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C14</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C15</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C16</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C17</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C18</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary">B8</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B9</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B10</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B11</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B12</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B13</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B14</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B15</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B16</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B17</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B18</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary">A8</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A9</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A10</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A11</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A12</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A13</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A14</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A15</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A16</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A17</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A18</button>
                                </div>

                                <div class="w-100 mt-5">
                                    <div class="stage">STAGE</div>
                                </div>
                            </div>
                            {{-- ===================== --}}
                            <div class="d-flex flex-column bd-highlight" style="gap: 5px;">
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">Y24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Y25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Y26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Y27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Y28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Y29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Y30</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Y31</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Y32</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Y33</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">X24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X30</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X31</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X32</button>
                                    <button type="button" class="btn btn-padding btn-reguler">X33</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">W24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W30</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W31</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W32</button>
                                    <button type="button" class="btn btn-padding btn-reguler">W33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">V24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V30</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V31</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V32</button>
                                    <button type="button" class="btn btn-padding btn-reguler">V33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">U24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U30</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U31</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U32</button>
                                    <button type="button" class="btn btn-padding btn-reguler">U33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">T24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T30</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T31</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T32</button>
                                    <button type="button" class="btn btn-padding btn-reguler">T33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">S24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S30</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S31</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S32</button>
                                    <button type="button" class="btn btn-padding btn-reguler">S33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">R24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R30</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R31</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R32</button>
                                    <button type="button" class="btn btn-padding btn-reguler">R33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">Q24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q30</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q31</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q32</button>
                                    <button type="button" class="btn btn-padding btn-reguler">Q33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">P22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P23</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">P30</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler">O22</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O23</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O24</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O25</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O26</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O27</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O28</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O29</button>
                                    <button type="button" class="btn btn-padding btn-reguler">O30</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">N22</button>
                                    <button type="button" class="btn btn-padding btn-light">N23</button>
                                    <button type="button" class="btn btn-padding btn-light">N24</button>
                                    <button type="button" class="btn btn-padding btn-light">N25</button>
                                    <button type="button" class="btn btn-padding btn-light">N26</button>
                                    <button type="button" class="btn btn-padding btn-light">N27</button>
                                    <button type="button" class="btn btn-padding btn-light">N28</button>
                                    <button type="button" class="btn btn-padding btn-light">N29</button>
                                    <button type="button" class="btn btn-padding btn-light">N30</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">M22</button>
                                    <button type="button" class="btn btn-padding btn-light">M23</button>
                                    <button type="button" class="btn btn-padding btn-light">M24</button>
                                    <button type="button" class="btn btn-padding btn-light">M25</button>
                                    <button type="button" class="btn btn-padding btn-light">M26</button>
                                    <button type="button" class="btn btn-padding btn-light">M27</button>
                                    <button type="button" class="btn btn-padding btn-light">M28</button>
                                    <button type="button" class="btn btn-padding btn-light">M29</button>
                                    <button type="button" class="btn btn-padding btn-light">M30</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">L20</button>
                                    <button type="button" class="btn btn-padding btn-light">L21</button>
                                    <button type="button" class="btn btn-padding btn-light">L22</button>
                                    <button type="button" class="btn btn-padding btn-light">L23</button>
                                    <button type="button" class="btn btn-padding btn-light">L24</button>
                                    <button type="button" class="btn btn-padding btn-light">L25</button>
                                    <button type="button" class="btn btn-padding btn-light">L26</button>
                                    <button type="button" class="btn btn-padding btn-light">L27</button>
                                    <button type="button" class="btn btn-padding btn-light">L28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">K20</button>
                                    <button type="button" class="btn btn-padding btn-light">K21</button>
                                    <button type="button" class="btn btn-padding btn-light">K22</button>
                                    <button type="button" class="btn btn-padding btn-light">K23</button>
                                    <button type="button" class="btn btn-padding btn-light">K24</button>
                                    <button type="button" class="btn btn-padding btn-light">K25</button>
                                    <button type="button" class="btn btn-padding btn-light">K26</button>
                                    <button type="button" class="btn btn-padding btn-light">K27</button>
                                    <button type="button" class="btn btn-padding btn-light">K28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light">JA21</button>
                                    <button type="button" class="btn btn-padding btn-light">JA22</button>
                                    <button type="button" class="btn btn-padding btn-light">JA23</button>
                                    <button type="button" class="btn btn-padding btn-light">JA24</button>
                                    <button type="button" class="btn btn-padding btn-light">JA25</button>
                                    <button type="button" class="btn btn-padding btn-light">JA26</button>
                                    <button type="button" class="btn btn-padding btn-light">JA27</button>
                                    <button type="button" class="btn btn-padding btn-light">JA28</button>
                                    <button type="button" class="btn btn-padding btn-light">JA29</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight" style="gap: 5px; opacity: 0;">
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                    <button type="button" class="btn">-</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">J21</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J22</button>
                                    <button type="button" class="btn btn-padding btn-vvip">J23</button>
                                    <button type="button" class="btn btn-padding btn-light">J24</button>
                                    <button type="button" class="btn btn-padding btn-light">J25</button>
                                    <button type="button" class="btn btn-padding btn-light">J26</button>
                                    <button type="button" class="btn btn-padding btn-light">J27</button>
                                    <button type="button" class="btn btn-padding btn-light">J28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">I21</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I22</button>
                                    <button type="button" class="btn btn-padding btn-vvip">I23</button>
                                    <button type="button" class="btn btn-padding btn-light">I24</button>
                                    <button type="button" class="btn btn-padding btn-light">I25</button>
                                    <button type="button" class="btn btn-padding btn-light">I26</button>
                                    <button type="button" class="btn btn-padding btn-light">I27</button>
                                    <button type="button" class="btn btn-padding btn-light">I28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">H21</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H22</button>
                                    <button type="button" class="btn btn-padding btn-vvip">H23</button>
                                    <button type="button" class="btn btn-padding btn-light">H24</button>
                                    <button type="button" class="btn btn-padding btn-light">H25</button>
                                    <button type="button" class="btn btn-padding btn-light">H26</button>
                                    <button type="button" class="btn btn-padding btn-light">H27</button>
                                    <button type="button" class="btn btn-padding btn-light">H28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">G21</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G22</button>
                                    <button type="button" class="btn btn-padding btn-vvip">G23</button>
                                    <button type="button" class="btn btn-padding btn-light">G24</button>
                                    <button type="button" class="btn btn-padding btn-light">G25</button>
                                    <button type="button" class="btn btn-padding btn-light">G26</button>
                                    <button type="button" class="btn btn-padding btn-light">G27</button>
                                    <button type="button" class="btn btn-padding btn-light">G28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">F21</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F22</button>
                                    <button type="button" class="btn btn-padding btn-vvip">F23</button>
                                    <button type="button" class="btn btn-padding btn-light">F24</button>
                                    <button type="button" class="btn btn-padding btn-light">F25</button>
                                    <button type="button" class="btn btn-padding btn-light">F26</button>
                                    <button type="button" class="btn btn-padding btn-light">F27</button>
                                    <button type="button" class="btn btn-padding btn-light">F28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">E19</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E20</button>
                                    <button type="button" class="btn btn-padding btn-vvip">E21</button>
                                    <button type="button" class="btn btn-padding btn-light">E22</button>
                                    <button type="button" class="btn btn-padding btn-light">E23</button>
                                    <button type="button" class="btn btn-padding btn-light">E24</button>
                                    <button type="button" class="btn btn-padding btn-light">E25</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">D19</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D20</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D21</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D22</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D23</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D24</button>
                                    <button type="button" class="btn btn-padding btn-vvip">D25</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip">C19</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C20</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C21</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C22</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C23</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C24</button>
                                    <button type="button" class="btn btn-padding btn-vvip">C25</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary">B19</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B20</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B21</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B22</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B23</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B24</button>
                                    <button type="button" class="btn btn-padding btn-secondary">B25</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary">A19</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A20</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A21</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A22</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A23</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A24</button>
                                    <button type="button" class="btn btn-padding btn-secondary">A25</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div class="d-flex" style="gap: 5px;">
                            <button type="button" class="btn btn-reguler">Reguler</button>
                            <button type="button" class="btn btn-light">VIP</button>
                            <button type="button" class="btn btn-vvip">VVIP</button>
                            <button type="button" class="btn btn-secondary">Undangan</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script src="{{ asset('assets/vendor/select2/js/select2.full.min.js') }}"></script>

        <script>
            $('.select2-elements').select2({
                theme: 'bootstrap-5'
            })
        </script>
    @endsection
