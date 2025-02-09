<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCarRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {

    }

    /**
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name'  => 'required|string|max:100',
            'brand_id'   => 'required|integer|exists:car_brands,id',
            'model_id'   => [
                'required',
                'integer',
                Rule::exists('car_models', 'id')->where(function ($query) {
                    return $query->where('car_brand_id', request()->brand_id);
                }),
            ],
        ];
    }
}
