<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ env('DASHBOARD_THEME_PATH') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>پیشخوان - روحی بات</title>

    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#000c63">
    <link rel="apple-touch-icon" href="/icons/pwa-192.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Roohi AI">

    <!-- Favicon -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <!-- Icons -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/toastr/toastr.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/animate-css/animate.css" rel="stylesheet" />
    <!-- Vendors CSS -->
    <style>
        .card-body.verify {
            margin-top: 50px;
        }

        @media(max-width: 768px) {
            .card-body.verify {
                padding-bottom: 170px;
                background-size: 170px !important;
                margin-top: 20px;
            }

        }
    </style>

    <!-- Helpers -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/css/rtl.css" rel="stylesheet" />
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
                        <div class="row">
                            @if (session('vip_error'))
                                <p class="col-12 alert alert-danger">{{ session('vip_error') }}</p>
                            @endif

                            <!-- Support Tracker -->
                            <div class="col-12 col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between pb-0">
                                        <div class="card-title mb-0">
                                            <h5 class="mb-0">تکمیل پروفایل و تهیه اشتراک</h5>
                                        </div>

                                    </div>
                                    <div class="card-body verify"
                                        style="background: url('{{ env('DASHBOARD_THEME_PATH') }}/assets/img/auth_vector.jpg') no-repeat left bottom; ">
                                        <div class="row">
                                            <div class="col-12">

                                                <ul class="p-0 m-0">
                                                    <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">

                                                        @if (auth()->user()->nam == null || auth()->user()->gender == null || auth()->user()->birthdate == null)
                                                            <div class="badge rounded bg-label-secondary p-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                                    <path d="M9 12l2 2l4 -4" />
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <div class="badge rounded bg-label-success p-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24"
                                                                    fill="currentColor"
                                                                    class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path
                                                                        d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                                                                </svg>
                                                            </div>
                                                        @endif

                                                        <div>
                                                            <h6 class="mb-0 text-nowrap">ثبت اطلاعات شخصی </h6>
                                                            @if (auth()->user()->nam == null || auth()->user()->gender == null || auth()->user()->birthdate == null)
                                                                <a href="{{ route('dashboard.profile') }}"
                                                                    class="btn btn-sm btn-outline-warning waves-effect">تکمیل
                                                                    پروفایل</a>
                                                            @endif
                                                        </div>
                                                    </li>
                                                    <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">

                                                        @if (auth()->user()->lbank_uid == null && auth()->user()->coincall_uid == null)
                                                            <div class="badge rounded bg-label-secondary p-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                                    <path d="M9 12l2 2l4 -4" />
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <div class="badge rounded bg-label-success p-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24"
                                                                    fill="currentColor"
                                                                    class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path
                                                                        d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                                                                </svg>
                                                            </div>
                                                        @endif

                                                        <div>
                                                            <h6 class="mb-0 text-nowrap">ثبت و اتصال UID</h6>
                                                            @if (auth()->user()->lbank_uid == null && auth()->user()->coincall_uid == null)
                                                                <a href="{{ route('dashboard.profile.uid') }}"
                                                                    class="btn btn-sm btn-outline-warning waves-effect">ثبت
                                                                    UID</a>
                                                            @endif
                                                        </div>
                                                    </li>

                                                    <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">

                                                        @if (auth()->user()->has_active_vip)
                                                            <div class="badge rounded bg-label-success p-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24"
                                                                    fill="currentColor"
                                                                    class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path
                                                                        d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <div class="badge rounded bg-label-secondary p-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                                    <path d="M9 12l2 2l4 -4" />
                                                                </svg>
                                                            </div>
                                                        @endif

                                                        <div>
                                                            <h6 class="mb-0 text-nowrap">خرید اشتراک VIP</h6>
                                                            @if (auth()->user()->has_active_vip == null)
                                                                <a href="{{ route('subscription') }}"
                                                                    class="btn btn-sm btn-outline-warning waves-effect">خرید
                                                                    اشتراک</a>
                                                            @endif
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between pb-0">
                                        <div class="card-title mb-0">
                                            <h5 class="mb-0">وضعیت حساب و اشتراک</h5>

                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mt-lg-4 mt-lg-2 mb-lg-4 mb-2 pt-3">
                                                    @if (auth()->user()->has_active_vip)
                                                        <h1 class="mb-0 lh-80p">
                                                            {{ auth()->user()->vip_remaining_days }}</h1>
                                                        <p class="mb-0">روزهای باقی مانده</p>
                                                    @endif
                                                </div>
                                                <ul class="p-0 m-0">
                                                    @if (auth()->user()->has_active_vip)
                                                        <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                                            <div class="badge rounded bg-label-primary p-1">
                                                                <i class="ti ti-ticket ti-sm"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 text-nowrap">وضعیت اشتراک</h6>
                                                                @if (auth()->user()->has_active_vip)
                                                                    <small
                                                                        class="bg-label-success font-weight-bold p-1 rounded">فعال</small>
                                                                @else
                                                                    <small
                                                                        class="bg-label-danger font-weight-bold p-1 rounded">غیرفعال</small>
                                                                @endif
                                                            </div>
                                                        </li>

                                                        <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                                                            <div class="badge rounded bg-label-info p-1">
                                                                <i class="ti ti-circle-check ti-sm"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 text-nowrap">تاریخچه اشتراک</h6>
                                                                <small
                                                                    class="text-muted">{{ verta(auth()->user()->activeSubscribe->start_vip)->format('Y-m-d') }}</small>
                                                                تا
                                                                <small
                                                                    class="text-muted">{{ verta(auth()->user()->activeSubscribe->exp_vip)->format('Y-m-d') }}</small>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex gap-3 align-items-center pb-1">
                                                            <div class="badge rounded bg-label-warning p-1">
                                                                <i class="ti ti-clock ti-sm"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 text-nowrap">اتصال UID ال بانک</h6>
                                                                @if (auth()->user()->lbank_uid)
                                                                    <small
                                                                        class="bg-label-success rounded p-1 me-2">متصل
                                                                        شده</small>
                                                                    <small
                                                                        class="bg-label-success rounded p-1">{{ auth()->user()->lbank_uid }}</small>
                                                                @else
                                                                    <small
                                                                        class="bg-label-danger rounded p-1 me-2">متصل
                                                                        نشده</small>

                                                                    <a href="{{ route('dashboard.profile.uid') }}">برای
                                                                        اتصال کلیک کنید</a>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="d-flex gap-3 align-items-center pb-1">
                                                            <a href="{{ route('subscription') }}"
                                                                class="alert alert-danger d-block w-100">برای تمدید
                                                                اشتراک اینجا کلیک کنید.</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Support Tracker -->

                            @if (session('deposit_error'))
                                <p class="col-12 alert alert-danger">{{ session('deposit_error') }}</p>
                            @endif

                            @if (auth()->user()->has_active_vip)
                                <div class="col-lg-7 col-12 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                                            <div class="card-title mb-0">
                                                <h5 class="mb-0">گزارش سیگنال های هفته ای</h5>
                                                <small class="text-muted">آمار سیگنال هفتگی</small>
                                            </div>

                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div
                                                    class="col-12 col-md-4 mt-md-10 d-flex flex-column align-self-end">
                                                    <div
                                                        class="d-flex gap-2 align-items-center mb-2 pb-1 flex-wrap pt-3">
                                                        <h1 class="mb-0 ">
                                                            <bdi>{{ $thisWeekVisibleSignals }} سیگنال</bdi>
                                                        </h1>

                                                    </div>
                                                    <small>تعداد سیگنال های ارائه شده در این هفته</small>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div id="weeklyEarningReports"></div>
                                                </div>
                                            </div>
                                            <div class="border rounded p-3 mt-4">
                                                <div class="row gap-4 gap-sm-0">
                                                    <div class="col-12 col-sm-4">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <div class="badge rounded bg-label-primary p-1">
                                                                <i class="ti ti-check ti-sm"></i>
                                                            </div>
                                                            <h6 class="mb-0">تعداد کل</h6>
                                                        </div>
                                                        <h4 class="my-2 pt-1">
                                                            <bdi>{{ $thisWeekVisibleSignals }} سیگنال</bdi>
                                                        </h4>
                                                        <div class="progress w-75" style="height: 4px">
                                                            <div aria-valuemax="100" aria-valuemin="0"
                                                                aria-valuenow="65" class="progress-bar"
                                                                role="progressbar" style="width: 65%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-4">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <div class="badge rounded bg-label-info p-1">
                                                                <i class="ti ti-arrow-up ti-sm"></i>
                                                            </div>
                                                            <h6 class="mb-0">سیگنال های سود</h6>
                                                        </div>
                                                        <h4 class="my-2 pt-1">
                                                            <bdi>{{ $thisWeekVisibleSignalsSood }} سیگنال</bdi>

                                                        </h4>
                                                        <div class="progress w-75" style="height: 4px">
                                                            <div aria-valuemax="100" aria-valuemin="0"
                                                                aria-valuenow="50" class="progress-bar bg-info"
                                                                role="progressbar" style="width: 50%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-4">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <div class="badge rounded bg-label-danger p-1">
                                                                <i class="ti ti-arrow-down ti-sm"></i>
                                                            </div>
                                                            <h6 class="mb-0">سیگنال های ضرر</h6>
                                                        </div>
                                                        <h4 class="my-2 pt-1">
                                                            <bdi>{{ $thisWeekVisibleSignalsZarar }} سیگنال</bdi>

                                                        </h4>
                                                        <div class="progress w-75" style="height: 4px">
                                                            <div aria-valuemax="100" aria-valuemin="0"
                                                                aria-valuenow="65" class="progress-bar bg-danger"
                                                                role="progressbar" style="width: 65%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-5 col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="bg-label-primary rounded-3 text-center mb-3 pt-4">
                                            <img alt="تصویر کارت دختر" class="img-fluid"
                                                src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/illustrations/girl-with-laptop.png"
                                                width="140">
                                        </div>
                                        <h4 class="mb-2 pb-1">دعوت از دوستان</h4>
                                        <p class="small">درآمد دلاری با دعوت از دوستان خود</p>
                                        <div class="row mb-3 g-3">
                                            <div class="col-6">
                                                <div class="d-flex">
                                                    <div class="avatar flex-shrink-0 me-2">
                                                        <span class="avatar-initial rounded bg-label-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-hexagon-number-1">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path
                                                                    d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033" />
                                                                <path d="M10 10l2 -2v8" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-nowrap">{{ $directReferralsCount }} نفر
                                                        </h6>
                                                        <small>تعداد اعضای LEVEL 1</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex">
                                                    <div class="avatar flex-shrink-0 me-2">
                                                        <span class="avatar-initial rounded bg-label-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24"
                                                                fill="currentColor"
                                                                class="icon icon-tabler icons-tabler-filled icon-tabler-hexagon-number-1">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path
                                                                    d="M10.425 1.414a3.33 3.33 0 0 1 3.216 0l6.775 3.995c.067 .04 .127 .084 .18 .133l.008 .007l.107 .076a3.223 3.223 0 0 1 1.284 2.39l.005 .203v7.284c0 1.175 -.643 2.256 -1.623 2.793l-6.804 4.302c-.98 .538 -2.166 .538 -3.2 -.032l-6.695 -4.237a3.226 3.226 0 0 1 -1.678 -2.826v-7.285a3.21 3.21 0 0 1 1.65 -2.808zm.952 5.803l-.084 .076l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114c-.083 -.777 -1.008 -1.16 -1.617 -.67z" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-nowrap">{{ $activeNetworkCount }} نفر
                                                        </h6>
                                                        <small>تعداد اعضای فعال LEVEL 1</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex">
                                                    <div class="avatar flex-shrink-0 me-2">
                                                        <span class="avatar-initial rounded bg-label-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-users-group">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                                                <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                                                                <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-nowrap">{{ $totalNetworkCount }} نفر</h6>
                                                        <small>تعداد زیرمجموعه ها</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex">
                                                    <div class="avatar flex-shrink-0 me-2">
                                                        <span class="avatar-initial rounded bg-label-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-hexagon">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6" />
                                                                <path
                                                                    d="M6.201 18.744a4 4 0 0 1 3.799 -2.744h4a4 4 0 0 1 3.798 2.741" />
                                                                <path
                                                                    d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-nowrap">{{ $activeNetworkCount }} نفر
                                                        </h6>
                                                        <small>تعداد کل زیرمجموعه های فعال</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn btn-primary w-100 waves-effect waves-light copylink"
                                            href="{{ $inviteLink }}"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-link">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M9 15l6 -6" />
                                                <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                <path
                                                    d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                            </svg>
                                            کپی کردن لینک دعوت</a>
                                    </div>
                                </div>
                            </div>



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
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/bootstrap.js"></script>

    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/toastr/toastr.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/toastr/toastr.js"></script>
    <!-- Main JS -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/js/main.js"></script>
    <!-- Page JS -->
    <script>
        $('.dashboard').addClass('active')
    </script>

    <script>
        // کپی کردن لینک دعوت
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.copylink');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const href = btn.getAttribute('href');
            if (!href) return;

            try {
                await navigator.clipboard.writeText(href);
                toastr.success('لینک دعوت با موفقیت کپی شد!', 'موفق', {
                    positionClass: 'toast-bottom-center',
                    timeOut: 3000,
                    progressBar: true,
                    closeButton: true
                });
            } catch (err) {
                console.error('خطا در کپی کردن:', err);
                toastr.error('خطا در کپی کردن لینک', 'خطا', {
                    positionClass: 'toast-bottom-center',
                    timeOut: 3000,
                    progressBar: true,
                    closeButton: true
                });
            }
        });
    </script>
    <script>
        (function() {
            let cardColor, headingColor, labelColor, shadeColor, grayColor;
            if (isDarkStyle) {
                cardColor = config.colors_dark.cardColor;
                labelColor = config.colors_dark.textMuted;
                headingColor = config.colors_dark.headingColor;
                shadeColor = 'dark';
                grayColor = '#5E6692'; // gray color is for stacked bar chart
            } else {
                cardColor = config.colors.cardColor;
                labelColor = config.colors.textMuted;
                headingColor = config.colors.headingColor;
                shadeColor = '';
                grayColor = '#817D8D';
            }

            window.weeklySignalsChart = {
                categories: @json($chartCategories, JSON_UNESCAPED_UNICODE),
                data: @json($chartData)
            };

            const weeklyEarningReportsEl = document.querySelector('#weeklyEarningReports'),
                weeklyEarningReportsConfig = {
                    chart: {
                        height: 202,
                        parentHeightOffset: 0,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            barHeight: '60%',
                            columnWidth: '38%',
                            startingShape: 'rounded',
                            endingShape: 'rounded',
                            borderRadius: 4,
                            distributed: true
                        }
                    },
                    grid: {
                        show: false,
                        padding: {
                            top: -30,
                            bottom: 0,
                            left: -10,
                            right: -10
                        }
                    },
                    colors: [
                        config.colors_label.primary,
                        config.colors_label.primary,
                        config.colors_label.primary,
                        config.colors_label.primary,
                        config.colors.primary,
                        config.colors_label.primary,
                        config.colors_label.primary
                    ],
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        name: 'فروش',
                        data: [40, 65, 50, 45, 90, 55, 70]
                    }],
                    legend: {
                        show: false
                    },
                    xaxis: {
                        categories: ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'],
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '13px',
                                fontFamily: 'font-primary'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            show: false
                        }
                    },
                    tooltip: {
                        enabled: false
                    },
                    responsive: [{
                        breakpoint: 1025,
                        options: {
                            chart: {
                                height: 199
                            }
                        }
                    }]
                };
            if (typeof weeklyEarningReportsEl !== undefined && weeklyEarningReportsEl !== null) {
                const weeklyEarningReports = new ApexCharts(weeklyEarningReportsEl, weeklyEarningReportsConfig);
                weeklyEarningReports.render();
            }

        })();

        // Earning Reports Bar Chart
        // --------------------------------------------------------------------
    </script>

    @include('dashboard.sections.installApp')
</body>

</html>
