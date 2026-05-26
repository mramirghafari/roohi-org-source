<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact"
    data-assets-path="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>تیکت‌های من</title>
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="m-0">تیکت‌های من</h4>
                            <a href="{{ route('tickets.create') }}" class="btn btn-primary">ثبت تیکت جدید</a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="card">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>موضوع</th>
                                            <th>بخش</th>
                                            <th>اولویت</th>
                                            <th>وضعیت</th>
                                            <th>مسئول</th>
                                            <th>آخرین بروزرسانی</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                            $priorityLabels = \App\Models\Ticket::PRIORITIES;
                                        @endphp
                                        @forelse($tickets as $ticket)
                                            <tr>
                                                <td>{{ $ticket->id }}</td>
                                                <td>{{ $ticket->subject }}</td>
                                                <td>{{ \App\Models\Ticket::DEPARTMENTS[$ticket->department] ?? $ticket->department }}
                                                </td>
                                                <td>{{ $priorityLabels[$ticket->priority] ?? $ticket->priority }}</td>
                                                <td>
                                                    <span
                                                        class="{{ $statusBadgeClasses[$ticket->status] ?? 'badge bg-label-secondary' }}">
                                                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                                    </span>
                                                </td>
                                                <td>{{ $ticket->assignee?->nam ?? ($ticket->assignee?->name ?? '-') }}
                                                </td>
                                                <td>{{ $ticket->updated_at?->format('Y-m-d H:i') }}</td>
                                                <td><a href="{{ route('tickets.show', $ticket->tracking_code) }}"
                                                        class="btn btn-sm btn-info">مشاهده</a></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">هنوز تیکتی ثبت نشده است.</td>
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
            $('.tickets').addClass('active');
        </script>
    </div>
</body>

</html>
