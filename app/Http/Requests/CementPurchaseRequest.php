<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CementPurchaseRequest extends FormRequest
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
    public static function rules()
    {
        return [
            'date' => 'required',
            'bags' => 'required',
            'supplier_id' => 'required',
            'site_id' => 'required',
            'remark' => 'required'
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'site_id' => $this->site,
            'supplier_id' => $this->supplier
        ]);
    }


}
