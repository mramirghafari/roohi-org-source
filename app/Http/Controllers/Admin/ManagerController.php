<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManagerController extends Controller
{
    public function __construct(private AuditLogger $auditLogger)
    {
    }

    public function index(): View
    {
        $roles = UserRole::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $managers = User::query()
            ->with('roles')
            ->where(function ($query) {
                $query->where('isAdmin', 1)
                    ->orWhere('is_support', 1)
                    ->orWhereHas('roles');
            })
            ->orderByDesc('id')
            ->get();

        $users = User::query()
            ->select('id', 'nam', 'mobile', 'isAdmin', 'is_support')
            ->orderByDesc('id')
            ->limit(3000)
            ->get();

        return view('dashboard.managers.index', compact('roles', 'managers', 'users'));
    }

    public function assign(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['integer', 'exists:user_roles,id'],
            'is_admin' => ['nullable', 'boolean'],
            'is_support' => ['nullable', 'boolean'],
        ]);

        $user = User::query()->with('roles')->findOrFail($validated['user_id']);
        $before = $this->managerSnapshot($user);
        $syncData = [];

        foreach (array_values(array_unique(array_map('intval', $validated['role_ids'] ?? []))) as $roleId) {
            $syncData[$roleId] = [
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $user->roles()->sync($syncData);
        $user->isAdmin = $request->boolean('is_admin') ? 1 : 0;
        $user->is_support = $request->boolean('is_support') ? 1 : 0;
        $user->save();
        $user->load('roles');

        $this->auditLogger->logAction(
            'manager.roles_assigned',
            $user,
            ['before' => $before, 'after' => $this->managerSnapshot($user)],
            [
                'section' => 'managers',
                'assigned_role_ids' => array_keys($syncData),
            ],
            null,
            $request,
            'role_assignment'
        );

        return redirect()->route('managers.index')->with('success', 'نقش‌های کاربر با موفقیت ذخیره شد.');
    }

    private function managerSnapshot(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->nam ?: $user->name,
            'mobile' => $user->mobile,
            'is_admin' => (bool) $user->isAdmin,
            'is_support' => (bool) $user->is_support,
            'roles' => $user->roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ])->values()->all(),
        ];
    }
}