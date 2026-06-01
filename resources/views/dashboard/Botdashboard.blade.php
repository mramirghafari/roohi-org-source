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
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css"
        rel="stylesheet" />
    <link
        href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/libs/flatpickr/flatpickr.css" rel="stylesheet" />
    <!-- Row Group CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css"
        rel="stylesheet" />
    <!-- Form Validation -->
    <link
        href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/@form-validation/form-validation.css" rel="stylesheet"/>
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ asset('/dashboard_theme') }}/assets/css/rtl.css" rel="stylesheet"/>
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
                        <span class="text-muted fw-light">پیشخوان ربات /</span>
                        ربات {{ Request::routeIs(['bot1.dashboard']) ? '@AiRoohiBot' : '@RoohiAi_Bot' }}
                    </h4>
                    <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="card">
                                @if ($Bot == 'Bot1')
                                <div class="card-body">
                                    <a class="btn {{ Request::routeIs(['bot1.dashboard']) || Request::routeIs(['bot2.dashboard']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect waves-light" href="{{ route('bot1.dashboard') }}" >پیشخوان</a>
                                    <a class="btn {{ Request::routeIs(['bot1.users']) || Request::routeIs(['bot2.users']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect" href="{{ route('bot1.users') }}" >کل کاربران</a>
                                    <a class="btn {{ Request::routeIs(['bot1.activeUsers']) || Request::routeIs(['bot2.activeUsers']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect" href="{{ route('bot1.activeUsers') }}" >کاربران فعال</a>
                                    <a class="btn {{ Request::routeIs(['bot1.deactiveUsers']) || Request::routeIs(['bot2.deactiveUsers']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect" href="{{ route('bot1.deactiveUsers') }}">کاربران رفته</a>
                                    <a class="btn {{ Request::routeIs(['userSearch']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect" href="{{ route('userSearch') }}">جستجوی کاربر</a>
                                </div>
                                @elseif ($Bot == 'Bot2')
                                <div class="card-body">
                                    <a class="btn {{ Request::routeIs(['bot1.dashboard']) || Request::routeIs(['bot2.dashboard']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect waves-light" href="{{ route('bot2.dashboard') }}" >پیشخوان</a>
                                    <a class="btn {{ Request::routeIs(['bot1.users']) || Request::routeIs(['bot2.users']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect" href="{{ route('bot2.users') }}" >کل کاربران</a>
                                    <a class="btn {{ Request::routeIs(['bot1.activeUsers']) || Request::routeIs(['bot2.activeUsers']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect" href="{{ route('bot2.activeUsers') }}" >کاربران فعال</a>
                                    <a class="btn {{ Request::routeIs(['bot1.deactiveUsers']) || Request::routeIs(['bot2.deactiveUsers']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect" href="{{ route('bot2.deactiveUsers') }}">کاربران رفته</a>
                                    <a class="btn {{ Request::routeIs(['userSearch']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect" href="{{ route('userSearch') }}">جستجوی کاربر</a>
                                </div> @endif
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
                        <p class="text-muted mb-0">{{ number_format($ActiveUsers) }} نفر </p>
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
                            <h4 class="text-warning mb-0">{{ number_format($deactiveUsers) }}</h4><br />
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
                            <h4 class="text-info mb-0">21</h4>
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
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/i18n/fa.js"></script>
    <!-- Flat Picker -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/libs/jdate/jdate.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/libs/flatpickr/flatpickr-jalali.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/libs/flatpickr/l10n/fa.js"></script>
    <!-- Form Validation -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/@form-validation/auto-focus.js"></script>
    <!-- Main JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <script>
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
