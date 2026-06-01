<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>کاربران ربات - روحی بات</title>
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
                        @if ($Bot == 'Bot1')
                           <span class="text-muted fw-light">ربات قدیمی روحی ترید/</span>
                        @elseif ($Bot == 'Bot2')
                           <span class="text-muted fw-light">ربات جدید روحی ترید ال بانک/</span>
                        @endif
                        
                        @if (Request::routeIs(['bot1.users']) || Request::routeIs(['bot2.users']))
                            کل کاربران
                        @elseif(Request::routeIs(['bot1.activeUsers']) || Request::routeIs(['bot2.activeUsers']))
                            کاربران فعال
                        @elseif(Request::routeIs(['bot1.deactiveUsers']) || Request::routeIs(['bot2.deactiveUsers']))
                            کاربران رفته
                        @endif
                    </h4>
                    <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                    <div class="row">
                        <div class="col-12 col-md-2">
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
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-10">
                            <div class="card">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="datatables-direct-basic table">
                                <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام کاربر</th>
                                    <th>شماره موبایل</th>
                                    <th>نام تلگرام</th>
                                    <th>یوزرنیم تلگرام</th>
                                    @if ($Bot == 'Bot2')
                                    <th>موجودی ال بانک</th> @endif
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
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

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

    <!-- Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>


    <!-- Main JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <style>
        div.dt-buttons {
            position: initial;
            padding: 10px;
        }

        [dir=rtl] div.dataTables_wrapper .dataTables_filter {
            display: flex;
            justify-content: flex-end;
            float: left;
            padding: 0 20px;
        }
    </style>
    <script>
        $(function() {
            var dt_without_ajax_table = $('.datatables-direct-basic');

            if (dt_without_ajax_table.length) {
                dt_without_ajax = dt_without_ajax_table.DataTable({
                    searching: true,
                    lengthChange: true,
                    ordering: true,
                    processing: true,
                    serverSide: true,
                    ajax: '{{ $botUsersAjaxUrl }}',
                    pageLength: 100,
                    lengthMenu: [
                        [25, 50, 100],
                        [25, 50, 100]
                    ],
                    order: [],
                    columnDefs: [{
                        targets: '_all',
                        orderable: false
                    }],
                    language: {
                        processing: 'در حال جستجو...',
                        search: 'جستجو:',
                        lengthMenu: 'نمایش _MENU_ ردیف',
                        zeroRecords: 'رکوردی پیدا نشد',
                        info: 'نمایش _START_ تا _END_ از _TOTAL_ رکورد',
                        infoEmpty: 'رکوردی برای نمایش وجود ندارد',
                        infoFiltered: '(فیلتر شده از _MAX_ رکورد)',
                        paginate: {
                            first: 'اول',
                            last: 'آخر',
                            next: 'بعدی',
                            previous: 'قبلی'
                        }
                    },

                    // ✅ فعال‌سازی دکمه‌ها
                    dom: 'fltip',
                    buttons: []
                });

                $('<div class="dt-buttons" style="padding: 10px;">' +
                    '<a href="{{ $botUsersExportUrl }}" class="btn btn-primary">' +
                    'خروجی اکسل' +
                    '</a></div>').insertBefore('.dataTables_wrapper');
            }
        });
    </script>
    </body>

</html>
