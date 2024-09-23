<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BulkStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user !== null && $user->tokenCan('invoice:create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*.customerId' => ['required', 'integer'],
            '*.status' => ['required', Rule::in(['B', 'P', 'V', 'b', 'p', 'v'])],
            '*.amount' => ['required', 'numeric'],
            '*.billedDate' => ['required', 'date_format:Y-m-d H:i:s'],
            '*.paidDate' => ['required', 'date_format:Y-m-d H:i:s', 'nullable'],
        ];
    }

    protected function prepareForValidation(){
       $data = [];

       foreach($this->toArray() as $obj){
        $obj['customer_id'] = $obj['customerId'] ?? null;
        $obj['billed_date'] = $obj['billedDate'] ?? null;
        $obj['paid_date'] = $obj['paidDate'] ?? null;

        $date = $obj;
       }
       
       $this->merge($data);
    }
}

