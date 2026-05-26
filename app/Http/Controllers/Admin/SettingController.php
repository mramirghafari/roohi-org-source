<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\DefaultCommissionRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public const REFERRAL_JOIN_CONDITION_KEY = 'referral_join_condition';

    public function index()
    {
        $referralJoinCondition = AppSetting::getValue(self::REFERRAL_JOIN_CONDITION_KEY, 'vip_active');
        $defaultRules = DefaultCommissionRule::query()->orderBy('event')->orderBy('level')->get();

        return view('dashboard.settings.index', compact('referralJoinCondition', 'defaultRules'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'referral_join_condition' => 'required|in:register,vip_active',
            'rules' => 'nullable|array',
            'rules.*.event' => 'nullable|in:referral_register,referral_vip_purchase',
            'rules.*.level' => 'nullable|integer|min:1|max:100',
            'rules.*.stars_reward' => 'nullable|numeric|min:0',
            'rules.*.toman_reward' => 'nullable|numeric|min:0',
            'rules.*.usdt_reward' => 'nullable|numeric|min:0',
            'rules.*.commission_percent' => 'nullable|numeric|min:0|max:100',
            'rules.*.is_active' => 'nullable|in:0,1',
        ]);

        $preparedRules = $this->prepareRules($validated['rules'] ?? []);

        DB::transaction(function () use ($validated, $preparedRules) {
            AppSetting::setValue(self::REFERRAL_JOIN_CONDITION_KEY, $validated['referral_join_condition']);

            DefaultCommissionRule::query()->delete();
            if (!empty($preparedRules)) {
                DefaultCommissionRule::query()->insert(array_values($preparedRules));
            }
        });

        return redirect()->route('settings.index')->with('success', 'تنظیمات با موفقیت ذخیره شد.');
    }

    private function prepareRules(array $rows): array
    {
        $prepared = [];

        foreach ($rows as $row) {
            $event = $row['event'] ?? null;
            $level = isset($row['level']) ? (int) $row['level'] : 0;

            if (!$event || $level < 1) {
                continue;
            }

            $key = $event . ':' . $level;
            $prepared[$key] = [
                'event' => $event,
                'level' => $level,
                'stars_reward' => (float) ($row['stars_reward'] ?? 0),
                'toman_reward' => (float) ($row['toman_reward'] ?? 0),
                'usdt_reward' => (float) ($row['usdt_reward'] ?? 0),
                'commission_percent' => (float) ($row['commission_percent'] ?? 0),
                'is_active' => ((int) ($row['is_active'] ?? 1)) === 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $prepared;
    }
}
