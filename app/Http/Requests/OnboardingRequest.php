<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Http\JsonResponse;

class OnboardingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'string',
        ];
    }

    protected function errorResponse(): ?JsonResponse
    {
        $code = 422;
        return response()->json([
            'response_code' => $code,
            'response_status' => 'failed-validation',
            'message' => 'Error! The request not expected!',
            'errors' => $this->validator->errors()->messages()
        ], $code);
    }

}
