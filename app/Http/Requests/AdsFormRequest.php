<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdsFormRequest extends FormRequest
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
            'ad_images' => 'nullable|array|max:12',
            'ad_images.*' => 'image|mimes:png,jpg,jpeg,webp|max:5120',
            'feature_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'first_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'second_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'name' => 'required|min:3|max:120',
            'description' => 'required|min:3',
            'price' => "required|regex:/^\d+(\.\d{1,2})?$/",
            'price_status' => 'required',
            'category_id' => 'required',
            'product_condition' => 'required',
            'country_id' => 'required',
        ];
    }
}
