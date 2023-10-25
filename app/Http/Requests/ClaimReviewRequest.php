<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use App\Enums\StatusClaim;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Enum;

class ClaimReviewRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => ['required','string', new Enum(StatusClaim::class)],
            'reason' => 'required',
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
