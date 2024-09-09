@extends('layouts/dashboard')

@section('title')
    Buat Jadwal Baru
@endsection

@section('page-style')
    <link href="{{ asset('assets/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Input Jadwal</h5>
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show m-2">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                        fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
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
            <form method="POST" action="{{ route('schedules.update', $schedule->id) }}">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="shiftName" class="form-label">Nama Jadwal</label>
                            <input type="text" name="name"
                                class="form-control {{ $errors->has('name') ? 'border border-danger' : '' }}"
                                value="{{ $schedule->name }}" id="shiftName" aria-describedby="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="text" name="date"
                                class="form-control {{ $errors->has('date') ? 'border border-danger' : '' }}"
                                value="{{ $schedule->date }}" id="date" aria-describedby="start">
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="kode" class="form-label">Event</label>
                            <select name="event_id"
                                class="form-control {{ $errors->has('event_id') ? 'border border-danger' : '' }}"
                                id="kode" aria-describedby="event_id">
                                <option value="">Pilih Event</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}"
                                        {{ $schedule->event->id === $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
    </script>

    <script>
        $('#date').bootstrapMaterialDatePicker({
            weekStart: 1,
            time: false,
            format: 'YYYY/MM/DD',
        });
    </script>
@endsection
