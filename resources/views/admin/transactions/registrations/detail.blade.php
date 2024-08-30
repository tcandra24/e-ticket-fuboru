@extends('layouts.dashboard')

@section('title')
    Detail Registrasi
@endsection

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

        .stage {
            height: 50px;
            background-color: #333;
            color: #fff;
            text-align: center;
            line-height: 50px;
            font-weight: bold;
        }

        .btn-light:disabled {
            background-color: #ffffff !important;
            border-color: #ffffff !important;
        }

        .btn-light {
            background-color: #c2c2c2 !important
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-block w-100 my-4">
                        <div class="d-flex justify-content-center">
                            <div class="d-flex flex-column">
                                <img src="{{ asset('/storage/qr-codes/qr-code-' . $registration->token . '.svg') }}"
                                    width="300" height="300" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="d-block w-100">
                        <div class="d-flex flex-row justify-content-center">
                            <div class="d-flex flex-column">
                                <div class="p-2">
                                    <h4>Nama Lengkap: </h4>
                                    <p>{{ $registration->fullname }}</p>
                                </div>
                                <div class="p-2">
                                    <h4>Nomer Registrasi: </h4>
                                    <p>{{ $registration->registration_number }}</p>
                                </div>
                                <div class="p-2">
                                    <h4>No HP: </h4>
                                    <p>{{ $registration->no_hp }}</p>
                                </div>
                                <div class="p-2">
                                    <h4>Jumlah: </h4>
                                    <p>{{ $registration->qty }}</p>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="p-2">
                                    <h4>Jadwal: </h4>
                                    <p>{{ $registration->schedule->name }}</p>
                                </div>
                                <div class="p-2">
                                    <h4>Grup Kursi: </h4>
                                    <p>{{ $registration->groupSeat->name }}</p>
                                </div>
                                <div class="p-2">
                                    <h4>Kursi: </h4>
                                    @foreach ($registration->seats as $seat)
                                        <span class="badge rounded-pill bg-primary">{{ $seat->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Kursi Penonton</h5>
                    <div class="row">
                        <form method="POST" action="{{ route('transaction.seat.store') }}">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ request()->event_id }}">
                            <input type="hidden" name="registration_number" value="{{ request()->registration_number }}">
                            <div class="row">
                                <div class="col-lg-6 d-flex align-items-stretch">
                                    <div class="mb-3 w-100">
                                        <label for="seats" class="form-label">Pilih Kursi</label>
                                        <select name="seats[]" class="form-control select2-elements" id="seats"
                                            aria-describedby="seats" multiple="multiple">
                                            <option value="">Pilih Kursi</option>
                                            @foreach ($seats as $seat)
                                                <option value="{{ $seat->id }}"
                                                    {{ in_array($seat->id, $registration->seats->pluck('id')->toArray()) ? 'disabled' : '' }}>
                                                    {{ $seat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('seats')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <div class="justify-content-between mt-3">
                            <div class="d-flex" style="gap: 5px;">
                                <button type="button" class="btn btn-reguler">Reguler</button>
                                <button type="button" class="btn btn-light">VIP</button>
                                <button type="button" class="btn btn-vvip">VVIP</button>
                                <button type="button" class="btn btn-secondary">Undangan</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex flex-row p-2 w-100 overflow-auto" style="gap: 40px;">
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
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X9</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X10</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W9</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V9</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U9</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T9</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S9</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R9</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q9</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q10</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P9</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O1</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O2</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O3</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O4</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O5</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O6</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O7</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O8</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O9</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N1</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N2</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N3</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N4</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N5</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N6</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N7</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N8</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N9 ', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N9</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M1</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M2</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M3</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M4</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M5</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M6</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M7</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M8</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M9</button>
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
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J1</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J2</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J3</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J4</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J5</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J6</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J7</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('78', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I1</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I2</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I3</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I4</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I5</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I6</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I7</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H1</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H2</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H3</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H4</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H5</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H6</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H7</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G1</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G2</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G3</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G4</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G5</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G6</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G7</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F1</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F2</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F3</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F4</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F5</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F6</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F7</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F8</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('E1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E1</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('E2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E2</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('E3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E3</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('E4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E4</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E5</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E6</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E7</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D1</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D2</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D3</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D4</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D5</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D6</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D7</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C1</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C2</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C3</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C4</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C5</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C6</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C7</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B1</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B2</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B3</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B4</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B5</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B6</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B7</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A1', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A1</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A2', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A2</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A3', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A3</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A4', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A4</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A5', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A5</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A6', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A6</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A7', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A7</button>
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
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X21</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X23</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W21</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W23</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V21</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U21</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T21</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S21</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R21</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q21</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q23</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P10</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P21</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O10</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O11</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O12</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O13</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O14</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O15</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O16</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O17</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O18</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O19</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O20</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O21</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N10</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N11</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N12</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N13</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N14</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N15</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N16</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N17</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N18</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N19</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N20</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N21</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M10</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M11</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M12</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M13</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M14</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M15</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M16</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M17</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M18</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M19</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M20</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M21</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L8</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L9</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L10</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L11</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L12</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L13</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L14</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L15</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L16</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L17</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L18</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L19</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K8</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K9</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K10</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K11</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K12</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K13</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K14</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K15</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K16</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K17</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K18</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K19</button>
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
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J9</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J10</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J11</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J12</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J13</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J14</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J15</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J16</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J17</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J18</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J19</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I9</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I10</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I11</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I12</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I13</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I14</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I15</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I16</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I17</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I18</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I19</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H9</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H10</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H11</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H12</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H13</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H14</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H15</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H16</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H17</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H18</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H19</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G9</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G10</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G11</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G12</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G13</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G14</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G15</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G16</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G17</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G18</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G19</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F9</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F10</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F11</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F12</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F13</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F14</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F15</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F16</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F17</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F18</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F19</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F20</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E8</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E9</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E10</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E11</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E12</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E13</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E14</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E15</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E16</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E17</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E18</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D8</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D19</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D10</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D11</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D12</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D13</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D14</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D15</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D16</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D17</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D18</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C8</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C9</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C10</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C11</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C12</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C13</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C14</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C15</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C16</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C17</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C18</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B8</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B9</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B10</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B11</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B12</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B13</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B14</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B15</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B16</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B17</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B18</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A8', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A8</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A9', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A9</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A10', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A10</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A11', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A11</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A12', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A12</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A13', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A13</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A14', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A14</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A15', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A15</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A16', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A16</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A17', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A17</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A18', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A18</button>
                                </div>

                                <div class="w-100 mt-5">
                                    <div class="stage">STAGE</div>
                                </div>
                            </div>
                            {{-- ===================== --}}
                            <div class="d-flex flex-column bd-highlight" style="gap: 5px;">
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y30</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y31', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y31</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y32', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y32</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Y33', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Y33</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X30</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X31', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X31</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X32', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X32</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('X33', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>X33</button>
                                </div>
                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W30</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W31', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W31</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W32', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W32</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('W33', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>W33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V30</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V31', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V31</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V32', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V32</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('V33', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>V33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U30</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U31', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U31</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U32', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U32</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('U33', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>U33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T30</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T31', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T31</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T32', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T32</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('T33', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>T33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S30</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S31', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S31</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S32', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S32</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('S33', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>S33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R30</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R31', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R31</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R32', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R32</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('R33', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>R33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-between" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q30</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q31', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q31</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q32', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q32</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('Q33', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>Q33</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P23</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('P30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>P30</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O22</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O23</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O24</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O25</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O26</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O27</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O28</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O29</button>
                                    <button type="button" class="btn btn-padding btn-reguler"
                                        {{ in_array('O30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>O30</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N22</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N28</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N29</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('N30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>N30</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M22</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M28</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M29</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('M30', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>M30</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L20</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L21</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L22</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('L28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>L28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K20</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K21</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K22</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('K28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>K28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('JA21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>JA21</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('JA22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>JA22</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('JA23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>JA23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('JA24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>JA24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('JA25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>JA25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('JA26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>JA26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('JA27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>JA27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('JA28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>JA28</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('JA29', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>JA29</button>
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
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J21</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J22</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('J23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('J28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>J28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I21</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I22</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('I23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('I28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>I28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H21</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H22</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('H23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('H28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>H28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G21</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G22</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('G23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('G28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>G28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F21</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F22</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('F23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F25</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F26', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F26</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F27', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F27</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('F28', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>F28</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E19</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E20</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('E21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E21</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('E22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E22</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('E23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E23</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('E24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E24</button>
                                    <button type="button" class="btn btn-padding btn-light"
                                        {{ in_array('E25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>E25</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D19</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D20</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D21</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D22</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D23</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D24</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('D25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>D25</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C19</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C20</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C21</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C22</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C23</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C24</button>
                                    <button type="button" class="btn btn-padding btn-vvip"
                                        {{ in_array('C25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>C25</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B19</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B20</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B21</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B22</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B23</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B24</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('B25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>B25</button>
                                </div>

                                <div class="d-flex flex-row bd-highlight justify-content-evenly" style="gap: 5px;">
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A19', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A19</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A20', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A20</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A21', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A21</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A22', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A22</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A23', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A23</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A24', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A24</button>
                                    <button type="button" class="btn btn-padding btn-secondary"
                                        {{ in_array('A25', $seatAlreadyBook->pluck('name')->toArray()) ? 'disabled' : '' }}>A25</button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/lightbox/js/lightbox.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/select2/js/select2.full.min.js') }}"></script>

    <script>
        const limitSeat = '{{ $registration->qty }}'
        $('.select2-elements').select2({
            theme: 'bootstrap-5',
            maximumSelectionLength: limitSeat,
        })
    </script>
@endsection
