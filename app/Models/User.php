<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'balance',
        'is_admin',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'integer',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function hasEnoughFunds(int $amount): bool
    {
        return $this->balance >= $amount;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function totalIncome(): int
    {
        return $this->deposits()->sum('amount');
    }

    public function totalExpense(): int
    {
        return $this->orders()->sum('amount');
    }

    public function lastTransactions(int $max = 10)
    {
        $transactions = $this->deposits()
            ->select('id', 'amount', 'created_at as transaction_date')
            ->get()
            ->map(function ($transaction) {
                $transaction['type'] = 'income';
                return $transaction;
            })
            ->merge(
                $this->orders()->select('id', 'amount', 'created_at as transaction_date')
                    ->get()
                    ->map(function ($transaction) {
                        $transaction['type'] = 'expense';
                        return $transaction;
                    })
            )
            ->sortByDesc('transaction_date')
            ->take($max)
            ->values()
        ;

        return $transactions;
    }
}
