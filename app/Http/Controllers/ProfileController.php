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
            'total_income' => $user->totalIncome(),
            'total_expense' => $user->totalExpense(),
            'last_transactions' => $user->lastTransactions(10),
        ]);
    }
}
