<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;

    public const ASSET_TOMAN = 'toman';
    public const ASSET_USDT = 'usdt';
    public const ASSET_STARS = 'stars';

    public const ASSETS = [
        self::ASSET_TOMAN,
        self::ASSET_USDT,
        self::ASSET_STARS,
    ];

    protected $fillable = [
        'user_id',
        'toman_balance',
        'usdt_balance',
        'stars_balance',
    ];

    protected function casts(): array
    {
        return [
            'toman_balance' => 'decimal:2',
            'usdt_balance' => 'decimal:8',
            'stars_balance' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBalanceByAsset(string $asset): string
    {
        return match ($asset) {
            self::ASSET_TOMAN => (string) $this->toman_balance,
            self::ASSET_USDT => (string) $this->usdt_balance,
            self::ASSET_STARS => number_format((int) $this->stars_balance, 8, '.', ''),
            default => throw new \InvalidArgumentException('دارایی نامعتبر است.'),
        };
    }
}
