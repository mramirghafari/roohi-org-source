<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::query()->with('user')->latest('occurred_at');

        if ($request->filled('event')) {
            $query->where('event', $request->string('event'));
        }

        if ($request->filled('area')) {
            $query->where('area', $request->string('area'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $query->where(function ($builder) use ($search) {
                $builder->where('path', 'like', '%' . $search . '%')
                    ->orWhere('route_name', 'like', '%' . $search . '%')
                    ->orWhere('action', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('nam', 'like', '%' . $search . '%')
                            ->orWhere('name', 'like', '%' . $search . '%')
                            ->orWhere('mobile', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $logs = $query->paginate(50)->withQueryString();

        $events = AuditLog::query()->select('event')->distinct()->orderBy('event')->pluck('event');
        $actions = AuditLog::query()->whereNotNull('action')->select('action')->distinct()->orderBy('action')->pluck('action');
        $areas = AuditLog::query()->whereNotNull('area')->select('area')->distinct()->orderBy('area')->pluck('area');
        $users = User::query()
            ->whereIn('id', AuditLog::query()->whereNotNull('user_id')->select('user_id')->distinct())
            ->orderByDesc('id')
            ->limit(1000)
            ->get(['id', 'nam', 'name', 'mobile']);

        return view('dashboard.audit-logs.index', compact('logs', 'events', 'actions', 'areas', 'users'));
    }
}