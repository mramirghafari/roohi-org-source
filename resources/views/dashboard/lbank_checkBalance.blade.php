<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>بررسی UID کاربر - روحی بات</title>
    <meta content="" name="description" />
    <!-- Favicon -->
    <link href="{{ asset('/dashboard_theme') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <!-- Icons -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/flag-icons.css" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/css/demo.css" rel="stylesheet" />
    <!-- Vendors CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/node-waves/node-waves.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/typeahead-js/typeahead.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/select2/select2.css" rel="stylesheet" />
    <!-- Page CSS -->
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
                        <div class="row">
                            <div class="col-12">
                                <form method="POST" action="{{ route('lbank.checkBalance.process') }}">
                                    @csrf
                                    <div class="card">
                                        <div
                                            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                                            <h5 class="card-title mb-sm-0 me-2">بررسی UID کاربر</h5>
                                            <div class="action-btns">
                                                <button class="btn btn-primary">بررسی</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-8 mx-auto">

                                                    <h5 class="my-4">یو آیدی وارد کنید:</h5>
                                                    <div class="row g-3 mb-5">
                                                        <div class="col-12">
                                                            <input class="form-control" name="uid"
                                                                placeholder="LBA6H53539"
                                                                value="{{ isset($uid) ? $uid : '' }}" type="text" />
                                                        </div>
                                                    </div>

                                                    @if (isset($uid))
                                                        <h5 class="my-4">نتیجه: </h5>
                                                        <div class="row g-3">
                                                            <div class="col-12 col-md-6">
                                                                <div class="info-container">
                                                                    <ul class="list-unstyled">
                                                                        <li class="mb-2">
                                                                            <span class="fw-medium me-1">وضعیت درخواست:
                                                                            </span>
                                                                            @if ($success)
                                                                                <span
                                                                                    class="badge bg-success">موفق</span>
                                                                            @else
                                                                                <span class="badge bg-danger">نا
                                                                                    موفق</span>
                                                                            @endif

                                                                        </li>
                                                                        <li class="mb-2 pt-1">
                                                                            <span class="fw-medium me-1">زیرمجموعه روحی:
                                                                            </span>
                                                                            @if ($inTeam)
                                                                                <span
                                                                                    class="badge bg-label-success">بله</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-label-danger">خیر</span>
                                                                            @endif
                                                                        </li>
                                                                        <li class="mb-2 pt-1">
                                                                            <span class="fw-medium me-1">تا به حال شارژ
                                                                                حساب داشته؟:</span>
                                                                            @if ($deposit)
                                                                                <span
                                                                                    class="badge bg-label-success">بله</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-label-danger">خیر</span>
                                                                            @endif

                                                                        </li>
                                                                        <li class="mb-2 pt-1">
                                                                            <span class="fw-medium me-1">تا به حال
                                                                                معامله باز کرده؟:</span>
                                                                            @if ($trade)
                                                                                <span
                                                                                    class="badge bg-label-success">بله</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-label-danger">خیر</span>
                                                                            @endif

                                                                        </li>
                                                                        <li class="mb-2 pt-1">
                                                                            <span class="fw-medium me-1">وضعیت احراز
                                                                                هویت KYC روی ال بانک؟</span>
                                                                            @if ($kycStatus)
                                                                                <span
                                                                                    class="badge bg-label-success">انجام
                                                                                    شده</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-label-danger">انجام
                                                                                    نشده</span>
                                                                            @endif

                                                                        </li>
                                                                        <li class="mb-2 pt-1">
                                                                            <span class="fw-medium me-1">موجودی
                                                                                اسپات:</span>
                                                                            <span>{{ number_format($spot) }} </span>
                                                                        </li>

                                                                        <li class="mb-2 pt-1">
                                                                            <span class="fw-medium me-1">موجودی
                                                                                فیوچرز:</span>
                                                                            <span>{{ number_format($futures) }} </span>
                                                                        </li>

                                                                        <li class="mb-2 pt-1">
                                                                            <span class="fw-medium me-1">موجودی
                                                                                مجموع:</span>
                                                                            <span>{{ number_format($total) }} </span>
                                                                        </li>

                                                                        <li class="mb-2 pt-1">
                                                                            <span class="fw-medium me-1">بونوس: </span>
                                                                            @if ($bonus)
                                                                                <span
                                                                                    class="badge bg-label-success">دریافت
                                                                                    شده</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-label-danger">دریافت
                                                                                    نشده</span>
                                                                            @endif
                                                                        </li>

                                                                        <li class="mb-2 pt-1">
                                                                            <span class="fw-medium me-1">مجموع با بونوس:
                                                                            </span>
                                                                            <span>{{ number_format($spotUsdt) }}
                                                                            </span>
                                                                        </li>


                                                                    </ul>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5 class="my-4">اطلاعات کاربر در روحی بات:</h5>
                                                                @if ($UserBot)
                                                                    <ul class="list-unstyled"></ul>
                                                                    <li class="mb-2">
                                                                        <span class="fw-medium me-1">نام :</span>
                                                                        <span>{{ $UserBot->nam }}</span>
                                                                    </li>
                                                                    <li class="mb-2">
                                                                        <span class="fw-medium me-1">موبایل:</span>
                                                                        <span>{{ $UserBot->mobile }}</span>
                                                                    </li>
                                                                    <li class="mb-2">
                                                                        <span class="fw-medium me-1">تلگرام:</span>
                                                                        <span>{{ $UserBot->name . ' ' . $UserBot->last_name }}</span>
                                                                    </li>
                                                                    <li class="mb-2">
                                                                        <span class="fw-medium me-1">نام کاربری:</span>
                                                                        <span>{{ $UserBot->username }}</span>
                                                                    </li>
                                                                    <li class="mb-2">
                                                                        <span class="fw-medium me-1">صفحه پروفایل
                                                                            کاربر:</span>
                                                                        <a href="{{ route('users.detail', $UserBot) }}"
                                                                            class="btn btn-info btn-sm">مشاهده
                                                                            جزئیات</a>
                                                                    </li>

                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /Sticky Actions -->
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
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/select2/i18n/fa.js"></script>
    <!-- Main JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <!-- Page JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/form-layouts.js"></script>
</body>

</html>
