<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $orders = $user->orders()->orderBy('id', 'DESC')->simplePaginate(10);

        return $this->success($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric'],
            'description' => ['required','string','max:255'],
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (! $user->hasEnoughFunds($request->amount)) {
            return $this->error(errorMessage: trans('en.insufficient_funds'), httpCode: 402);
        }

        try {
            DB::beginTransaction();

            $order = new Order([
                'amount' => $request->amount,
                'description' => $request->description,
                'user_id' => $user->id,
            ]);
            $order->save();

            $user->balance -= $order->amount;
            $user->save();

            DB::commit();

            return $this->success($order);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('OrderController@store: Failed to create order: '. $e->getMessage());

            return $this->error(errorMessage: 'An error occurred while processing your request', httpCode: 500);
        }
    }
}
