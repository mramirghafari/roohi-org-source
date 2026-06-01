<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('dashboard.subscription-plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('dashboard.subscription-plans.create', [
            'plan' => new SubscriptionPlan([
                'duration_months' => 1,
                'gateway_enabled' => true,
                'card_to_card_enabled' => false,
                'receipt_required' => true,
                'payer_card_required' => true,
                'paid_at_required' => false,
                'is_active' => true,
                'sort_order' => (int) SubscriptionPlan::query()->max('sort_order') + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        SubscriptionPlan::query()->create($this->validatedData($request));

        return redirect()->route('subscription-plans.index')->with('success', 'اشتراک جدید با موفقیت تعریف شد.');
    }

    public function edit(SubscriptionPlan $subscriptionPlan): View
    {
        return view('dashboard.subscription-plans.edit', [
            'plan' => $subscriptionPlan,
        ]);
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $subscriptionPlan->update($this->validatedData($request, $subscriptionPlan));

        return redirect()->route('subscription-plans.index')->with('success', 'اشتراک با موفقیت بروزرسانی شد.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $subscriptionPlan->delete();

        return redirect()->route('subscription-plans.index')->with('success', 'اشتراک حذف شد.');
    }

    private function validatedData(Request $request, ?SubscriptionPlan $plan = null): array
    {
        $data = $request->all();
        $data['gateway_enabled'] = $request->boolean('gateway_enabled');
        $data['card_to_card_enabled'] = $request->boolean('card_to_card_enabled');
        $data['receipt_required'] = $request->boolean('receipt_required');
        $data['payer_card_required'] = $request->boolean('payer_card_required');
        $data['paid_at_required'] = $request->boolean('paid_at_required');
        $data['is_active'] = $request->boolean('is_active');

        $validator = Validator::make($data, [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:36'],
            'gateway_price' => ['nullable', 'integer', 'min:1000'],
            'gateway_enabled' => ['boolean'],
            'card_to_card_price' => ['nullable', 'integer', 'min:1000'],
            'card_to_card_enabled' => ['boolean'],
            'card_number' => ['nullable', 'string', 'max:32'],
            'card_owner' => ['nullable', 'string', 'max:120'],
            'card_accounts' => ['nullable', 'array'],
            'card_accounts.*.card_number' => ['nullable', 'string', 'max:32'],
            'card_accounts.*.bank_name' => ['nullable', 'string', 'max:120'],
            'card_accounts.*.card_owner' => ['nullable', 'string', 'max:120'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'receipt_required' => ['boolean'],
            'payer_card_required' => ['boolean'],
            'paid_at_required' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ], [
            'title.required' => 'عنوان اشتراک الزامی است.',
            'duration_months.required' => 'مدت اشتراک الزامی است.',
            'duration_months.min' => 'مدت اشتراک باید حداقل یک ماه باشد.',
            'gateway_price.min' => 'قیمت درگاه باید حداقل ۱۰۰۰ تومان باشد.',
            'card_to_card_price.min' => 'قیمت کارت به کارت باید حداقل ۱۰۰۰ تومان باشد.',
        ]);

        $validator->after(function ($validator) use ($data) {
            if (!$data['gateway_enabled'] && !$data['card_to_card_enabled']) {
                $validator->errors()->add('payment_methods', 'حداقل یکی از روش‌های پرداخت باید فعال باشد.');
            }

            if ($data['gateway_enabled'] && empty($data['gateway_price'])) {
                $validator->errors()->add('gateway_price', 'برای پرداخت درگاه، قیمت درگاه الزامی است.');
            }

            if ($data['card_to_card_enabled']) {
                if (empty($data['card_to_card_price'])) {
                    $validator->errors()->add('card_to_card_price', 'برای کارت به کارت، قیمت کارت به کارت الزامی است.');
                }

                if (empty($this->prepareCardAccounts($data['card_accounts'] ?? []))) {
                    $validator->errors()->add('card_accounts', 'برای کارت به کارت، حداقل یک شماره کارت مقصد تعریف کنید.');
                }
            }
        });

        $validated = $validator->validate();
        $cardAccounts = $this->prepareCardAccounts($validated['card_accounts'] ?? []);
        $features = $this->prepareFeatures($validated['features'] ?? []);
        $firstCardAccount = $cardAccounts[0] ?? [];
        $iconPath = $plan?->icon_path;

        if ($request->hasFile('icon')) {
            if ($iconPath) {
                Storage::disk('public')->delete($iconPath);
            }

            $iconPath = $request->file('icon')->store('subscription-plan-icons', 'public');
        }

        return [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'icon_path' => $iconPath,
            'features' => $features,
            'duration_months' => (int) $validated['duration_months'],
            'gateway_price' => $validated['gateway_enabled'] ? (int) $validated['gateway_price'] : null,
            'gateway_enabled' => (bool) $validated['gateway_enabled'],
            'card_to_card_price' => $validated['card_to_card_enabled'] ? (int) $validated['card_to_card_price'] : null,
            'card_to_card_enabled' => (bool) $validated['card_to_card_enabled'],
            'card_number' => $validated['card_to_card_enabled'] ? ($firstCardAccount['card_number'] ?? null) : null,
            'card_owner' => $validated['card_to_card_enabled'] ? ($firstCardAccount['card_owner'] ?? null) : null,
            'card_accounts' => $validated['card_to_card_enabled'] ? $cardAccounts : [],
            'receipt_required' => (bool) $validated['receipt_required'],
            'payer_card_required' => (bool) $validated['payer_card_required'],
            'paid_at_required' => (bool) $validated['paid_at_required'],
            'is_active' => (bool) $validated['is_active'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];
    }

    private function prepareCardAccounts(array $cardAccounts): array
    {
        return collect($cardAccounts)
            ->map(function ($account) {
                return [
                    'card_number' => trim((string) ($account['card_number'] ?? '')),
                    'bank_name' => trim((string) ($account['bank_name'] ?? '')),
                    'card_owner' => trim((string) ($account['card_owner'] ?? '')),
                ];
            })
            ->filter(fn ($account) => $account['card_number'] !== '')
            ->values()
            ->all();
    }

    private function prepareFeatures(array $features): array
    {
        return collect($features)
            ->map(fn ($feature) => trim((string) $feature))
            ->filter()
            ->values()
            ->all();
    }
}