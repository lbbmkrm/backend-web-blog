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
    protected function failedResponse(Exception $e): JsonResponse
    {
        $code = $e->getCode();
        if ($code < 100 || $code >= 600) {
            $code = 500;
        }
        return response()->json([
            'message' => $e->getMessage()
        ], $code);
    }
}
