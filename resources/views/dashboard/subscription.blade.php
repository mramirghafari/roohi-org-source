<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>اشتراک من - روحی بات</title>
    <meta content="" name="description" />
    <!-- Favicon -->
    <link href="{{ asset('/dashboard_theme') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <!-- Icons -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.css" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/css/demo.css" rel="stylesheet" />

    <!-- Helpers -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ asset('/dashboard_theme') }}/assets/css/rtl.css" rel="stylesheet" />
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Navbar -->
            @include('dashboard.sections.navbar')
            <!-- / Navbar -->
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Menu -->
                    @include('dashboard.sections.aside')
                    <!-- / Menu -->
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @if (session('payment_message'))
                            <div class="alert @if (session('payment_state') === 'success') alert-success @elseif(session('payment_state') === 'cancel') alert-warning @elseif(session('payment_state') === 'expired') alert-secondary @else alert-danger @endif alert-dismissible"
                                role="alert">
                                {{ session('payment_message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="card">
                            <!-- Pricing Plans -->
                            <div class="pb-sm-5 pb-2 rounded-top">
                                <div class="container py-5">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    @if (is_null($sub) || $status == 'none')
                                                        <div class="mb-2">

                                                            <h4 class="mb-1">شما تا به حال اشتراک نداشته اید.</h4>
                                                            <p>همین حالا اولین اشتراک خود را تهیه کنید.</p>
                                                        </div>
                                                    @endif
                                                    @if ($sub)
                                                        <div class="mb-2 pt-1">
                                                            <h4 class="mb-1">شروع اشتراک از
                                                                {{ verta($sub?->start_vip)->formatWord('l dS F') }}
                                                            </h4>
                                                            <p>پس از انقضای اشتراک برای شما اعلان ارسال می کنیم</p>
                                                        </div>
                                                        @if ($remainingPercent <= 20)
                                                            <div class="alert alert-warning" role="alert">

                                                                <h5 class="alert-heading mb-2">توجه!</h5>
                                                                <span> شما نیاز به تمدید اشتراک دارید</span>

                                                            </div>
                                                        @endif
                                                        @if ($sub)
                                                            <div class="plan-statistics">
                                                                <div class="d-flex justify-content-between">
                                                                    <h6 class="mb-1">روزهای باقی مانده</h6>
                                                                    <h6 class="mb-1">{{ intval($remainingDays) }} از
                                                                        {{ intval($totalDays) }} روز</h6>
                                                                </div>
                                                                <div class="progress mb-1" style="height: 10px">
                                                                    <div aria-valuemax="100" aria-valuemin="0"
                                                                        aria-valuenow="{{ $remainingPercent }}"
                                                                        class="progress-bar @if ($remainingPercent <= 20) bg-danger @elseif($remainingPercent <= 50) bg-warning @else bg-success @endif"
                                                                        style="width: {{ 100 - $remainingPercent . '%' }}"
                                                                        role="progressbar"></div>
                                                                </div>
                                                                <p>{{ $remainingDays }} روز باقی مانده تا اتمام اشتراک
                                                                </p>
                                                            </div>
                                                        @endif
                                                        <div class="mb-3 pt-1">
                                                            <h6 class="mb-1">
                                                                <span class="me-2">تاریخ اتمام اشتراک:
                                                                    {{ verta($sub?->end_vip)->formatWord('l dS F') }}</span>
                                                                {{-- <span class="badge bg-label-primary">تخفیف دار</span> --}}
                                                            </h6>
                                                            {{-- <p>طرح استاندارد برای مشاغل کوچک تا متوسط</p> --}}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="card rounded">
                                                <div
                                                    class="card-body @if ($hasDemoSub || $sub) bg-label-success @else bg-label-warning @endif rounded">
                                                    <h4>🎉 یک ماه اشتراک رایگان هدیه تیم روحی ترید!</h4>
                                                    @if ($hasDemoSub)
                                                        <div class="mb-2">

                                                            <h6 class="mb-1">شما اشتراک هدیه خود را دریافت کرده اید!
                                                            </h6>
                                                            <p>می توانید با خرید اشتراک جدید، اشتراک فعلی خود را تمدید
                                                                کنید.</p>
                                                        </div>
                                                    @else
                                                        <div class="mb-2 pt-1">
                                                            @if ($sub || $hasDemoSub)
                                                                <h6 class="mb-1 text-danger">هدیه اشتراک VIP شما فعال
                                                                    است
                                                                </h6>
                                                            @else
                                                                <h6 class="mb-1 text-danger">شما تا به حال اشتراکی
                                                                    نداشته
                                                                    اید.</h6>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    <div class="mb-2 pt-1">
                                                        <p>در صورت تمایل میتوانید از طرف تیم روحی ترید یک ماه اشتراک
                                                            رایگان VIP دریافت کنید. برای استفاده از این هدیه باید از
                                                            طریق لینک زیر در صرافی ال بانک ثبت نام کنید و با حداقل
                                                            100 دلار دیپازیت از بونوس ویژه ی تیم روحی ترید بهره مند
                                                            شوید و هم اشتراک VIP ربات هوش مصنوعی را دریافت کنید:
                                                        </p>
                                                        <a href="https://lbank.com/ref/ROOHI" target="_blank"
                                                            class="btn btn-primary d-grid w-100 mt-3">ثبت نام در ال
                                                            بانک</a>
                                                        <form action="{{ route('users.getDemoVIP') }}" method="POST"
                                                            class="mt-3">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="lbank_uid" class="form-label">شناسه ال
                                                                    بانک خود را وارد کنید:</label>
                                                                <input type="text"
                                                                    class="form-control @if ($errors->has('lbank_uid') || $errors->has('error')) is-invalid @endif"
                                                                    id="lbank_uid" name="lbank_uid"
                                                                    placeholder="شناسه ال بانک"
                                                                    value="{{ $hasDemoSub ? auth()->user()->lbank_uid : old('lbank_uid') }}"
                                                                    required {{ $hasDemoSub ? 'readonly' : '' }}>
                                                                @if ($errors->has('lbank_uid'))
                                                                    <span class="error text-danger d-inline-block mt-2">
                                                                        {{ $errors->first('lbank_uid') }}
                                                                    </span>
                                                                @elseif ($errors->has('error'))
                                                                    <span class="error text-danger d-inline-block mt-2">
                                                                        {{ $errors->first('error') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <button type="submit" class="btn btn-success d-grid w-100"
                                                                {{ $hasDemoSub ? 'disabled' : '' }}>{{ $hasDemoSub ? 'اشتراک VIP فعال شده است' : 'ثبت شناسه' }}</button>


                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <h2 class="text-center mb-2 mt-3 mt-md-4">پلن‌های خرید اشتراک</h2>
                                        <p class="text-center"> برای خرید پلن های VIP یک اشتراک مناسب با بودجه و خواسته
                                            های
                                            خود انتخاب کنید تا خدمات به شما ارائه شود.</p>

                                        <div class="row mx-0 gy-3 px-lg-5">
                                            <!-- Basic -->
                                            <div class="col-lg mb-md-0 mb-3 mt-0">
                                                <div
                                                    class="card  {{ $planType == 1 ? 'border-primary' : '' }} border rounded shadow-none">
                                                    <div class="card-body">
                                                        @if ($planType == 1)
                                                            <div class="position-absolute end-0 me-4 top-0 mt-4">
                                                                <span class="badge bg-label-primary">فعال</span>
                                                            </div>
                                                        @endif
                                                        <div class="mb-3 pt-2 text-center">
                                                            <img alt="تصویر پایه" height="140"
                                                                src="{{ asset('/dashboard_theme') }}/assets/img/illustrations/page-pricing-basic.png" />
                                                        </div>
                                                        <h3 class="card-title text-center text-capitalize mb-1">یک ماهه
                                                        </h3>
                                                        <p class="text-center">100 دلار با <span
                                                                class="bg-label-danger rounded p-1">تخفیف 10%</span> 90
                                                            دلار. معادل:</p>
                                                        <div class="text-center  mb-2">
                                                            <div class="d-flex justify-content-center">
                                                                <h1 class="display-5 mb-0 text-primary">4,900,000</h1>
                                                                <sup
                                                                    class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">ءتء</sup>
                                                            </div>

                                                        </div>

                                                        <form action="{{ route('subscription.payment.request') }}"
                                                            method="POST" class="mt-3">
                                                            @csrf
                                                            <input type="hidden" name="plan" value="1">
                                                            <button class="btn btn-primary d-grid w-100"
                                                                type="submit">
                                                                {{ $planType == 1 ? 'تمدید اشتراک' : 'خرید اشتراک' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Pro -->
                                            <div class="col-lg mb-md-0 mb-3 mt-0">
                                                <div
                                                    class="card {{ $planType == 2 ? 'border-primary ' : '' }} border shadow-none">
                                                    <div class="card-body position-relative">
                                                        @if ($planType == 2)
                                                            <div class="position-absolute end-0 me-4 top-0 mt-4">
                                                                <span class="badge bg-label-primary">فعال</span>
                                                            </div>
                                                        @endif
                                                        <div class="mb-3 pt-2 text-center">
                                                            <img alt="تصویر استاندارد" height="140"
                                                                src="{{ asset('/dashboard_theme') }}/assets/img/illustrations/page-pricing-standard.png" />
                                                        </div>
                                                        <h3 class="card-title text-center text-capitalize mb-1">دو ماهه
                                                        </h3>
                                                        <p class="text-center">200 دلار با <span
                                                                class="bg-label-danger rounded p-1">تخفیف 15%</span>
                                                            170
                                                            دلار. معادل:</p>
                                                        <div class="text-center  mb-2">
                                                            <div class="d-flex justify-content-center">
                                                                <h1 class="display-5 mb-0 text-primary">8٬800٬000</h1>
                                                                <sup
                                                                    class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">ءتء</sup>
                                                            </div>

                                                        </div>

                                                        <form action="{{ route('subscription.payment.request') }}"
                                                            method="POST" class="mt-3">
                                                            @csrf
                                                            <input type="hidden" name="plan" value="2">
                                                            <button class="btn btn-primary d-grid w-100"
                                                                type="submit">
                                                                {{ $planType == 2 ? 'تمدید اشتراک' : 'خرید اشتراک' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Enterprise -->
                                            <div class="col-lg mt-0">
                                                <div
                                                    class="card  {{ $planType == 3 ? 'border-primary ' : '' }} border rounded shadow-none">
                                                    <div class="card-body">
                                                        @if ($planType == 3)
                                                            <div class="position-absolute end-0 me-4 top-0 mt-4">
                                                                <span class="badge bg-label-primary">فعال</span>
                                                            </div>
                                                        @endif
                                                        <div class="mb-3 pt-2 text-center">
                                                            <img alt="تصویر سازمانی" height="140"
                                                                src="{{ asset('/dashboard_theme') }}/assets/img/illustrations/page-pricing-enterprise.png" />
                                                        </div>
                                                        <h3 class="card-title text-center text-capitalize mb-1">سه ماهه
                                                        </h3>
                                                        <p class="text-center">300 دلار با <span
                                                                class="bg-label-danger rounded p-1">تخفیف 35%</span>
                                                            195
                                                            دلار. معادل:</p>
                                                        <div class="text-center  mb-2">
                                                            <div class="d-flex justify-content-center">
                                                                <h1 class="display-5 mb-0 text-primary">11.700.000</h1>
                                                                <sup
                                                                    class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">ءتء</sup>
                                                            </div>

                                                        </div>

                                                        <form action="{{ route('subscription.payment.request') }}"
                                                            method="POST" class="mt-3">
                                                            @csrf
                                                            <input type="hidden" name="plan" value="3">
                                                            <button class="btn btn-primary d-grid w-100"
                                                                type="submit">
                                                                {{ $planType == 3 ? 'تمدید اشتراک' : 'خرید اشتراک' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="card mb-4 mt-5">
                                            <h5 class="card-header">تاریخچه خرید اشتراک</h5>
                                            <div class="table-responsive">
                                                <table class="table border-top">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-truncate">نوع عملیات</th>
                                                            <th class="text-truncate">وضعیت</th>
                                                            <th class="text-truncate">قیمت (تومان)</th>
                                                            <th class="text-truncate">کد پیگیری</th>
                                                            <th class="text-truncate">تاریخ خرید</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-border-bottom-0">
                                                        @forelse ($subscriptionTransactions as $tx)
                                                            <tr>
                                                                <td class="text-truncate">
                                                                    @if ($tx->plan_months == 1)
                                                                        <i
                                                                            class="ti ti-circle-number-1 text-info me-2 ti-sm"></i>
                                                                        <span class="fw-medium">اشتراک یک ماهه</span>
                                                                    @elseif($tx->plan_months == 2)
                                                                        <i
                                                                            class="ti ti-circle-number-2 text-info me-2 ti-sm"></i>
                                                                        <span class="fw-medium">اشتراک دو ماهه</span>
                                                                    @elseif($tx->plan_months == 3)
                                                                        <i
                                                                            class="ti ti-circle-number-3 text-info me-2 ti-sm"></i>
                                                                        <span class="fw-medium">اشتراک سه ماهه</span>
                                                                    @else
                                                                        <span
                                                                            class="fw-medium">{{ $tx->message ?? 'ثبت دستی اشتراک' }}</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-truncate">
                                                                    @if ($tx->status === 'success')
                                                                        <span class="btn btn-label-success">موفق</span>
                                                                    @elseif($tx->status === 'cancel')
                                                                        <span class="btn btn-label-warning">کنسل</span>
                                                                    @elseif($tx->status === 'expired')
                                                                        <span
                                                                            class="btn btn-label-secondary">منقضی</span>
                                                                    @elseif($tx->status === 'pending')
                                                                        <span class="btn btn-label-info">در
                                                                            انتظار</span>
                                                                    @else
                                                                        <span class="btn btn-label-danger">خطا</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-truncate">
                                                                    {{ number_format((int) $tx->amount) }}
                                                                </td>
                                                                <td class="text-truncate">{{ $tx->ref_id ?? '-' }}
                                                                </td>
                                                                <td class="text-truncate">
                                                                    {{ verta($tx->created_at)->format('H:i - d F Y') }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5"
                                                                    class="text-center text-muted py-4">هنوز تراکنشی
                                                                    ثبت نشده است.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Pricing Plans -->
                            </div>
                        </div>
                        <!--/ Content -->
                        <!-- Footer -->
                        @include('dashboard.sections.footer')
                        <!-- / Footer -->
                        <div class="content-backdrop fade"></div>
                    </div>
                    <!--/ Content wrapper -->
                </div>
                <!--/ Layout container -->
            </div>
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
        <!--/ Layout wrapper -->
        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/jquery/jquery.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/popper/popper.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/bootstrap.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
        <!-- endbuild -->
        <!-- Vendors JS -->
        <!-- Main JS -->
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
        <!-- Page JS -->
        <script src="{{ asset('/dashboard_theme') }}/assets//js/pages-pricing.js"></script>
        <script>
            $('.subscription').addClass('active');
        </script>
        @if (session('demo_vip_success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'تبریک!',
                        text: 'اشتراک VIP یک ماهه برای شما فعال شد!',
                        confirmButtonText: 'باشه',
                        showCancelButton: false,
                        showDenyButton: false,
                        showCloseButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: (popup) => {
                            popup.querySelector('.swal2-cancel')?.remove();
                            popup.querySelector('.swal2-deny')?.remove();
                            popup.querySelector('.swal2-close')?.remove();
                        }
                    });
                });
            </script>
        @endif

</body>

</html>
