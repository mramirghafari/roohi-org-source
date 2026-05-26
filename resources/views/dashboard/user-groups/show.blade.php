<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>جزئیات گروه کاربری - روحی بات</title>
    <meta content="" name="description" />
    <link href="{{ asset('/dashboard_theme') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/css/demo.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/js/config.js"></script>
    <link href="{{ asset('/dashboard_theme') }}/assets/css/rtl.css" rel="stylesheet" />
</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            @include('dashboard.sections.navbar')
            <div class="layout-page">
                <div class="content-wrapper">
                    @include('dashboard.sections.aside')
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><span class="text-muted fw-light">مدیریت گروه ها /</span>
                                {{ $group->name }}</h4>
                            <a href="{{ route('user-groups.index') }}" class="btn btn-label-dark">بازگشت</a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @php
                            $currentSupportIds = $group->supportAccounts->pluck('id')->map(fn($id) => (int) $id)->all();
                            $currentRules = $group->commissionRules ?? collect();
                        @endphp

                        <div class="row g-4">
                            <div class="col-12 col-lg-5">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="mb-3">تنظیمات گروه</h6>
                                        <form method="POST" action="{{ route('user-groups.update', $group) }}">
                                            @csrf
                                            <div class="mb-2">
                                                <label class="form-label">نام گروه</label>
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ old('name', $group->name) }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">توضیحات</label>
                                                <textarea class="form-control" name="description" rows="2">{{ old('description', $group->description) }}</textarea>
                                            </div>
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                    value="1" id="is_active"
                                                    {{ old('is_active', $group->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">گروه فعال باشد</label>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">حالت پورسانت گروه</label>
                                                <select class="form-select" name="commission_mode" required>
                                                    <option value="inherit" {{ old('commission_mode', $group->commission_mode) === 'inherit' ? 'selected' : '' }}>
                                                        ارث‌بری از تنظیمات پیش‌فرض سایت
                                                    </option>
                                                    <option value="custom" {{ old('commission_mode', $group->commission_mode) === 'custom' ? 'selected' : '' }}>
                                                        قوانین اختصاصی همین گروه
                                                    </option>
                                                </select>
                                            </div>

                                            <hr>
                                            <h6 class="mb-2">مقادیر پایه پورسانت</h6>
                                            <div class="mb-2">
                                                <label class="form-label">پاداش پایه جذب (Stars)</label>
                                                <input type="number" step="0.00000001" class="form-control"
                                                    name="attraction_stars_reward"
                                                    value="{{ old('attraction_stars_reward', $group->attraction_stars_reward) }}"
                                                    required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">درصد پایه پورسانت خرید VIP</label>
                                                <input type="number" step="0.01" min="0" max="100"
                                                    class="form-control" name="purchase_commission_percent"
                                                    value="{{ old('purchase_commission_percent', $group->purchase_commission_percent) }}"
                                                    required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">مبالغ پایه پورسانت خرید (تومان / تتر)</label>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <input type="number" step="0.00000001" min="0"
                                                            class="form-control" name="purchase_reward_toman"
                                                            value="{{ old('purchase_reward_toman', $group->purchase_reward_toman) }}"
                                                            placeholder="تومان" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" step="0.00000001" min="0"
                                                            class="form-control" name="purchase_reward_usdt"
                                                            value="{{ old('purchase_reward_usdt', $group->purchase_reward_usdt) }}"
                                                            placeholder="تتر" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">حالت پایه پاداش خرید</label>
                                                <select class="form-select" name="purchase_reward_mode" required>
                                                    <option value="none"
                                                        {{ old('purchase_reward_mode', $group->purchase_reward_mode) === 'none' ? 'selected' : '' }}>
                                                        بدون پاداش</option>
                                                    <option value="toman"
                                                        {{ old('purchase_reward_mode', $group->purchase_reward_mode) === 'toman' ? 'selected' : '' }}>
                                                        فقط تومان</option>
                                                    <option value="usdt"
                                                        {{ old('purchase_reward_mode', $group->purchase_reward_mode) === 'usdt' ? 'selected' : '' }}>
                                                        فقط تتر</option>
                                                    <option value="both"
                                                        {{ old('purchase_reward_mode', $group->purchase_reward_mode) === 'both' ? 'selected' : '' }}>
                                                        تومان + تتر</option>
                                                </select>
                                            </div>

                                            <hr>
                                            <h6 class="mb-2">پشتیبان‌های این گروه (ویرایش مستقیم)</h6>
                                            <div class="mb-3">
                                                <select class="form-select js-select2-multi" name="support_user_ids[]"
                                                    multiple size="6">
                                                    @foreach ($allUsers as $user)
                                                        @if ((int) $user->is_support === 1 || (int) $user->isAdmin === 1)
                                                            <option value="{{ $user->id }}"
                                                                {{ in_array((int) $user->id, $currentSupportIds, true) ? 'selected' : '' }}>
                                                                {{ $user->nam }} - {{ $user->mobile }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">با ذخیره فرم، لیست پشتیبان‌های گروه sync
                                                    می‌شود.</small>
                                            </div>

                                            <hr>
                                            <h6 class="mb-2">عملیات اشتراک گروهی</h6>
                                            <div class="mb-2">
                                                <label class="form-label">نوع عملیات اشتراک برای کل کاربران
                                                    گروه</label>
                                                <select class="form-select" name="subscription_operation">
                                                    <option value="none">بدون عملیات</option>
                                                    <option value="add_days">افزودن روز به اشتراک فعلی</option>
                                                    <option value="set_new">ست اشتراک جدید (مانند userInfo)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">تعداد روز</label>
                                                <input type="number" class="form-control" min="1"
                                                    max="3650" name="subscription_days"
                                                    placeholder="مثال: 7 یا 30 یا 90">
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100">ذخیره تنظیمات
                                                گروه</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-7">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h6 class="mb-3">اساین پشتیبان به گروه</h6>
                                        <form method="POST"
                                            action="{{ route('user-groups.supports.add', $group) }}">
                                            @csrf
                                            <div class="row g-2">
                                                <div class="col-12 col-md-9">
                                                    <select class="form-select" name="support_user_id" required>
                                                        <option value="">انتخاب اکانت پشتیبان</option>
                                                        @foreach ($allUsers as $user)
                                                            @if ((int) $user->is_support === 1 || (int) $user->isAdmin === 1)
                                                                <option value="{{ $user->id }}">
                                                                    {{ $user->nam }} - {{ $user->mobile }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <button type="submit"
                                                        class="btn btn-warning w-100">اساین</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive text-nowrap mt-3">
                                            <table class="table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>نام</th>
                                                        <th>موبایل</th>
                                                        <th>نقش</th>
                                                        <th>عملیات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($group->supportAccounts as $index => $support)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $support->nam }}</td>
                                                            <td>{{ $support->mobile }}</td>
                                                            <td>
                                                                @if ((int) $support->isAdmin === 1)
                                                                    <span class="badge bg-label-danger">مدیرکل</span>
                                                                @else
                                                                    <span class="badge bg-label-warning">پشتیبان</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <form method="POST"
                                                                    action="{{ route('user-groups.supports.remove', [$group, $support]) }}">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-label-danger">حذف</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">پشتیبانی
                                                                برای این گروه اساین نشده است</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="mb-3">پورسانت لول‌بندی‌شده دعوت</h6>

                                        @if (($group->commission_mode ?? 'inherit') === 'inherit')
                                            <div class="alert alert-info">
                                                این گروه روی حالت <strong>ارث‌بری از تنظیمات پیش‌فرض سایت</strong> است.
                                                برای اعمال قوانین اختصاصی، در فرم تنظیمات گروه حالت پورسانت را روی «قوانین اختصاصی همین گروه» بگذار.
                                            </div>
                                        @endif

                                        <form method="POST"
                                            action="{{ route('user-groups.commissions.update', $group) }}">
                                            @csrf
                                            <div class="table-responsive text-nowrap">
                                                <table class="table" id="commission-rules-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>رویداد</th>
                                                            <th>لول</th>
                                                            <th>Stars</th>
                                                            <th>تومان</th>
                                                            <th>تتر</th>
                                                            <th>درصد</th>
                                                            <th>فعال</th>
                                                            <th>حذف</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($currentRules as $ruleIndex => $rule)
                                                            <tr>
                                                                <td>
                                                                    <select class="form-select"
                                                                        name="rules[{{ $ruleIndex }}][event]"
                                                                        required>
                                                                        <option value="referral_register"
                                                                            {{ $rule->event === 'referral_register' ? 'selected' : '' }}>
                                                                            دعوت دوست (ثبت‌نام)</option>
                                                                        <option value="referral_vip_purchase"
                                                                            {{ $rule->event === 'referral_vip_purchase' ? 'selected' : '' }}>
                                                                            دعوت فعال (خرید VIP)</option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="number" class="form-control"
                                                                        min="1"
                                                                        name="rules[{{ $ruleIndex }}][level]"
                                                                        value="{{ $rule->level }}" required></td>
                                                                <td><input type="number" step="0.00000001"
                                                                        min="0" class="form-control"
                                                                        name="rules[{{ $ruleIndex }}][stars_reward]"
                                                                        value="{{ $rule->stars_reward }}"></td>
                                                                <td><input type="number" step="0.00000001"
                                                                        min="0" class="form-control"
                                                                        name="rules[{{ $ruleIndex }}][toman_reward]"
                                                                        value="{{ $rule->toman_reward }}"></td>
                                                                <td><input type="number" step="0.00000001"
                                                                        min="0" class="form-control"
                                                                        name="rules[{{ $ruleIndex }}][usdt_reward]"
                                                                        value="{{ $rule->usdt_reward }}"></td>
                                                                <td><input type="number" step="0.01"
                                                                        min="0" max="100"
                                                                        class="form-control"
                                                                        name="rules[{{ $ruleIndex }}][commission_percent]"
                                                                        value="{{ $rule->commission_percent }}"></td>
                                                                <td>
                                                                    <select class="form-select"
                                                                        name="rules[{{ $ruleIndex }}][is_active]">
                                                                        <option value="1"
                                                                            {{ $rule->is_active ? 'selected' : '' }}>
                                                                            بله</option>
                                                                        <option value="0"
                                                                            {{ !$rule->is_active ? 'selected' : '' }}>
                                                                            خیر</option>
                                                                    </select>
                                                                </td>
                                                                <td><button type="button"
                                                                        class="btn btn-sm btn-label-danger remove-rule-row">حذف</button>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td>
                                                                    <select class="form-select" name="rules[0][event]"
                                                                        required>
                                                                        <option value="referral_register">دعوت دوست
                                                                            (ثبت‌نام)
                                                                        </option>
                                                                        <option value="referral_vip_purchase">دعوت فعال
                                                                            (خرید VIP)</option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="number" class="form-control"
                                                                        min="1" name="rules[0][level]"
                                                                        value="1" required></td>
                                                                <td><input type="number" step="0.00000001"
                                                                        min="0" class="form-control"
                                                                        name="rules[0][stars_reward]" value="0">
                                                                </td>
                                                                <td><input type="number" step="0.00000001"
                                                                        min="0" class="form-control"
                                                                        name="rules[0][toman_reward]" value="0">
                                                                </td>
                                                                <td><input type="number" step="0.00000001"
                                                                        min="0" class="form-control"
                                                                        name="rules[0][usdt_reward]" value="0">
                                                                </td>
                                                                <td><input type="number" step="0.01"
                                                                        min="0" max="100"
                                                                        class="form-control"
                                                                        name="rules[0][commission_percent]"
                                                                        value="0"></td>
                                                                <td>
                                                                    <select class="form-select"
                                                                        name="rules[0][is_active]">
                                                                        <option value="1" selected>بله</option>
                                                                        <option value="0">خیر</option>
                                                                    </select>
                                                                </td>
                                                                <td><button type="button"
                                                                        class="btn btn-sm btn-label-danger remove-rule-row">حذف</button>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex gap-2 mt-2">
                                                <button type="button" class="btn btn-label-primary"
                                                    id="add-commission-rule" {{ ($group->commission_mode ?? 'inherit') !== 'custom' ? 'disabled' : '' }}>افزودن لول جدید</button>
                                                <button type="submit" class="btn btn-primary" {{ ($group->commission_mode ?? 'inherit') !== 'custom' ? 'disabled' : '' }}>ذخیره پورسانت‌های
                                                    لول‌بندی‌شده</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h6 class="mb-3">اعضای گروه</h6>
                                        <form method="POST" action="{{ route('user-groups.members.add', $group) }}">
                                            @csrf
                                            <div class="row g-2">
                                                <div class="col-12 col-md-7">
                                                    <select class="form-select" name="user_id" required>
                                                        <option value="">انتخاب کاربر</option>
                                                        @foreach ($allUsers as $user)
                                                            <option value="{{ $user->id }}">{{ $user->nam }} -
                                                                {{ $user->mobile }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <input type="text" class="form-control" name="notes"
                                                        placeholder="توضیح (اختیاری)">
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <button type="submit"
                                                        class="btn btn-success w-100">افزودن</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive text-nowrap mt-3">
                                            <table class="table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>نام کاربر</th>
                                                        <th>موبایل</th>
                                                        <th>عملیات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($group->members as $index => $member)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $member->nam }}</td>
                                                            <td>{{ $member->mobile }}</td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <a href="{{ route('users.detail', $member->id) }}"
                                                                        class="btn btn-sm btn-label-info">پروفایل</a>
                                                                    <form method="POST"
                                                                        action="{{ route('user-groups.members.remove', [$group, $member]) }}">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-label-danger">حذف</button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted">هنوز
                                                                کاربری عضو این گروه نشده است</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('dashboard.sections.footer')
                        <div class="content-backdrop fade"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <script>
        $('.users').addClass('active').removeClass('open');
        $('.user-groups').addClass('active').removeClass('open');

        $(function() {
            $('.js-select2-multi').select2({
                width: '100%',
                dir: 'rtl'
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('#commission-rules-table tbody');
            const addBtn = document.getElementById('add-commission-rule');

            if (!tableBody || !addBtn) {
                return;
            }

            const buildRow = (index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <select class="form-select" name="rules[${index}][event]" required>
                            <option value="referral_register">دعوت دوست (ثبت‌نام)</option>
                            <option value="referral_vip_purchase">دعوت فعال (خرید VIP)</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control" min="1" name="rules[${index}][level]" value="1" required></td>
                    <td><input type="number" step="0.00000001" min="0" class="form-control" name="rules[${index}][stars_reward]" value="0"></td>
                    <td><input type="number" step="0.00000001" min="0" class="form-control" name="rules[${index}][toman_reward]" value="0"></td>
                    <td><input type="number" step="0.00000001" min="0" class="form-control" name="rules[${index}][usdt_reward]" value="0"></td>
                    <td><input type="number" step="0.01" min="0" max="100" class="form-control" name="rules[${index}][commission_percent]" value="0"></td>
                    <td>
                        <select class="form-select" name="rules[${index}][is_active]">
                            <option value="1" selected>بله</option>
                            <option value="0">خیر</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-sm btn-label-danger remove-rule-row">حذف</button></td>
                `;

                return tr;
            };

            const nextIndex = () => tableBody.querySelectorAll('tr').length;

            addBtn.addEventListener('click', function() {
                tableBody.appendChild(buildRow(nextIndex()));
            });

            tableBody.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-rule-row')) {
                    const row = event.target.closest('tr');
                    if (!row) {
                        return;
                    }
                    row.remove();
                }
            });
        });
    </script>
</body>

</html>
