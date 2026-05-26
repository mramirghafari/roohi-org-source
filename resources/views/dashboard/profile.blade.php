<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ env('DASHBOARD_THEME_PATH') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>حساب من - روحی بات</title>
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
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/jalalidatepicker.min.css" rel="stylesheet" />

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
                            ویرایش حساب
                        </h4>
                        <div class="row g-4">
                            <!-- Navigation -->
                            <div class="col-12 col-lg-4">
                                <div class="d-flex justify-content-between flex-column mb-3 mb-md-0">
                                    <ul class="nav nav-align-left nav-pills flex-column">
                                        <li class="nav-item mb-1">
                                            <a class="nav-link py-2 active" href="{{ route('dashboard.profile') }}">
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
                                            <a class="nav-link py-2" href="{{ route('dashboard.profile.uid') }}">
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
                                    <div class="tab-pane fade show active" id="store_details" role="tabpanel">
                                        <form method="POST" action="{{ route('dashboard.profile.update') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card mb-4">
                                                <div class="card-header">
                                                    <h5 class="card-title m-0">اطلاعات حساب</h5>
                                                    <p>کاربر گرامی، توجه داشته باشید در صورت ثبت اطلاعات حساب کاربری
                                                        خود،
                                                        امکان تغییر آن وجود ندارد. لطفا قبل از ثبت از صحت اطلاعات وارد
                                                        شده
                                                        اطمینان حاصل نمایید.</p>
                                                </div>
                                                <div class="card-body">

                                                    <div class="row mb-3 g-3">
                                                        <div class="col-12">
                                                            <span class="mb-0 me-5">جنسیت: </span>
                                                            <label class="d-inline-block me-5">
                                                                آقا
                                                                <input type="radio" name="gender" value="1"
                                                                    {{ auth()->user()->gender == 1 ? 'checked' : '' }} />
                                                            </label>
                                                            <label>
                                                                خانم
                                                                <input type="radio" name="gender" value="2"
                                                                    {{ auth()->user()->gender == 2 ? 'checked' : '' }} />
                                                            </label>
                                                            @if ($errors->has('gender'))
                                                                <div class="text-danger mt-1">
                                                                    لطفا جنسیت خود را انتخاب کنید.
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <label class="form-label mb-0" for="nam">نام و نام
                                                                خانوادگی</label>
                                                            <input aria-label="نام شما" class="form-control"
                                                                id="nam" name="nam" placeholder="نام شما"
                                                                value="{{ auth()->user()->nam ? auth()->user()->nam : '' }}"
                                                                {{ auth()->user()->name ? 'readonly' : '' }}
                                                                type="text" />
                                                            @if ($errors->has('nam'))
                                                                <div class="text-danger mt-1">
                                                                    لطفا نام خود را وارد کنید.
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <label class="form-label mb-0" for="mobile">موبایل
                                                                شما</label>
                                                            <input aria-label="موبایل شما" class="form-control"
                                                                id="mobile" name="mobile"
                                                                placeholder="موبایل شما"
                                                                value="{{ auth()->user()->mobile ? auth()->user()->mobile : '' }}"
                                                                {{ auth()->user()->mobile ? 'disabled' : '' }}
                                                                type="text" />
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <label class="form-label mb-0" for="birthdate">تاریخ
                                                                تولد</label>
                                                            <input aria-label="تاریخ تولد" class="form-control"
                                                                id="birthdate" name="birthdate"
                                                                placeholder="تاریخ تولد"
                                                                value="{{ auth()->user()->birthdate ? auth()->user()->birthdate : '' }}"
                                                                {{ auth()->user()->birthdate ? 'readonly' : '' }}
                                                                type="text" data-jdp />
                                                            @if ($errors->has('birthdate'))
                                                                <div class="text-danger mt-1">
                                                                    لطفا تاریخ تولد خود را وارد کنید.
                                                                </div>
                                                            @endif
                                                        </div>

                                                    </div>


                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end gap-3">

                                                <button class="btn btn-primary waves-effect waves-light"
                                                    type="submit">بروزرسانی
                                                    پروفایل</button>
                                            </div>
                                        </form>
                                    </div>
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

    <!-- Main JS -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/main.js"></script>
    <!-- Page JS -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/app-ecommerce-settings.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/jalalidatepicker.min.js"></script>
    <script>
        jalaliDatepicker.startWatch();
    </script>
</body>

</html>
