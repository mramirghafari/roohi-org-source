<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>بررسی خرید اشتراک - روحی بات</title>
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
                                <h4 class="mb-1"><span class="text-muted fw-light">مدیریت /</span> بررسی خرید اشتراک
                                </h4>
                                <p class="text-muted mb-0">پرداخت‌های کارت به کارت اینجا در صف بررسی می‌مانند و فقط بعد
                                    از تایید، اشتراک فعال می‌شود.</p>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="row g-4 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">کل درخواست‌ها</span>
                                        <h3 class="mb-0 mt-2">{{ number_format($transactions->count()) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">در انتظار بررسی</span>
                                        <h3 class="mb-0 mt-2">
                                            {{ number_format($transactions->where('status', 'pending')->count()) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">تایید شده</span>
                                        <h3 class="mb-0 mt-2">
                                            {{ number_format($transactions->where('status', 'success')->count()) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3">درخواست‌های کارت به کارت</h6>
                                <div class="table-responsive text-nowrap">
                                    <table class="table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>کاربر</th>
                                                <th>اشتراک</th>
                                                <th>مبلغ</th>
                                                <th>اطلاعات پرداخت</th>
                                                <th>وضعیت</th>
                                                <th>زمان ثبت</th>
                                                <th>عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transactions as $transaction)
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold">
                                                            {{ $transaction->user?->nam ?: 'کاربر #' . $transaction->user_id }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $transaction->user?->mobile ?: '-' }}</small>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            {{ $transaction->subscriptionPlan?->title ?: $transaction->plan_months . ' ماهه' }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ number_format((int) $transaction->plan_months) }}
                                                            ماه</small>
                                                    </td>
                                                    <td>{{ number_format((int) $transaction->amount) }} تومان</td>
                                                    <td>
                                                        <div>کارت واریزکننده:
                                                            {{ $transaction->payer_card_number ?: '-' }}</div>
                                                        <small class="text-muted">
                                                            زمان پرداخت:
                                                            {{ $transaction->manual_paid_at ? verta($transaction->manual_paid_at)->format('H:i - d F Y') : '-' }}
                                                        </small>
                                                        @if ($transaction->receipt_path)
                                                            <div class="mt-2">
                                                                <a href="{{ route('subscription-payments.receipt', $transaction) }}"
                                                                    class="btn btn-sm btn-label-primary">دانلود رسید</a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($transaction->status === 'success')
                                                            <span class="badge bg-label-success">تایید شده</span>
                                                        @elseif($transaction->status === 'pending')
                                                            <span class="badge bg-label-warning">در انتظار</span>
                                                        @elseif($transaction->status === 'cancel')
                                                            <span class="badge bg-label-secondary">رد شده</span>
                                                        @else
                                                            <span
                                                                class="badge bg-label-danger">{{ $transaction->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ verta($transaction->created_at)->format('H:i - d F Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($transaction->status === 'pending')
                                                            <div class="d-flex gap-2">
                                                                <form
                                                                    action="{{ route('subscription-payments.approve', $transaction) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('این پرداخت تایید و اشتراک فعال شود؟')">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-success">تایید و
                                                                        فعال‌سازی</button>
                                                                </form>
                                                                <form
                                                                    action="{{ route('subscription-payments.reject', $transaction) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('این درخواست رد شود؟')">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-label-danger">رد</button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">بررسی شده</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">هنوز درخواست
                                                        کارت به کارتی ثبت نشده است.</td>
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
        $('.subscription-payments').addClass('active').removeClass('open');
    </script>
</body>

</html>
