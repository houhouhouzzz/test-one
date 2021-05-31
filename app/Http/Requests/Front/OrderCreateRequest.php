<?php

namespace App\Http\Requests\Front;

use App\Model\Order;
use App\Model\Product;
use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
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
        return[
            'sku_id' => 'required|exists:skus,id',
            'quantity' => 'required|int|min:1',
            'currency_code' => 'required|string|in:' . join(',', array_column(Product::PRICE_COLUMNS, 'currency_code')),

            'customer_name' => 'required|string',
            'customer_phone' => 'required|int',
            'whats_app' => 'nullable|string',

            'country_code' => 'required|string|in:' . join(',', array_keys(Order::ORDER_COUNTRIES)),
            'address' => 'required|string',

        ];
    }

    public function messages()
    {
        return [
            'sku_id.required' => 'MUST SELECT PRODUCT',
            'sku_id.exists' => 'THE PRODUCT NOT FOUND',
            'quantity.required' => 'QUANTITY IS MUST CHOOSE',
            'quantity.int' => 'QUANTITY IS NOT A NUMBER',
            'quantity.min' => 'QUANTITY NUMBER IS ERROR',
            'currency_code.required' => 'CURRENCY CODE NOT FOUND',
            'currency_code.in' => 'CURRENCY CODE IS INVALID',

            'customer_name.required' => 'FULL NAME IS REQUIRED',
            'customer_name.string' => 'FULL NAME IS INVALID',
            'customer_phone.required' => 'PHONE NUMBER IS REQUIRED',
            'customer_phone.int' => 'PHONE NUMBER IS INVALID',
            'whats_app.string' => 'WHATS APP IS INVALID',

            'country_code' => 'required|string|in:' . join(',', array_keys(Order::ORDER_COUNTRIES)),
            'address' => 'required|string',

            'country_code.required' => 'COUNTRY CODE NOT FOUND',
            'country_code.in' => 'COUNTRY CODE IS INVALID',
            'address.required' => 'COUNTRY CODE NOT FOUN',
            'address.string' => 'COUNTRY CODE IS INVALID',
        ];
    }
}
