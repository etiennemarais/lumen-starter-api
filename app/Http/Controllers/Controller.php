<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    /**
     * @param $message
     * @return Response
     */
    protected function respondWithMissingField($message)
    {
        return response()->json([
            'status' => 400,
            'message' => $message,
        ], 400);
    }

    /**
     * @param $message
     * @return Response
     */
    private function respondWithValidationError($message)
    {
        return response()->json([
            'status' => 406,
            'message' => $message,
        ], 406);
    }

    /**
     * @param $validator
     * @return Response
     */
    protected function respondWithErrorMessage($validator)
    {
        $required = $messages = $digits = [];
        $validatorMessages = $validator->errors()->toArray();
        foreach($validatorMessages as $field => $message) {
            if (strpos($message[0], 'required')) {
                $required[] = $field;
            }

            if (strpos($message[0], 'digits')) {
                $digits[] = $field;
            }

            foreach ($message as $error) {
                $messages[] = $error;
            }
        }

        if (count($required) > 0) {
            $fields = implode(', ', $required);
            $message = "Missing required fields $fields";

            return $this->respondWithMissingField($message);
        }

        if (count($digits) > 0) {
            $fields = implode(', ', $digits);
            $message = "The $fields that you provided is invalid";

            return $this->respondWithValidationError($message);
        }

        return $this->respondWithValidationError(implode(', ', $messages));
    }
}
