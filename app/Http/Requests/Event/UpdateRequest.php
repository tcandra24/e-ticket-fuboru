<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'fields' => 'required',
            'model_path' => 'required',
            'date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'description.required' => 'Keterangan wajib diisi',
            'fields.required' => 'Form Field harus diisi',
            'model_path.required' => 'Model harus diisi',
            'date.required' => 'Tanggal harus diisi',
        ];
    }
}
