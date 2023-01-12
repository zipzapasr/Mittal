<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CementInRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date' => 'required',
            'bags' => 'required',
            'from_site_id' => 'required',
            'to_site_id' => 'required',
            'remark' => 'required'
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'from_site_id' => $this->from_site,
            'to_site_id' => $this->to_site
        ]);
    }
}
