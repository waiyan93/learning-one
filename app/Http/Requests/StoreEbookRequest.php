<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEbookRequest extends FormRequest
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
            'pdf' => 'required|mimes:pdf|max:100000'
        ];
    }

    public function messages()
    {
        return [
            'pdf.required' => 'You must choose a PDF',
            'pdf.mimes' => 'File must be in PDF Format',
            'pdf.max' => 'File size is larger than permitted'
        ];
    }
}
