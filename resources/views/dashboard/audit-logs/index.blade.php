<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>لاگ فعالیت‌ها - روحی بات</title>
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
                                <h4 class="mb-1"><span class="text-muted fw-light">مدیریت کاربران /</span> لاگ
                                    فعالیت‌ها</h4>
                                <p class="text-muted mb-0">بازدید صفحات و عملیات حساس نقش‌ها و مدیران از اینجا قابل
                                    بررسی است.</p>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="GET" action="{{ route('audit-logs.index') }}"
                                    class="row g-3 align-items-end">
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">نوع رویداد</label>
                                        <select name="event" class="form-select">
                                            <option value="">همه</option>
                                            @foreach ($events as $event)
                                                <option value="{{ $event }}"
                                                    {{ request('event') === $event ? 'selected' : '' }}>
                                                    {{ $event }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">بخش</label>
                                        <select name="area" class="form-select">
                                            <option value="">همه</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area }}"
                                                    {{ request('area') === $area ? 'selected' : '' }}>
                                                    {{ $area }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">عملیات</label>
                                        <select name="action" class="form-select">
                                            <option value="">همه</option>
                                            @foreach ($actions as $action)
                                                <option value="{{ $action }}"
                                                    {{ request('action') === $action ? 'selected' : '' }}>
                                                    {{ $action }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">کاربر</label>
                                        <select name="user_id" class="form-select">
                                            <option value="">همه</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>
                                                    {{ $user->nam ?: $user->name ?: 'کاربر #' . $user->id }} -
                                                    {{ $user->mobile }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">جستجو</label>
                                        <input type="text" name="q" class="form-control"
                                            value="{{ request('q') }}" placeholder="مسیر، اکشن، IP یا کاربر">
                                    </div>
                                    <div class="col-12 d-flex gap-2">
                                        <button class="btn btn-primary" type="submit">فیلتر</button>
                                        <a href="{{ route('audit-logs.index') }}" class="btn btn-label-secondary">حذف
                                            فیلتر</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">آخرین لاگ‌ها</h6>
                                    <span class="badge bg-label-primary">{{ number_format($logs->total()) }}
                                        رکورد</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>زمان</th>
                                                <th>کاربر</th>
                                                <th>رویداد</th>
                                                <th>مسیر / عملیات</th>
                                                <th>نقش‌های کاربر</th>
                                                <th>IP</th>
                                                <th>جزئیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($logs as $log)
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <div>{{ optional($log->occurred_at)->format('Y-m-d') }}</div>
                                                        <small
                                                            class="text-muted">{{ optional($log->occurred_at)->format('H:i:s') }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($log->user)
                                                            <div class="fw-semibold">
                                                                {{ $log->user->nam ?: $log->user->name ?: 'کاربر #' . $log->user->id }}
                                                            </div>
                                                            <small
                                                                class="text-muted">{{ $log->user->mobile ?: $log->user->email }}</small>
                                                        @else
                                                            <span class="text-muted">سیستمی / حذف‌شده</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-label-{{ $log->event === 'page_view' ? 'info' : 'warning' }}">{{ $log->event }}</span>
                                                        @if ($log->area)
                                                            <div><small class="text-muted">{{ $log->area }}</small>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 260px;">
                                                        <div class="fw-semibold">{{ $log->action ?: '-' }}</div>
                                                        <small class="text-muted">{{ $log->method }}
                                                            {{ $log->path }}</small>
                                                        @if ($log->route_name)
                                                            <div><small class="text-muted">route:
                                                                    {{ $log->route_name }}</small></div>
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 180px;">
                                                        @forelse (($log->actor_roles ?? []) as $role)
                                                            <span
                                                                class="badge bg-label-primary mb-1">{{ $role['name'] ?? ($role['slug'] ?? '-') }}</span>
                                                        @empty
                                                            <span class="text-muted">بدون نقش</span>
                                                        @endforelse
                                                    </td>
                                                    <td class="text-nowrap">{{ $log->ip_address }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-label-info" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#audit-log-{{ $log->id }}">مشاهده</button>
                                                    </td>
                                                </tr>
                                                <tr class="collapse" id="audit-log-{{ $log->id }}">
                                                    <td colspan="7">
                                                        <div class="row g-3">
                                                            <div class="col-12 col-lg-6">
                                                                <div class="border rounded p-3 h-100">
                                                                    <div class="fw-semibold mb-2">تغییرات</div>
                                                                    <pre class="mb-0 small text-wrap" dir="ltr">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-lg-6">
                                                                <div class="border rounded p-3 h-100">
                                                                    <div class="fw-semibold mb-2">متادیتا و دسترسی‌ها
                                                                    </div>
                                                                    <pre class="mb-0 small text-wrap" dir="ltr">{{ json_encode(['metadata' => $log->metadata, 'actor_permissions' => $log->actor_permissions], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">هنوز لاگی
                                                        ثبت نشده است.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $logs->links() }}
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
        $('.audit-logs').addClass('active').removeClass('open');
    </script>
</body>

</html>
