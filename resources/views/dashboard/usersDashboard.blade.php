<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>پیشخوان ربات - روحی بات</title>
    <meta content="" name="description" />
    <!-- Favicon -->
    <link href="{{ asset('/dashboard_theme') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <!-- Icons -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/css/demo.css" rel="stylesheet" />
    <!-- Vendors CSS -->

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
            @include('dashboard.sections.navbar')
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
                            <span class="text-muted fw-light">مدیریت کاربران سایت</span>
                        </h4>
                        <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="card p-3">
                                    <a class="btn {{ Request::routeIs(['users.index']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect waves-light"
                                        href="{{ route('users.index') }}">پیشخوان</a>
                                    <a class="btn {{ Request::routeIs(['users.all']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect"
                                        href="{{ route('users.all') }}">کل کاربران</a>
                                    <a class="btn {{ Request::routeIs(['users.activeUsers']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect"
                                        href="{{ route('users.activeUsers') }}">کاربران فعال</a>
                                    <a class="btn {{ Request::routeIs(['users.deactiveUsers']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect"
                                        href="{{ route('users.deactiveUsers') }}">کاربران غیرفعال</a>
                                    <a class="btn {{ Request::routeIs(['users.leftUsers']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect"
                                        href="{{ route('users.leftUsers') }}">کاربران رفته</a>

                                    <a class="btn {{ Request::routeIs(['userSearch']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect"
                                        href="{{ route('userSearch') }}">جستجوی کاربر</a>
                                </div>
                            </div>
                            <div class="col-12
        col-md-9">
                                <div class="row text-nowrap">
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="card-icon mb-3">
                                                    <div class="avatar">
                                                        <div class="avatar-initial rounded bg-label-primary">
                                                            <i class="ti ti-currency-dollar ti-md"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h4 class="card-title mb-3">کل کاربران</h4>
                                                    <div class="d-flex align-items-baseline mb-1 gap-1">
                                                        <h4 class="text-primary mb-0">
                                                            {{ number_format($AllUsers) }} نفر
                                                        </h4>

                                                    </div>
                                                    <p class="text-muted mb-0 text-truncate">کل کاربران ربات</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="card-icon mb-3">
                                                    <div class="avatar">
                                                        <div class="avatar-initial rounded bg-label-success">
                                                            <i class="ti ti-gift ti-md"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h4 class="card-title mb-3">کاربران فعال</h4>
                                                    <span class="badge bg-label-success mb-2">اشتراک ویژه</span>
                                                    <p class="text-muted mb-0">{{ number_format($ActiveUsers) }} نفر
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-icon mb-3">
                                                    <div class="avatar">
                                                        <div class="avatar-initial rounded bg-label-warning">
                                                            <i class="ti ti-star ti-md"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h4 class="card-title mb-3">کاربران رفته</h4>
                                                    <div class="d-flex align-items-baseline mb-1 gap-1">
                                                        <h4 class="text-warning mb-0">
                                                            {{ number_format($deactiveUsers) }}</h4><br />
                                                        <p class="mb-0">اعضایی که اشتراکشون تموم شده</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-icon mb-3">
                                                    <div class="avatar">
                                                        <div class="avatar-initial rounded bg-label-info">
                                                            <i class="ti ti-discount ti-md"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h4 class="card-title mb-3">ادمین ها</h4>
                                                    <div class="d-flex align-items-baseline mb-1 gap-1">
                                                        <h4 class="text-info mb-0">{{ $adminUsers }}</h4>
                                                        <p class="mb-0">کدهایی که برنده شده</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-5" />

                        </div>
                        <!-- / Content -->
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
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
        <!-- endbuild -->

        <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
        <script>
            $('.users').addClass('active');
            $(function() {
                var dt_without_ajax_table = $('.datatables-direct-basic');

                // DataTable Direct
                // --------------------------------------------------------------------
                if (dt_without_ajax_table.length) {
                    dt_without_ajax = dt_without_ajax_table.DataTable({
                        searching: true,
                        lengthChange: true,
                        ordering: true,
                        pageLength: 10,
                    });

                }

            });
        </script>
</body>

</html>
