<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>کاربران سایت - روحی بات</title>
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
                            <span class="text-muted fw-light">مدیریت کاربران</span>
                        </h4>
                        <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                        <div class="row">
                            <div class="col-12 col-md-2">
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
                            <div class="col-12 col-md-10">
                                @if (Request::routeIs(['bot1.users']))
                                    {{ $Users->links() }}
                                @endif

                                <div class="card">
                                    <div class="card-datatable table-responsive pt-0">
                                        <table class="datatables-direct-basic table">
                                            <thead>
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th>نام کاربر</th>
                                                    <th style="display: none;">موبایل</th>
                                                    <th>شروع اشتراک</th>
                                                    <th>پایان اشتراک</th>
                                                    <th>مانده اشتراک</th>
                                                    <th>LBank UID</th>
                                                    <th>Coinlocally UID</th>
                                                    <th>عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php($x = 1)
                                                @foreach ($Users as $user)
                                                    <tr>
                                                        <td><bdi>{{ $x }}</bdi></td>
                                                        <td>
                                                            <div class="row flex-column">
                                                                <div>
                                                                    @if ($user->has_active_vip)
                                                                        <small class="badge bg-label-success me-1"
                                                                            style="padding: 5px"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="18" height="18"
                                                                                viewBox="0 0 24 24" fill="currentColor"
                                                                                class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
                                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                                    fill="none" />
                                                                                <path
                                                                                    d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                                                                            </svg></small>
                                                                    @endif
                                                                    <bdi>{{ $user->nam }}</bdi>

                                                                </div>
                                                                <div class="ps-5">
                                                                    <small>{{ $user->mobile }}</small>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td style="display: none;">{{ $user->mobile }}</td>
                                                        <td
                                                            data-start="{{ $user->activeSubscribe ? $user->activeSubscribe->start_vip->format('Y-m-d') : '' }}">
                                                            @if ($user->activeSubscribe)
                                                                <small>{{ verta($user->activeSubscribe->start_vip)->format('Y-m-d') }}</small>
                                                            @else
                                                                <small>-</small>
                                                            @endif
                                                        </td>
                                                        <td
                                                            data-end="{{ $user->activeSubscribe ? $user->activeSubscribe->exp_vip->format('Y-m-d') : '' }}">
                                                            @if ($user->activeSubscribe)
                                                                <small>{{ verta($user->activeSubscribe->exp_vip)->format('Y-m-d') }}</small>
                                                            @else
                                                                <small>-</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($user->activeSubscribe)
                                                                <small
                                                                    class="badge {{ $user->vip_remaining_days > 10 ? 'bg-label-info' : 'bg-label-danger' }}">
                                                                    {{ $user->vip_remaining_days . ' روز' }}</small>
                                                            @else
                                                                <small>-</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($user->lbank_uid)
                                                                <span
                                                                    class="badge bg-label-success me-1 rounded p-1">{{ $user->lbank_uid }}</span>
                                                            @else
                                                                <span>-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($user->coincall_uid)
                                                                <span
                                                                    class="badge bg-label-info me-1 rounded p-1">{{ $user->coincall_uid }}</span>
                                                            @else
                                                                <span>-</span>
                                                            @endif
                                                        </td>

                                                        <td><a href="{{ route('users.detail', $user->id) }}"
                                                                class="btn btn-sm btn-info">مشاهده</a></td>
                                                    </tr>
                                                    @php($x++)
                                                @endforeach
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
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
        <!-- endbuild -->
        <!-- Vendors JS -->
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/i18n/fa.js"></script>
        <!-- Flat Picker -->

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
            $('.users').addClass('active');
            $(function() {
                var dt_without_ajax_table = $('.datatables-direct-basic');

                if (dt_without_ajax_table.length) {
                    dt_without_ajax = dt_without_ajax_table.DataTable({
                        searching: true,
                        lengthChange: true,
                        ordering: true,
                        pageLength: 100,

                        // ✅ فعال‌سازی دکمه‌ها
                        dom: 'fltip',
                        buttons: []
                    });

                    // دکمه export دستی
                    $('<div class="dt-buttons" style="padding: 10px;">' +
                        '<a href="{{ route('users.export.excel', ['scope' => request()->routeIs('users.activeUsers') ? 'active' : (request()->routeIs('users.deactiveUsers') ? 'deactive' : (request()->routeIs('users.leftUsers') ? 'left' : 'all'))]) }}" class="btn btn-primary">' +
                        'خروجی اکسل' +
                        '</a></div>').insertBefore('.dataTables_wrapper');
                }
            });
        </script>
</body>

</html>
