<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>مدیریت کمپین‌های پرداخت - روحی بات</title>
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
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                            <div>
                                <h4 class="mb-1"><span class="text-muted fw-light">مدیریت /</span> کمپین‌های پرداخت</h4>
                                <p class="text-muted mb-0">کمپین‌ها، ظرفیت، قیمت و ثبت‌نام‌های پرداخت‌شده را مدیریت کنید.</p>
                            </div>
                            <a href="{{ route('payment-campaigns.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> ایجاد کمپین جدید
                            </a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="row g-4 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">کل کمپین‌ها</span>
                                        <h3 class="mb-0 mt-2">{{ number_format($campaigns->count()) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">ثبت‌نام موفق</span>
                                        <h3 class="mb-0 mt-2">{{ number_format((int) $campaigns->sum('registrations_success_count')) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">در انتظار پرداخت</span>
                                        <h3 class="mb-0 mt-2">{{ number_format((int) $campaigns->sum('registrations_pending_count')) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3">لیست کمپین‌ها</h6>
                                <div class="table-responsive text-nowrap">
                                    <table class="table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>عنوان</th>
                                                <th>قیمت</th>
                                                <th>ظرفیت</th>
                                                <th>ثبت‌نام‌ها</th>
                                                <th>وضعیت</th>
                                                <th>لینک پرداخت</th>
                                                <th>عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($campaigns as $campaign)
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold">{{ $campaign->title }}</div>
                                                        <small class="text-muted">{{ $campaign->slug }}</small>
                                                    </td>
                                                    <td>
                                                        <div>{{ number_format((int) $campaign->current_price) }} تومان</div>
                                                        <small class="text-muted">
                                                            قیمت اصلی: {{ number_format((int) $campaign->original_price) }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if ($campaign->capacity)
                                                            {{ number_format((int) $campaign->registrations_success_count) }}
                                                            /
                                                            {{ number_format((int) $campaign->capacity) }}
                                                        @else
                                                            نامحدود
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-label-success">موفق {{ number_format((int) $campaign->registrations_success_count) }}</span>
                                                        <span class="badge bg-label-warning">در انتظار {{ number_format((int) $campaign->registrations_pending_count) }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($campaign->is_active)
                                                            <span class="badge bg-label-success">فعال</span>
                                                        @else
                                                            <span class="badge bg-label-secondary">غیرفعال</span>
                                                        @endif
                                                        @if ($campaign->is_full)
                                                            <span class="badge bg-label-danger">تکمیل ظرفیت</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('payment-campaigns.register.form', $campaign) }}"
                                                            class="btn btn-sm btn-label-primary" target="_blank">
                                                            <i class="ti ti-external-link"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('payment-campaigns.show', $campaign) }}"
                                                            class="btn btn-sm btn-info">جزئیات</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">هنوز کمپینی ثبت نشده است.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
        $('.users').addClass('active open');
        $('.user-camps').addClass('active').removeClass('open');
    </script>
</body>

</html>
