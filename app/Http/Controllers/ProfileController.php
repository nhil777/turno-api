<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success([
            'balance' => $user->balance,
            'totalIncome' => $user->totalIncome(),
            'totalExpense' => $user->totalExpense(),
            'lastTransactions' => $user->lastTransactions(10),
        ]);
    }
}
