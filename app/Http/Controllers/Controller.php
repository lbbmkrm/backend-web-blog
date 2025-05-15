<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function successResponse(string $message, $data = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    protected function failedResponse(Exception $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage()
        ], $exception->getCode());
    }
}
