<body>
    <div class="page-wrapper" id="main-wrapper">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100">
            <div class="d-flex justify-content-center w-100 my-3">
                <div class="row justify-content-center w-100">
                    <div class="col-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="d-block w-100 my-2">
                                            <div class="d-flex justify-content-center">
                                                <div class="d-flex flex-column">
                                                    <img src="{{ asset('/storage/qr-codes/qr-code-' . $token . '.svg') }}"
                                                        width="300" height="300" alt="">
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
                </div>
            </div>
        </div>
    </div>
</body>
