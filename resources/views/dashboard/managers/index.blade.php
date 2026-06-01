<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>مدیران - روحی بات</title>
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
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                            <div>
                                <h4 class="mb-1"><span class="text-muted fw-light">مدیریت کاربران /</span> مدیران</h4>
                                <p class="text-muted mb-0">کاربرها را به نقش‌های فروش، پشتیبانی، سرپرست یا بازاریاب وصل
                                    کنید.</p>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">لطفا خطاهای فرم را بررسی کنید.</div>
                        @endif

                        <div class="row g-4">
                            <div class="col-12 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="mb-3">تخصیص نقش به کاربر</h6>
                                        <form method="POST" action="{{ route('managers.assign') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">کاربر</label>
                                                <select class="form-select" name="user_id" required>
                                                    <option value="">انتخاب کاربر</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->nam ?: 'کاربر #' . $user->id }} -
                                                            {{ $user->mobile }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">نقش‌ها</label>
                                                @foreach ($roles as $role)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="role_ids[]" value="{{ $role->id }}"
                                                            id="manager_role_{{ $role->id }}">
                                                        <label class="form-check-label"
                                                            for="manager_role_{{ $role->id }}">{{ $role->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="is_admin" value="0">
                                                        <input class="form-check-input" type="checkbox" name="is_admin"
                                                            value="1" id="manager_is_admin">
                                                        <label class="form-check-label" for="manager_is_admin">ادمین
                                                            سایت</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="is_support" value="0">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="is_support" value="1" id="manager_is_support">
                                                        <label class="form-check-label" for="manager_is_support">پشتیبان
                                                            تیکت</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">ذخیره نقش‌های
                                                کاربر</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-xl-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="mb-3">لیست مدیران و اعضای نقش‌دار</h6>
                                        <div class="table-responsive text-nowrap">
                                            <table class="table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>کاربر</th>
                                                        <th>نقش‌ها</th>
                                                        <th>دسترسی پایه</th>
                                                        <th>عملیات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($managers as $manager)
                                                        <tr>
                                                            <td>
                                                                <div class="fw-semibold">
                                                                    {{ $manager->nam ?: 'کاربر #' . $manager->id }}
                                                                </div>
                                                                <small
                                                                    class="text-muted">{{ $manager->mobile ?: '-' }}</small>
                                                            </td>
                                                            <td>
                                                                @forelse ($manager->roles as $role)
                                                                    <span
                                                                        class="badge bg-label-primary mb-1">{{ $role->name }}</span>
                                                                @empty
                                                                    <span class="text-muted">بدون نقش</span>
                                                                @endforelse
                                                            </td>
                                                            <td>
                                                                @if ((int) $manager->isAdmin === 1)
                                                                    <span class="badge bg-label-success">ادمین</span>
                                                                @endif
                                                                @if ((int) $manager->is_support === 1)
                                                                    <span class="badge bg-label-info">پشتیبان</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('users.detail', $manager->id) }}"
                                                                    class="btn btn-sm btn-info">پرونده کاربر</a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-4">
                                                                هنوز مدیری تعریف نشده است.</td>
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
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <script>
        $('.managers').addClass('active').removeClass('open');
    </script>
</body>

</html>
