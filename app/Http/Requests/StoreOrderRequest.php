<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            //
            'customer_id' => 'required|integer',    
            "product_ids"    => "required|array|min:2",
            "qties"    => "required|array|min:2", 
            "prices"    => "required|array|min:2", 
            "product_ids.*"    => "required|integer",
            "qties.*"    => "required|integer", 
            "prices.*"    => "required|integer", 
        ];
    }
}
