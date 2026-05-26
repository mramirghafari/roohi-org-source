<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact"
    data-assets-path="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>تیکت‌های پشتیبانی</title>
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/demo.css" rel="stylesheet" />
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/config.js"></script>
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/rtl.css" rel="stylesheet" />
</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            @include('dashboard.sections.navbar')
            <div class="layout-page">
                <div class="content-wrapper">
                    @include('dashboard.sections.aside')
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @php
                            $statusLabels = [
                                'open' => 'در انتظار',
                                'answered_by_user' => 'پاسخ کاربر',
                                'answered_by_support' => 'پاسخ پشتیبانی',
                                'closed' => 'بسته',
                            ];
                            $statusBadgeClasses = [
                                'open' => 'badge bg-label-warning',
                                'answered_by_user' => 'badge bg-label-info',
                                'answered_by_support' => 'badge bg-label-danger',
                                'closed' => 'badge bg-label-secondary',
                            ];
                        @endphp

                        <h4 class="mb-3">لیست تیکت‌ها (پشتیبانی)</h4>

                        <div class="card mb-3">
                            <div class="card-body">
                                <form method="GET" class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">فیلتر وضعیت</label>
                                        <select name="status" class="form-select">
                                            <option value="">همه</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status }}"
                                                    {{ request('status') == $status ? 'selected' : '' }}>
                                                    {{ $statusLabels[$status] ?? $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">فیلتر بخش</label>
                                        <select name="department" class="form-select">
                                            <option value="">همه</option>
                                            @foreach ($departments as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ request('department') == $key ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button class="btn btn-primary me-2" type="submit">اعمال فیلتر</button>
                                        <a href="{{ route('support.tickets.index') }}"
                                            class="btn btn-label-secondary">حذف فیلتر</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>کاربر</th>
                                            <th>موضوع</th>
                                            <th>بخش</th>
                                            <th>اولویت</th>
                                            <th>وضعیت</th>
                                            <th>مسئول</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tickets as $ticket)
                                            <tr>
                                                <td>{{ $ticket->id }}</td>
                                                <td>{{ $ticket->user?->nam ?? ($ticket->user?->name ?? '-') }}</td>
                                                <td>{{ $ticket->subject }}</td>
                                                <td>{{ $departments[$ticket->department] ?? $ticket->department }}</td>
                                                <td>{{ \App\Models\Ticket::PRIORITIES[$ticket->priority] ?? $ticket->priority }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="{{ $statusBadgeClasses[$ticket->status] ?? 'badge bg-label-secondary' }}">
                                                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                                    </span>
                                                </td>
                                                <td>{{ $ticket->assignee?->nam ?? ($ticket->assignee?->name ?? '-') }}
                                                </td>
                                                <td><a href="{{ route('support.tickets.show', $ticket) }}"
                                                        class="btn btn-sm btn-info">ورود</a></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">تیکتی یافت نشد.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-3">{{ $tickets->links() }}</div>
                        @include('dashboard.sections.footer')
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/libs/jquery/jquery.js"></script>
        <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/bootstrap.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
        <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/main.js"></script>
        <script>
            $('.tickets_admin').addClass('active');
        </script>
    </div>
</body>

</html>
