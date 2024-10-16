<div class="title" style="padding-bottom: 13px">
    <div style="text-align: center;text-transform: uppercase;font-size: 15px">
        Fuboru Registrasi
    </div>
</div>
<table style="width: 100%">
    <thead>
        <tr style="background-color: #e6e6e7;">
            <th scope="col">No</th>

            @foreach ($fields as $field)
                <th scope="col">{{ $field['label'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($registrations as $registration)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @foreach ($fields as $field)
                    <td class="border-bottom-0 pb-0">
                        @if ($field['model_path'] !== null)
                            @if ($field['is_multiple'])
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    @foreach ($registration->{$field['relation_method_name']} as $value)
                                        <span class="badge bg-success rounded-3 fw-semibold">
                                            {{ $value->name }},
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="mb-0 fw-normal">
                                    {{ $registration->{$field['relation_method_name']}->name }}
                                </p>
                            @endif
                        @else
                            <p class="mb-0 fw-normal">
                                {{ $registration->{$field['name']} }}
                            </p>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
