<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class SalesTeamDashboardController extends Controller
{
    public function index(): View
    {
        $manager = auth()->user();
        $isFullSalesScope = $this->canSeeAllSalesUsers($manager);
        $assignedGroups = $this->groupsQuery($manager, $isFullSalesScope)
            ->withCount([
                'members',
                'members as active_members_count' => function (Builder $query) {
                    $query->whereHas('subscribes', function (Builder $subscribeQuery) {
                        $subscribeQuery->where('status', 1)
                            ->where('vip', '>', 0)
                            ->whereNotNull('start_vip')
                            ->whereNotNull('exp_vip')
                            ->where('start_vip', '<=', now())
                            ->where('exp_vip', '>=', now());
                    });
                },
            ])
            ->orderBy('name')
            ->get();

        $assignedUserQuery = $this->assignedUsersQuery($manager, $isFullSalesScope);

        $groupsCount = $assignedGroups->count();
        $usersCount = (clone $assignedUserQuery)->count();
        $activeUsersCount = (clone $assignedUserQuery)->withActiveVip()->count();
        $expiredUsersCount = (clone $assignedUserQuery)->withExpiredVip()->count();
        $recentUsers = (clone $assignedUserQuery)
            ->with('userGroups:id,name')
            ->latest('id')
            ->limit(12)
            ->get();

        return view('dashboard.sales-team.dashboard', compact(
            'manager',
            'assignedGroups',
            'groupsCount',
            'usersCount',
            'activeUsersCount',
            'expiredUsersCount',
            'recentUsers',
            'isFullSalesScope'
        ));
    }

    private function groupsQuery(User $manager, bool $isFullSalesScope): Builder
    {
        if ($isFullSalesScope) {
            return UserGroup::query()->where('is_active', true);
        }

        return $manager->supportGroups()->getQuery();
    }

    private function assignedUsersQuery(User $manager, bool $isFullSalesScope): Builder
    {
        if ($isFullSalesScope) {
            return User::query();
        }

        $managerId = (int) $manager->id;

        return User::query()
            ->whereHas('userGroups.supportAccounts', function (Builder $query) use ($managerId) {
                $query->where('users.id', $managerId);
            })
            ->distinct('users.id');
    }

    private function canSeeAllSalesUsers(User $user): bool
    {
        return (int) $user->isAdmin === 1
            || $user->hasRole('sales-manager')
            || $user->hasRole('sales-expert');
    }
}