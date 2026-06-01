<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class RemoteAccessToken extends Model
{
    protected $fillable = [
        'user_id',
        'service',
        'token',
        'username',
        'password',
        'is_enabled',
        'meta',
        'expires_at',
        'used_at',
        'used_ip',
        'used_ua',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_enabled' => 'boolean',
        'password' => 'encrypted',
        'meta' => 'array',
    ];

    public function passwordForForm(?string $fallback = null): ?string
    {
        $rawPassword = $this->getRawOriginal('password');

        if ($rawPassword === null || $rawPassword === '') {
            return $fallback;
        }

        if (!$this->looksEncrypted($rawPassword)) {
            return $rawPassword;
        }

        try {
            return decrypt($rawPassword);
        } catch (Throwable) {
            return $fallback;
        }
    }

    private function looksEncrypted(string $value): bool
    {
        $decoded = json_decode(base64_decode($value, true) ?: '', true);

        return is_array($decoded)
            && array_key_exists('iv', $decoded)
            && array_key_exists('value', $decoded)
            && array_key_exists('mac', $decoded);
    }

    public function consume(): void
    {
        $this->forceFill([
            'used_at' => now(),
            'used_ip' => request()->ip(),
            'used_ua' => substr((string) request()->userAgent(), 0, 255),
        ])->save();
    }

    public function scopeValid($q)
    {
        return $q
            ->where('is_enabled', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instances()
    {
        return $this->hasMany(RemoteInstance::class, 'remote_access_token_id');
    }
}
