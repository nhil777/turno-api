<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public function success(mixed $data, int $httpCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
        ], $httpCode);
    }

    public function error(string $errorMessage, array $errors = [], int $httpCode = 500): JsonResponse
    {
        return response()->json([
            'message' => $errorMessage,
            'errors' => $errors,
        ], $httpCode);
    }
}
