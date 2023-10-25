<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ReturnResponser
{
    /**
     * Return a success JSON response.
     *
     * @param  string  $response
     * @param  $data
     * @param  $redirect
     * @param  string|null  $message
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($response, $data = null, $redirect = null, string $message = null, int $code = null): JsonResponse
    {
        if ($response == "created") {
            $response_status = 'successfully-' . $response;
            $message = "Data successfully created!";
            $code = 201;
        }
        elseif ($response == "updated") {
            $response_status = 'successfully-' . $response;
            $message = "Data successfully updated!";
            $code = 201;
        }
        elseif ($response == "deleted") {
            $response_status = 'successfully-' . $response;
            $message = "Data successfully deleted!";
            $code = 200;
        }
        elseif ($response == "uploaded") {
            $response_status = 'successfully-' . $response;
            $message = "Data successfully uploaded!";
            $code = 200;
        }
        elseif ($response == "downloaded") {
            $response_status = 'successfully-' . $response;
            $message = "Data successfully downloaded!";
            $code = 200;
        }
        elseif ($response == "searched") {
            $response_status = 'successfully-' . $response;
            $message = "Data successfully searched!";
            $code = 200;
        }
        elseif ($response == "get") {
            $response_status = 'successfully-' . $response;
            $message = "Data successfully get!";
            $code = 200;
        }
        else {
            $response_status = 'successfully-' . $response;
            $code = 200;
        }

        $return = [
            'response_code' => $code,
            'response_status' => $response_status,
            'message' => $message,
        ];
        
        if ($data) {
            $return['data'] = $data;
        }
        
        if ($redirect) {
            $return['redirect'] = $redirect;
        }

        return response()->json($return, $code);

    }

    /**
     * Return an error Validator JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
	protected function errorvalidator($errors = null, $message = null,int $code = 422): JsonResponse
	{
        if (!$message) {
            $message = 'Error! The request not expected!';
        }

        $dataresponse = [
            'response_code' => $code,
            'response_status' => 'failed-validation',
            'message' => $message,
            'errors' => $errors
        ];

        return response()->json($dataresponse, $code);
        
	}

    /**
     * Return an error Code JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
	protected function errorServer($errors, int $code = 400, $message = null) : JsonResponse
	{
        if (!$message) {
            $message = "Internal Server Error!";
        }
        return response()->json([
            'response_code' => $code,
            'response_status' => 'failed-server',
            'message' => $message,
            'errors' => $errors
        ], $code);
	}

}
