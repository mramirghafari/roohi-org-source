<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\RemoteEntryToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LbankAccessController extends Controller
{
    public function issue(Request $request)
    {
        // این مسیر را پشت auth پنل بگذار
        $request->validate([
            'ttl' => 'nullable|integer|min:60|max:1800', // 1 تا 30 دقیقه
        ]);

        $ttl = (int) ($request->input('ttl', 300)); // پیشفرض 5 دقیقه

        $row = RemoteEntryToken::create([
            'user_id'    => auth()->id(),
            'token'      => Str::random(48),
            'scope'      => 'lbank',
            'expires_at' => now()->addSeconds($ttl),
            'issued_ip'  => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        $url = "https://lbank.roohitrade.ir/access/{$row->token}";

        return response()->json([
            'ok' => true,
            'url' => $url,
            'expires_at' => $row->expires_at->toIso8601String(),
        ]);
    }
}
