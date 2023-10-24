<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use App\Enums\StatusClaim;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Enum;

class ClaimFormRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'category_id' => 'required|integer',
                    'currency_id' => 'required|integer',
                    'date' => 'required|date',
                    'amount' => 'required|string',
                    'description' => 'required|string',
                    'file_support.*' => 'required|mimes:jpg,jpeg,png,pdf,doc,docx,txt,tiff,xlsx'
                ];
                break;
            case 'PUT':
                return [
                    'category_id' => 'integer',
                    'review_user_id' => 'integer',
                    'currency_id' => 'integer',
                    'status' => ['string', new Enum(StatusClaim::class)],
                    'reason' => 'required_with:status',
                    'date' => 'date',
                    'amount' => 'string',
                    'description' => 'string',
                    'file_support.*' => 'mimes:jpg,jpeg,png,pdf,doc,docx,txt,tiff,xlsx'
                ];
                break;
            default:
                break;
        }

        
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
