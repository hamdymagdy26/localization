<?php

namespace App\Http\Requests\Abstracts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 *
 */
abstract class AbstractFormRequest extends FormRequest
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
     *
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response()->json(
                [
                    'data' => $validator->errors(),
                    'message' => 'Failed validation',
                    'success' => false
                ], 
                422
            )
        );
    }
}
