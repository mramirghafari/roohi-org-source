<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>ایجاد گروه کاربری - روحی بات</title>
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
                            <h4 class="mb-0"><span class="text-muted fw-light">مدیریت کاربران / گروه های کاربری
                                    /</span> ایجاد گروه جدید</h4>
                            <a href="{{ route('user-groups.index') }}" class="btn btn-label-dark">بازگشت</a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="row justify-content-center">
                            <div class="col-12 col-xl-10">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="mb-3">فرم ایجاد گروه جدید</h6>
                                        <form method="POST" action="{{ route('user-groups.store') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">نام گروه</label>
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ old('name') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">توضیحات (اختیاری)</label>
                                                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">حالت پورسانت گروه</label>
                                                <select class="form-select" name="commission_mode" required>
                                                    <option value="inherit"
                                                        {{ old('commission_mode', 'inherit') === 'inherit' ? 'selected' : '' }}>
                                                        ارث‌بری از تنظیمات پیش‌فرض سایت
                                                    </option>
                                                    <option value="custom"
                                                        {{ old('commission_mode') === 'custom' ? 'selected' : '' }}>
                                                        قوانین اختصاصی همین گروه
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">مسئول‌های گروه</label>
                                                <div class="row g-3">
                                                    @foreach ($assignmentRoles as $assignmentRole => $assignmentLabel)
                                                        <div class="col-12 col-md-6">
                                                            <label class="form-label">{{ $assignmentLabel }}</label>
                                                            <select class="form-select js-select2-single"
                                                                name="assignment_user_ids[{{ $assignmentRole }}]">
                                                                <option value="">انتخاب نشده</option>
                                                                @foreach ($assignmentUsers[$assignmentRole] ?? collect() as $assignmentUser)
                                                                    <option value="{{ $assignmentUser->id }}"
                                                                        {{ (string) old('assignment_user_ids.' . $assignmentRole) === (string) $assignmentUser->id ? 'selected' : '' }}>
                                                                        {{ $assignmentUser->nam ?: $assignmentUser->name ?: 'کاربر #' . $assignmentUser->id }}
                                                                        - {{ $assignmentUser->mobile }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <small class="text-muted d-block mt-2">هر لیست فقط کاربران دارای همان
                                                    نقش کاربری را نمایش می‌دهد.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">انتخاب کاربران اولیه گروه (اختیاری)</label>
                                                <select class="form-select js-select2-multi" name="member_user_ids[]"
                                                    multiple size="8">
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->nam }} -
                                                            {{ $user->mobile }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="alert alert-info">
                                                <strong>پورسانت لول‌بندی:</strong>
                                                می‌تونی همینجا قوانین اولیه پورسانت را تعریف کنی یا بعد از ایجاد گروه از
                                                بخش «جزئیات گروه» ویرایشش کنی.
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">تعریف اولیه پورسانت‌های لول‌بندی‌شده
                                                    (اختیاری)</label>
                                                <div class="table-responsive text-nowrap">
                                                    <table class="table" id="create-commission-rules-table">
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
                                                            <tr>
                                                                <td>
                                                                    <select class="form-select" name="rules[0][event]"
                                                                        required>
                                                                        <option value="referral_register">دعوت دوست
                                                                            (ثبت‌نام)</option>
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
                                                                        class="btn btn-sm btn-label-danger remove-create-rule-row">حذف</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button type="button" class="btn btn-label-primary"
                                                    id="add-create-commission-rule">افزودن لول جدید</button>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100">ایجاد گروه</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <script>
        $('.user-groups').addClass('active').removeClass('open');

        $(function() {
            $('.js-select2-multi, .js-select2-single').select2({
                width: '100%',
                dir: 'rtl'
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('#create-commission-rules-table tbody');
            const addBtn = document.getElementById('add-create-commission-rule');

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
                    <td><button type="button" class="btn btn-sm btn-label-danger remove-create-rule-row">حذف</button></td>
                `;
                return tr;
            };

            addBtn.addEventListener('click', function() {
                const index = tableBody.querySelectorAll('tr').length;
                tableBody.appendChild(buildRow(index));
            });

            tableBody.addEventListener('click', function(event) {
                if (!event.target.classList.contains('remove-create-rule-row')) {
                    return;
                }
                const row = event.target.closest('tr');
                if (row) {
                    row.remove();
                }
            });
        });
    </script>
</body>

</html>
