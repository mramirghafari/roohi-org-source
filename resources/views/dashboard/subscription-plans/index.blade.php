<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>تعریف اشتراک‌ها - روحی بات</title>
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
                                <h4 class="mb-1"><span class="text-muted fw-light">مدیریت /</span> تعریف اشتراک‌ها
                                </h4>
                                <p class="text-muted mb-0">پلن‌ها، قیمت درگاه و تنظیمات کارت به کارت اشتراک‌ها را مدیریت
                                    کنید.</p>
                            </div>
                            <a href="{{ route('subscription-plans.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> تعریف اشتراک جدید
                            </a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="row g-4 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">کل اشتراک‌ها</span>
                                        <h3 class="mb-0 mt-2">{{ number_format($plans->count()) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">اشتراک‌های فعال</span>
                                        <h3 class="mb-0 mt-2">
                                            {{ number_format($plans->where('is_active', true)->count()) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">دارای کارت به کارت</span>
                                        <h3 class="mb-0 mt-2">
                                            {{ number_format($plans->where('card_to_card_enabled', true)->count()) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3">لیست اشتراک‌ها</h6>
                                <div class="table-responsive text-nowrap">
                                    <table class="table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>عنوان</th>
                                                <th>مدت</th>
                                                <th>قیمت درگاه</th>
                                                <th>کارت به کارت</th>
                                                <th>وضعیت</th>
                                                <th>ترتیب</th>
                                                <th>عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($plans as $plan)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            @if ($plan->icon_path)
                                                                <img src="{{ asset('storage/' . $plan->icon_path) }}"
                                                                    alt="{{ $plan->title }}" class="rounded border"
                                                                    style="width: 42px; height: 42px; object-fit: contain;">
                                                            @endif
                                                            <div>
                                                                <div class="fw-semibold">{{ $plan->title }}</div>
                                                                <small
                                                                    class="text-muted">{{ $plan->description ?: 'بدون توضیحات' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ number_format((int) $plan->duration_months) }} ماه</td>
                                                    <td>
                                                        @if ($plan->gateway_enabled)
                                                            {{ number_format((int) $plan->gateway_price) }} تومان
                                                        @else
                                                            <span class="badge bg-label-secondary">غیرفعال</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($plan->card_to_card_enabled)
                                                            <div>{{ number_format((int) $plan->card_to_card_price) }}
                                                                تومان</div>
                                                            <small class="text-muted">
                                                                {{ number_format(count($plan->card_accounts ?? [])) }}
                                                                کارت تعریف شده
                                                            </small>
                                                        @else
                                                            <span class="badge bg-label-secondary">غیرفعال</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($plan->is_active)
                                                            <span class="badge bg-label-success">فعال</span>
                                                        @else
                                                            <span class="badge bg-label-secondary">غیرفعال</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format((int) $plan->sort_order) }}</td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ route('subscription-plans.edit', $plan) }}"
                                                                class="btn btn-sm btn-info">ویرایش</a>
                                                            <form
                                                                action="{{ route('subscription-plans.destroy', $plan) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('این اشتراک حذف شود؟')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-label-danger">حذف</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">هنوز اشتراکی
                                                        تعریف نشده است.</td>
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
        $('.subscription-plans').addClass('active').removeClass('open');
    </script>
</body>

</html>
