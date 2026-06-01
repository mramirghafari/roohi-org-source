<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserRoleController extends Controller
{
    public function __construct(private AuditLogger $auditLogger)
    {
    }

    public const PERMISSIONS = [
        'users.view_assigned' => 'مشاهده کاربران اختصاص داده‌شده',
        'sales.manage' => 'مدیریت فروش',
        'sales.follow_up' => 'پیگیری فروش',
        'marketing.follow_up' => 'پیگیری بازاریابی',
        'team.supervise' => 'سرپرستی تیم',
        'user_groups.assign_marketers' => 'اختصاص گروه‌های کاربری به بازاریاب‌ها',
        'tickets.manage' => 'مدیریت تیکت‌ها',
        'support.assign' => 'اختصاص مسئول پشتیبانی',
    ];

    public function index(): View
    {
        $roles = UserRole::query()
            ->withCount('users')
            ->orderByDesc('id')
            ->get();

        $permissions = self::PERMISSIONS;

        return view('dashboard.user-roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $role = UserRole::query()->create($this->validatedData($request));

        $this->auditLogger->logAction(
            'user_role.created',
            $role,
            ['after' => $this->roleSnapshot($role)],
            ['section' => 'user_roles'],
            null,
            $request,
            'role_management'
        );

        return redirect()->route('user-roles.index')->with('success', 'نقش جدید با موفقیت ایجاد شد.');
    }

    public function update(Request $request, UserRole $role): RedirectResponse
    {
        $before = $this->roleSnapshot($role);

        $role->update($this->validatedData($request, $role));
        $role->refresh();

        $this->auditLogger->logAction(
            'user_role.updated',
            $role,
            ['before' => $before, 'after' => $this->roleSnapshot($role)],
            ['section' => 'user_roles'],
            null,
            $request,
            'role_management'
        );

        return redirect()->route('user-roles.index')->with('success', 'نقش با موفقیت بروزرسانی شد.');
    }

    public function destroy(UserRole $role): RedirectResponse
    {
        $this->auditLogger->logAction(
            'user_role.deleted',
            $role,
            ['before' => $this->roleSnapshot($role)],
            ['section' => 'user_roles'],
            null,
            request(),
            'role_management'
        );

        $role->delete();

        return redirect()->route('user-roles.index')->with('success', 'نقش حذف شد.');
    }

    private function validatedData(Request $request, ?UserRole $role = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'unique:user_roles,slug,' . ($role?->id ?? 'NULL')],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:' . implode(',', array_keys(self::PERMISSIONS))],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'عنوان نقش الزامی است.',
            'slug.unique' => 'شناسه این نقش قبلا ثبت شده است.',
        ]);

        $slug = trim((string) ($validated['slug'] ?? ''));
        if ($slug === '') {
            $slug = Str::slug($validated['name']) ?: 'role-' . now()->format('His');
        }

        return [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'permissions' => array_values($validated['permissions'] ?? []),
            'is_active' => $request->boolean('is_active'),
            'created_by' => $role?->created_by ?? auth()->id(),
        ];
    }

    private function roleSnapshot(UserRole $role): array
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'slug' => $role->slug,
            'description' => $role->description,
            'permissions' => $role->permissions ?? [],
            'is_active' => (bool) $role->is_active,
        ];
    }
}