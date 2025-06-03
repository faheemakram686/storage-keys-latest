<?php

namespace App\Helpers;

class ResponseClass
{
    public static function success($data = [], $message = 'Success', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error($message = 'Error', $statusCode = 400, $errors = [])
    {
        return response()->json([
            'success' => false,
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

}