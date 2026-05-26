<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class SupportAccessController extends Controller
{
    public function index()
    {
        $users = User::query()->orderByDesc('id')->paginate(30);
        $departments = Ticket::DEPARTMENTS;

        return view('dashboard.tickets.support_access', compact('users', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_support' => 'nullable|boolean',
            'departments' => 'nullable|array',
            'departments.*' => 'in:' . implode(',', array_keys(Ticket::DEPARTMENTS)),
        ]);

        $isSupport = (bool) ($validated['is_support'] ?? false);

        $user->update([
            'is_support' => $isSupport ? 1 : 0,
        ]);

        $user->supportDepartments()->delete();

        if ($isSupport) {
            foreach (($validated['departments'] ?? []) as $department) {
                $user->supportDepartments()->create([
                    'department' => $department,
                ]);
            }
        }

        return redirect()->back()->with('success', 'دسترسی پشتیبانی کاربر بروزرسانی شد.');
    }
}
