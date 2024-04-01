<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftPurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'memo_to_name' => 'required',
            'gift_supplier_id' => 'required|exists:gift_suppliers,id',
            'gift_purchases.*.gift_id' => 'required|exists:gifts,id',
            'gift_purchases.*.count' => 'required|integer',
            //'gift_purchases.*.unit_price' => 'required|numeric',
            
        ];
    }
}
