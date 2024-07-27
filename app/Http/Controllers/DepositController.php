<?php

namespace App\Http\Controllers;

use App\Enums\DepositStatusEnum;
use App\Models\Deposit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepositController extends BaseController
{
    const MAX_IMAGE_FILE_SIZE = 1024 * 5; // 5MB

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|integer',
            'image' => 'required|mimes:jpeg,png,jpg|max:'.self::MAX_IMAGE_FILE_SIZE
        ]);

        $imagePath = $request->file('image')->store('deposits', 'public');

        $deposit = new Deposit([
            'image' => url('storage') . "/$imagePath",
            'amount' => (int) $request->amount,
            'status' => DepositStatusEnum::WAITING_APPROVAL,
            'user_id' => auth()->id(),
        ]);
        $deposit->save();

        return $this->success($deposit, 201);
    }
}
