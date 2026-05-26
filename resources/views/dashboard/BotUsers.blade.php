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
@if ($Bot == 'Bot1' && Request::routeIs(['bot1.users']))
    {{ $Users->links() }}
@endif

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
                                    <th>موجودی ال بانک</th>
                                    @endif
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($x = 1)
                                @foreach ($Users as $user)
                                <tr>
                                    <td><bdi>{{ $x }}</bdi></td>
                                    <td>
                                        @if ($user->lbankApi?->is_connected == 1)
                                        <span class="bg-label-success rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-link"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /></svg>
                                        </span>
                                        @endif
                                        <bdi>{{ $user->nam }}</bdi></td>
                                    <td><bdi>{{ $user->mobile }}</bdi></td>
                                    <td><small>{{ substr($user->name, 0, 30) . ' ' . substr($user->last_name, 0, 30) }}</small></td>
                                    <td><small><a href="https://t.me/{{ $user->username }}" target="_blank">{{ $user->username }}</a></small></td>
                                    @if ($Bot == 'Bot2')
                                    <td>
                                       <span class="badge bg-label-primary me-1"> {{ $user->latestBalance ? $user->latestBalance->balance : 'سینک نشده' }}</span>
                                    </td>
                                    @endif
                                    <td>
                                        @if ($Bot == 'Bot1')
                                        
                                            @if ($user->status == 1)
                                                <span class="badge bg-label-success me-1">فعال</span>
                                            @else
                                                <span class="badge bg-label-danger me-1">غیرفعال</span>
                                            @endif

                                        @elseif ($Bot == 'Bot2')
                                            @if ($user->lbank_uid != null && $user->status == 1)
                                                <span class="badge bg-label-success me-1">فعال</span>
                                            @else
                                                <span class="badge bg-label-danger me-1">غیرفعال</span>
                                            @endif
                                        @endif
                                    </td>
                                    @if ($Bot == 'Bot1')
                                    <td><a href="{{ route('bot1.botUserInfo', $user->id) }}" class="btn btn-sm btn-info">مشاهده</a></td>
                                    @elseif ($Bot == 'Bot2')
                                        <td><a href="{{ route('bot2.botUserInfo', $user->id) }}" class="btn btn-sm btn-info">مشاهده</a></td>
                                    @endif
                                </tr>
                                    @php($x++) @endforeach
                                </tbody>
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
        $('.bots').addClass('active');
        $(function() {
            var dt_without_ajax_table = $('.datatables-direct-basic');

            if (dt_without_ajax_table.length) {
                dt_without_ajax = dt_without_ajax_table.DataTable({
                    searching: true,
                    lengthChange: true,
                    ordering: true,
                    pageLength: 100,

                    // ✅ فعال‌سازی دکمه‌ها
                    dom: 'Bfltip',
                    buttons: [{
                        extend: 'excelHtml5',
                        text: 'خروجی اکسل',
                        title: 'datatable-export',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }]
                });
            }
        });
    </script>
    </body>

</html>
