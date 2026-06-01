@php
    $gatewayEnabled = old('gateway_enabled', (int) ($plan->gateway_enabled ?? 1));
    $cardToCardEnabled = old('card_to_card_enabled', (int) ($plan->card_to_card_enabled ?? 0));
    $receiptRequired = old('receipt_required', (int) ($plan->receipt_required ?? 1));
    $payerCardRequired = old('payer_card_required', (int) ($plan->payer_card_required ?? 1));
    $paidAtRequired = old('paid_at_required', (int) ($plan->paid_at_required ?? 0));
    $isActive = old('is_active', (int) ($plan->is_active ?? 1));
    $cardAccounts = old('card_accounts', $plan->card_accounts ?? []);
    if (empty($cardAccounts) && !empty($plan->card_number)) {
        $cardAccounts = [
            [
                'card_number' => $plan->card_number,
                'bank_name' => '',
                'card_owner' => $plan->card_owner,
            ],
        ];
    }
    if (empty($cardAccounts)) {
        $cardAccounts = [['card_number' => '', 'bank_name' => '', 'card_owner' => '']];
    }
    $features = old('features', $plan->features ?? []);
    if (empty($features)) {
        $features = [''];
    }
@endphp

@if ($errors->any())
    <div class="alert alert-danger">لطفا خطاهای فرم را بررسی کنید.</div>
@endif

@error('payment_methods')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="row g-3">
        <div class="col-12 col-md-8">
            <label class="form-label">عنوان اشتراک</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                value="{{ old('title', $plan->title) }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-2">
            <label class="form-label">مدت (ماه)</label>
            <input type="number" min="1" max="36"
                class="form-control @error('duration_months') is-invalid @enderror" name="duration_months"
                value="{{ old('duration_months', $plan->duration_months) }}" required>
            @error('duration_months')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-2">
            <label class="form-label">ترتیب نمایش</label>
            <input type="number" min="0" class="form-control @error('sort_order') is-invalid @enderror"
                name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}">
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label">توضیحات</label>
            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $plan->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <div class="card border shadow-none">
                <div class="card-body">
                    <h6 class="mb-3">آیکون اختصاصی اشتراک</h6>
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-3 text-center">
                            @if (!empty($plan->icon_path))
                                <img src="{{ asset('storage/' . $plan->icon_path) }}" alt="{{ $plan->title }}"
                                    class="rounded border p-2"
                                    style="max-width: 120px; max-height: 120px; object-fit: contain;">
                            @else
                                <div class="rounded border bg-label-secondary d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 120px; height: 120px;">
                                    <i class="ti ti-photo text-muted" style="font-size: 42px;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-12 col-md-9">
                            <label class="form-label">فایل آیکون</label>
                            <input type="file" name="icon" accept="image/*"
                                class="form-control @error('icon') is-invalid @enderror">
                            <small class="text-muted d-block mt-2">فرمت‌های مجاز: jpg، png، webp، svg. حداکثر حجم ۲
                                مگابایت.</small>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card border shadow-none">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">آپشن‌های اشتراک</h6>
                        <button type="button" class="btn btn-sm btn-label-primary" id="add-feature-row">افزودن
                            آپشن</button>
                    </div>
                    <div id="features-wrapper" class="row g-2">
                        @foreach ($features as $featureIndex => $feature)
                            <div class="col-12 feature-row">
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control @error('features.' . $featureIndex) is-invalid @enderror"
                                        name="features[]" value="{{ $feature }}"
                                        placeholder="مثلا دسترسی به سیگنال‌های VIP">
                                    <button type="button" class="btn btn-label-danger remove-feature-row">حذف</button>
                                </div>
                                @error('features.' . $featureIndex)
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border shadow-none h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">پرداخت درگاه</h6>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="gateway_enabled" value="0">
                            <input class="form-check-input" type="checkbox" name="gateway_enabled" value="1"
                                id="gateway_enabled" {{ (int) $gatewayEnabled === 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="gateway_enabled">فعال</label>
                        </div>
                    </div>
                    <label class="form-label">قیمت درگاه (تومان)</label>
                    <input type="number" min="1000"
                        class="form-control @error('gateway_price') is-invalid @enderror" name="gateway_price"
                        value="{{ old('gateway_price', $plan->gateway_price) }}">
                    @error('gateway_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border shadow-none h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">پرداخت کارت به کارت</h6>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="card_to_card_enabled" value="0">
                            <input class="form-check-input" type="checkbox" name="card_to_card_enabled"
                                value="1" id="card_to_card_enabled"
                                {{ (int) $cardToCardEnabled === 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="card_to_card_enabled">فعال</label>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">قیمت کارت به کارت (تومان)</label>
                            <input type="number" min="1000"
                                class="form-control @error('card_to_card_price') is-invalid @enderror"
                                name="card_to_card_price"
                                value="{{ old('card_to_card_price', $plan->card_to_card_price) }}">
                            @error('card_to_card_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">شماره کارت‌های مقصد</label>
                                <button type="button" class="btn btn-sm btn-label-primary"
                                    id="add-card-account-row">افزودن کارت</button>
                            </div>
                            @error('card_accounts')
                                <div class="text-danger small mb-2">{{ $message }}</div>
                            @enderror
                            <div id="card-accounts-wrapper" class="row g-2">
                                @foreach ($cardAccounts as $cardIndex => $cardAccount)
                                    <div class="col-12 card-account-row">
                                        <div class="row g-2 align-items-start">
                                            <div class="col-12 col-md-4">
                                                <input type="text" maxlength="32"
                                                    class="form-control @error('card_accounts.' . $cardIndex . '.card_number') is-invalid @enderror"
                                                    name="card_accounts[{{ $cardIndex }}][card_number]"
                                                    value="{{ $cardAccount['card_number'] ?? '' }}"
                                                    placeholder="شماره کارت">
                                                @error('card_accounts.' . $cardIndex . '.card_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="text" maxlength="120"
                                                    class="form-control @error('card_accounts.' . $cardIndex . '.bank_name') is-invalid @enderror"
                                                    name="card_accounts[{{ $cardIndex }}][bank_name]"
                                                    value="{{ $cardAccount['bank_name'] ?? '' }}"
                                                    placeholder="نام بانک">
                                                @error('card_accounts.' . $cardIndex . '.bank_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <input type="text" maxlength="120"
                                                    class="form-control @error('card_accounts.' . $cardIndex . '.card_owner') is-invalid @enderror"
                                                    name="card_accounts[{{ $cardIndex }}][card_owner]"
                                                    value="{{ $cardAccount['card_owner'] ?? '' }}"
                                                    placeholder="نام صاحب کارت">
                                                @error('card_accounts.' . $cardIndex . '.card_owner')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-1">
                                                <button type="button"
                                                    class="btn btn-label-danger w-100 remove-card-account-row">حذف</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="receipt_required" value="0">
                                <input class="form-check-input" type="checkbox" name="receipt_required"
                                    value="1" id="receipt_required"
                                    {{ (int) $receiptRequired === 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="receipt_required">رسید اجباری</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="payer_card_required" value="0">
                                <input class="form-check-input" type="checkbox" name="payer_card_required"
                                    value="1" id="payer_card_required"
                                    {{ (int) $payerCardRequired === 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="payer_card_required">شماره کارت کاربر
                                    اجباری</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="paid_at_required" value="0">
                                <input class="form-check-input" type="checkbox" name="paid_at_required"
                                    value="1" id="paid_at_required"
                                    {{ (int) $paidAtRequired === 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="paid_at_required">تاریخ و ساعت اجباری</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="form-check form-switch">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                    {{ (int) $isActive === 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">این اشتراک فعال باشد</label>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-4">{{ $submitLabel }}</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const featuresWrapper = document.getElementById('features-wrapper');
        const addFeatureButton = document.getElementById('add-feature-row');
        const cardsWrapper = document.getElementById('card-accounts-wrapper');
        const addCardButton = document.getElementById('add-card-account-row');

        if (featuresWrapper && addFeatureButton) {
            addFeatureButton.addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'col-12 feature-row';
                row.innerHTML = `
                    <div class="input-group">
                        <input type="text" class="form-control" name="features[]" placeholder="مثلا دسترسی به سیگنال‌های VIP">
                        <button type="button" class="btn btn-label-danger remove-feature-row">حذف</button>
                    </div>
                `;
                featuresWrapper.appendChild(row);
            });

            featuresWrapper.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-feature-row')) {
                    event.target.closest('.feature-row')?.remove();
                }
            });
        }

        if (cardsWrapper && addCardButton) {
            const buildCardRow = function(index) {
                const row = document.createElement('div');
                row.className = 'col-12 card-account-row';
                row.innerHTML = `
                    <div class="row g-2 align-items-start">
                        <div class="col-12 col-md-4">
                            <input type="text" maxlength="32" class="form-control" name="card_accounts[${index}][card_number]" placeholder="شماره کارت">
                        </div>
                        <div class="col-12 col-md-3">
                            <input type="text" maxlength="120" class="form-control" name="card_accounts[${index}][bank_name]" placeholder="نام بانک">
                        </div>
                        <div class="col-12 col-md-4">
                            <input type="text" maxlength="120" class="form-control" name="card_accounts[${index}][card_owner]" placeholder="نام صاحب کارت">
                        </div>
                        <div class="col-12 col-md-1">
                            <button type="button" class="btn btn-label-danger w-100 remove-card-account-row">حذف</button>
                        </div>
                    </div>
                `;
                return row;
            };

            addCardButton.addEventListener('click', function() {
                cardsWrapper.appendChild(buildCardRow(cardsWrapper.querySelectorAll('.card-account-row')
                    .length));
            });

            cardsWrapper.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-card-account-row')) {
                    event.target.closest('.card-account-row')?.remove();
                }
            });
        }
    });
</script>
