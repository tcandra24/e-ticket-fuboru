@extends('layouts/dashboard')

@section('title')
    Edit Event
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2-bootstrap-5-theme.min.css') }}" />
    <style>
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            box-shadow: unset !important;
        }
    </style>

    <link href="{{ asset('assets/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Edit Event</h5>
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
            <form method="POST" action="{{ route('events.update', $event->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="name" class="form-label">Nama Event</label>
                            <input type="text" name="name"
                                class="form-control {{ $errors->has('name') ? 'border border-danger' : '' }}" id="name"
                                value="{{ $event->name }}" aria-describedby="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="description" class="form-label">Keterangan</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="10">{{ $event->description }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="text" name="date"
                                class="form-control {{ $errors->has('date') ? 'border border-danger' : '' }}"
                                value="{{ \Carbon\Carbon::parse($event->date)->format('Y/m/d') }}" id="date"
                                aria-describedby="date">
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="link" class="form-label">Link</label>
                            <input type="text" name="link"
                                class="form-control {{ $errors->has('link') ? 'border border-danger' : '' }}"
                                id="link" value="{{ $event->link }}" aria-describedby="link">
                            @error('link')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="image" class="form-label">Gambar</label>
                            <input class="form-control" name="image" type="file" aria-describedby="image">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <div class="form-check">
                                <input class="form-check-input" name="is_active" type="checkbox" id="active"
                                    aria-describedby="term-condition" {{ $event->is_active ? 'checked' : '' }}>
                                <label for="term-condition" class="form-label">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="fields" class="form-label">Field</label>
                            <select name="fields[]"
                                class="form-control {{ $errors->has('fields') ? 'border border-danger' : '' }}"
                                id="fields" aria-describedby="roles" multiple>
                                <option value="">Pilih Field</option>
                                @foreach ($formFields as $formField)
                                    <option value="{{ $formField->id }}"
                                        {{ in_array($formField->id, $event->forms()->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $formField->name }} - {{ $formField->type }}
                                        ({{ $formField->model_path ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('fields')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="mb-3 w-100">
                            <label for="fields" class="form-label">Model</label>
                            <input class="form-control" name="model_path" type="text" aria-describedby="model_path"
                                placeholder="App\Models\NamaModel" value="{{ $event->model_path }}">
                            @error('model_path')
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
    <script src="{{ asset('assets/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
    </script>

    <script>
        $('#fields').select2({
            theme: 'bootstrap-5'
        })

        $('#date').bootstrapMaterialDatePicker({
            weekStart: 1,
            time: false,
            format: 'YYYY/MM/DD',
        });
    </script>
@endsection
