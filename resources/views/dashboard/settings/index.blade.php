<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>تنظیمات سایت - روحی بات</title>
    <meta content="" name="description" />
    <link href="{{ asset('/dashboard_theme') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/css/demo.css" rel="stylesheet" />
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
                            <h4 class="mb-0"><span class="text-muted fw-light">مدیریت /</span> تنظیمات سایت</h4>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="mb-3">تنظیمات کلی</h6>
                                        <form method="POST" action="{{ route('settings.update') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">شرط عضوگیری زیرمجموعه‌ها</label>
                                                <select class="form-select" name="referral_join_condition" required>
                                                    <option value="register"
                                                        {{ $referralJoinCondition === 'register' ? 'selected' : '' }}>
                                                        صرفاً ثبت‌نام کاربر
                                                    </option>
                                                    <option value="vip_active"
                                                        {{ $referralJoinCondition === 'vip_active' ? 'selected' : '' }}>
                                                        فقط زمانی که زیرمجموعه VIP فعال داشته باشد
                                                    </option>
                                                </select>
                                                <small class="text-muted d-block mt-2">
                                                    این گزینه تعیین می‌کند در صفحه «زیرمجموعه‌های من»، عضوگیری بر چه
                                                    مبنایی محاسبه شود.
                                                </small>
                                            </div>

                                            <hr>
                                            <h6 class="mb-3">پورسانت پیش‌فرض کاربران خارج از گروه</h6>
                                            <div class="alert alert-info">
                                                کاربرانی که عضو هیچ گروهی نیستند، از قوانین زیر به‌عنوان پورسانت پیش‌فرض
                                                استفاده می‌کنند.
                                            </div>

                                            <div class="table-responsive text-nowrap">
                                                <table class="table" id="default-commission-rules-table">
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
                                                        @forelse ($defaultRules as $index => $rule)
                                                            <tr>
                                                                <td>
                                                                    <select class="form-select"
                                                                        name="rules[{{ $index }}][event]"
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
                                                                        name="rules[{{ $index }}][level]"
                                                                        value="{{ $rule->level }}" required></td>
                                                                <td><input type="text" inputmode="numeric"
                                                                        class="form-control js-int-format"
                                                                        name="rules[{{ $index }}][stars_reward]"
                                                                        value="{{ number_format((float) $rule->stars_reward, 0, '.', ',') }}">
                                                                </td>
                                                                <td><input type="text" inputmode="numeric"
                                                                        class="form-control js-int-format"
                                                                        name="rules[{{ $index }}][toman_reward]"
                                                                        value="{{ number_format((float) $rule->toman_reward, 0, '.', ',') }}">
                                                                </td>
                                                                <td><input type="text" inputmode="numeric"
                                                                        class="form-control js-int-format"
                                                                        name="rules[{{ $index }}][usdt_reward]"
                                                                        value="{{ number_format((float) $rule->usdt_reward, 0, '.', ',') }}">
                                                                </td>
                                                                <td><input type="text" inputmode="numeric"
                                                                        class="form-control js-int-format"
                                                                        name="rules[{{ $index }}][commission_percent]"
                                                                        value="{{ number_format((float) $rule->commission_percent, 0, '.', ',') }}">
                                                                </td>
                                                                <td>
                                                                    <select class="form-select"
                                                                        name="rules[{{ $index }}][is_active]">
                                                                        <option value="1"
                                                                            {{ $rule->is_active ? 'selected' : '' }}>
                                                                            بله</option>
                                                                        <option value="0"
                                                                            {{ !$rule->is_active ? 'selected' : '' }}>
                                                                            خیر</option>
                                                                    </select>
                                                                </td>
                                                                <td><button type="button"
                                                                        class="btn btn-sm btn-label-danger remove-default-rule-row">حذف</button>
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
                                                                <td><input type="text" inputmode="numeric"
                                                                        class="form-control js-int-format"
                                                                        name="rules[0][stars_reward]" value="0">
                                                                </td>
                                                                <td><input type="text" inputmode="numeric"
                                                                        class="form-control js-int-format"
                                                                        name="rules[0][toman_reward]" value="0">
                                                                </td>
                                                                <td><input type="text" inputmode="numeric"
                                                                        class="form-control js-int-format"
                                                                        name="rules[0][usdt_reward]" value="0">
                                                                </td>
                                                                <td><input type="text" inputmode="numeric"
                                                                        class="form-control js-int-format"
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
                                                                        class="btn btn-sm btn-label-danger remove-default-rule-row">حذف</button>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="mb-3">
                                                <button type="button" class="btn btn-label-primary"
                                                    id="add-default-commission-rule">افزودن لول جدید</button>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100">ذخیره
                                                تنظیمات</button>
                                        </form>
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
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <script>
        $('.settings').addClass('active');

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('#default-commission-rules-table tbody');
            const addBtn = document.getElementById('add-default-commission-rule');

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
                    <td><input type="text" inputmode="numeric" class="form-control js-int-format" name="rules[${index}][stars_reward]" value="0"></td>
                    <td><input type="text" inputmode="numeric" class="form-control js-int-format" name="rules[${index}][toman_reward]" value="0"></td>
                    <td><input type="text" inputmode="numeric" class="form-control js-int-format" name="rules[${index}][usdt_reward]" value="0"></td>
                    <td><input type="text" inputmode="numeric" class="form-control js-int-format" name="rules[${index}][commission_percent]" value="0"></td>
                    <td>
                        <select class="form-select" name="rules[${index}][is_active]">
                            <option value="1" selected>بله</option>
                            <option value="0">خیر</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-sm btn-label-danger remove-default-rule-row">حذف</button></td>
                `;
                return tr;
            };

            addBtn.addEventListener('click', function() {
                const index = tableBody.querySelectorAll('tr').length;
                tableBody.appendChild(buildRow(index));
            });

            tableBody.addEventListener('click', function(event) {
                if (!event.target.classList.contains('remove-default-rule-row')) {
                    return;
                }
                const row = event.target.closest('tr');
                if (row) {
                    row.remove();
                }
            });

            const formatThousands = (value) => {
                if (!value) {
                    return '';
                }

                return value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            };

            const normalizeInput = (input) => {
                const digits = input.value.replace(/[^\d]/g, '');
                input.value = formatThousands(digits);
            };

            document.addEventListener('input', function(event) {
                if (event.target.classList.contains('js-int-format')) {
                    normalizeInput(event.target);
                }
            });

            document.querySelectorAll('.js-int-format').forEach(function(input) {
                normalizeInput(input);
            });

            const form = document.querySelector('form[action="{{ route('settings.update') }}"]');
            form?.addEventListener('submit', function() {
                form.querySelectorAll('.js-int-format').forEach(function(input) {
                    input.value = input.value.replace(/,/g, '');
                });
            });
        });
    </script>
</body>

</html>
