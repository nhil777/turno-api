<?php

namespace App\Http\Controllers;

use App\Enums\DepositStatusEnum;
use App\Events\DepositStatusUpdated;
use App\Models\Deposit;
use App\Services\DepositService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;

class DepositController extends BaseController
{
    const MAX_IMAGE_FILE_SIZE = 1024 * 5; // 5MB

    public function __construct(
        private readonly DepositService $depositService,
        private readonly UserService $userService
    )
    {
        $this->middleware('auth:api');
    }

    public function view(Deposit $deposit): JsonResponse
    {
        if (! Gate::allows('view', $deposit)) {
            throw new UnauthorizedException();
        }

        $deposit->load('user');

        return $this->success($deposit);
    }

    public function index(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isAdmin()) {
            $deposits = $this->depositService->all();
        } else {
            $deposits = $this->userService->deposits($user->id);
        }

        return $this->success($deposits);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|integer',
            'image' => 'required|mimes:jpeg,png,jpg|max:'.self::MAX_IMAGE_FILE_SIZE
        ]);

        $user = auth()->user();

        $imagePath = $request->file('image')->store('deposits', 'public');

        $deposit = $this->depositService->create([
            'image' => url('storage') . "/$imagePath",
            'amount' => (int) $request->amount,
            'status' => DepositStatusEnum::WAITING_APPROVAL,
            'user_id' => $user->id,
        ]);
        $deposit->save();

        return $this->success($deposit, 201);
    }

    public function approve(Deposit $deposit): JsonResponse
    {
        if (! Gate::allows('approve', Deposit::class)) {
            throw new UnauthorizedException();
        }

        $deposit = $this->depositService->approve($deposit->id);

        event(new DepositStatusUpdated($deposit));

        return $this->success(null);
    }

    public function reject(Deposit $deposit): JsonResponse
    {
        if (! Gate::allows('approve', Deposit::class)) {
            throw new UnauthorizedException();
        }

        $deposit = $this->depositService->reject($deposit->id);

        event(new DepositStatusUpdated($deposit));

        return $this->success(null);
    }
}
