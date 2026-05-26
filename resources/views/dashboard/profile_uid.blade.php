<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ env('DASHBOARD_THEME_PATH') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>اتصال UID به حساب من - روحی بات</title>
    <meta content="" name="description" />
    <!-- Favicon -->
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/img/favicon/favicon.ico" rel="icon"
        type="image/x-icon" />
    <!-- Icons -->
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/demo.css" rel="stylesheet" />
    <!-- Vendors CSS -->

    <!-- Helpers -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/rtl.css" rel="stylesheet" />
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Navbar -->
            @include('dashboard.sections.navbar')
            <!-- / Navbar -->
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
                        <h4 class="py-3 mb-4">
                            <span class="text-muted fw-light">حساب کاربری /</span>
                            و ثبت و اتصال UID
                        </h4>
                        <div class="row g-4">
                            @if (session('success'))
                                <p class="col-12 alert alert-success">{{ session('success') }}</p>
                            @endif
                            @if (session('error'))
                                <p class="col-12 alert alert-danger">{{ session('error') }}</p>
                            @endif
                            <!-- Navigation -->
                            <div class="col-12 col-lg-4">
                                <div class="d-flex justify-content-between flex-column mb-3 mb-md-0">
                                    <ul class="nav nav-align-left nav-pills flex-column">
                                        <li class="nav-item mb-1">
                                            <a class="nav-link py-2" href="{{ route('dashboard.profile') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit-circle me-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3" />
                                                    <path d="M16 5l3 3" />
                                                    <path d="M9 7.07a7 7 0 0 0 1 13.93a7 7 0 0 0 6.929 -6" />
                                                </svg>
                                                <span class="align-middle">ویرایش حساب</span>
                                            </a>
                                        </li>

                                        <li class="nav-item mb-1">
                                            <a class="nav-link py-2 active"
                                                href="{{ route('dashboard.profile.uid') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-settings me-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 14a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                    <path d="M12 10.5v1.5" />
                                                    <path d="M12 16v1.5" />
                                                    <path d="M15.031 12.25l-1.299 .75" />
                                                    <path d="M10.268 15l-1.3 .75" />
                                                    <path d="M15 15.803l-1.285 -.773" />
                                                    <path d="M10.285 12.97l-1.285 -.773" />
                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                    <path
                                                        d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                                                </svg>
                                                <span class="align-middle">ثبت UID</span>
                                            </a>
                                        </li>

                                        <li class="nav-item mb-1">
                                            <a class="nav-link py-2" href="{{ route('dashboard.profile.api_set') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-link-plus me-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 15l6 -6" />
                                                    <path
                                                        d="M11 6l.463 -.536a5 5 0 0 1 7.072 0a4.993 4.993 0 0 1 -.001 7.072" />
                                                    <path
                                                        d="M12.603 18.534a5.07 5.07 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                    <path d="M16 19h6" />
                                                    <path d="M19 16v6" />
                                                </svg>
                                                <span class="align-middle">اتصال وبسرویس ها</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link py-2" href="#">
                                                <i class="ti ti-bell-ringing me-2"></i>
                                                <span class="align-middle">نوتیفیکیشن</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /Navigation -->
                            <!-- Options -->
                            <div class="col-12 col-lg-8 pt-4 pt-lg-0">
                                <div class="tab-content p-0">
                                    <!-- Store Details Tab -->
                                    <form method="POST" action="{{ route('dashboard.profile.update_uid') }}">
                                        @csrf
                                        <div class="tab-pane fade show active" id="store_details" role="tabpanel">
                                            <div class="card mb-4">
                                                <div class="card-header">
                                                    <h5 class="card-title m-0">اتصال به LBank</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-3 g-3">

                                                        <div class="col-12">
                                                            <label class="form-label mb-0"
                                                                for="lbank_uid">LBANK-UID</label>
                                                            <input aria-label="LBANK-UID" class="form-control"
                                                                id="lbank_uid" name="lbank_uid"
                                                                placeholder="LBANK-UID"
                                                                value="{{ auth()->user()->lbank_uid ? auth()->user()->lbank_uid : '' }}"
                                                                {{ auth()->user()->lbank_uid ? '' : '' }}
                                                                type="text" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="card mb-4">
                                                <div class="card-header">
                                                    <h5 class="card-title m-0">اتصال به کوین لوکالی</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-3 g-3">

                                                        <div class="col-12">
                                                            <label class="form-label mb-0"
                                                                for="coincall_uid">CoinCall-UID</label>
                                                            <input aria-label="CoinCall-UID" class="form-control"
                                                                id="coincall_uid" name="coincall_uid"
                                                                placeholder="CoinCall-UID"
                                                                value="{{ auth()->user()->coincall_uid ? auth()->user()->coincall_uid : '' }}"
                                                                {{ auth()->user()->coincall_uid ? '' : '' }}
                                                                type="text" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end gap-3">

                                                @if (auth()->user()->coincall_uid == null || auth()->user()->lbank_uid == null)
                                                    <button type="submit"
                                                        class="btn btn-primary waves-effect waves-light">بررسی و ثبت
                                                        اتصال</button>
                                                @else
                                                    <button type="button" disabled
                                                        class="btn btn-success waves-effect waves-light">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="currentColor"
                                                            class="icon icon-tabler icons-tabler-filled icon-tabler-rosette-discount-check">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M12.01 2.011a3.2 3.2 0 0 1 2.113 .797l.154 .145l.698 .698a1.2 1.2 0 0 0 .71 .341l.135 .008h1a3.2 3.2 0 0 1 3.195 3.018l.005 .182v1c0 .27 .092 .533 .258 .743l.09 .1l.697 .698a3.2 3.2 0 0 1 .147 4.382l-.145 .154l-.698 .698a1.2 1.2 0 0 0 -.341 .71l-.008 .135v1a3.2 3.2 0 0 1 -3.018 3.195l-.182 .005h-1a1.2 1.2 0 0 0 -.743 .258l-.1 .09l-.698 .697a3.2 3.2 0 0 1 -4.382 .147l-.154 -.145l-.698 -.698a1.2 1.2 0 0 0 -.71 -.341l-.135 -.008h-1a3.2 3.2 0 0 1 -3.195 -3.018l-.005 -.182v-1a1.2 1.2 0 0 0 -.258 -.743l-.09 -.1l-.697 -.698a3.2 3.2 0 0 1 -.147 -4.382l.145 -.154l.698 -.698a1.2 1.2 0 0 0 .341 -.71l.008 -.135v-1l.005 -.182a3.2 3.2 0 0 1 3.013 -3.013l.182 -.005h1a1.2 1.2 0 0 0 .743 -.258l.1 -.09l.698 -.697a3.2 3.2 0 0 1 2.269 -.944zm3.697 7.282a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                                                        </svg>
                                                        متصل
                                                        شده</button>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /Options-->
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
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <!-- Main JS -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/main.js"></script>
    <!-- Page JS -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/app-ecommerce-settings.js"></script>
</body>

</html>
