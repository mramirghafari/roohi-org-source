<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>تاریخچه سیگنال ها - روحی بات</title>
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
    <!-- Row Group CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/animate-css/animate.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.css" rel="stylesheet" />

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
                            <span class="text-muted fw-light">پیام ها و سیگنال ها /</span>
                            تاریخچه
                        </h4>
                        <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                        <div class="card">
                            <div class="card-datatable table-responsive pt-0">
                                <table class="datatables-direct-basic table">
                                    <thead>
                                        <tr>
                                            <th>ردیف</th>
                                            <th>نوع معامله</th>
                                            <th>تاریخ</th>
                                            <th>ورودی اول</th>
                                            <th>تارگت اول</th>
                                            <th>SL</th>
                                            <th>اهرم</th>
                                            <th>وضعیت</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($x = 1)
                                        @foreach ($Signals as $signal)
                                            <tr>
                                                <td><bdi>{{ $x }}</bdi></td>
                                                <td><a href="{{ route('signalDetails', $signal->id) }}">
                                                        @if ($signal->type == 1)
                                                            <small class="badge bg-danger p-1"> شورت</small>
                                                        @else
                                                            <small class="badge bg-success p-1"> لانگ</small>
                                                        @endif
                                                    </a>
                                                    <a
                                                        href="{{ route('signalDetails', $signal->id) }}">{{ $signal->symbol }}</a>
                                                </td>
                                                <td>
                                                    <small>{{ verta($signal->created_at)->format('Y/m/d H:i') }}
                                                    </small>
                                                </td>
                                                <td>{{ $signal->entryPrice1 }}</td>
                                                <td>{{ $signal->target1 }}</td>
                                                <td>{{ $signal->sl }}</td>
                                                <td>{{ $signal->laverege }}</td>
                                                <td>
                                                    @if ($signal->status == 1)
                                                        <span class="badge  bg-label-success">سود</span>
                                                        @if ($signal->profit > 0)
                                                            <span
                                                                class="badge  bg-label-success">{{ $signal->profit . '%' }}</span>
                                                        @endif
                                                    @elseif ($signal->status == 3)
                                                        <span class="badge  bg-label-danger">ضرر</span>
                                                        @if ($signal->profit > 0)
                                                            <span
                                                                class="badge  bg-label-danger">{{ $signal->profit . '%' }}</span>
                                                        @endif
                                                    @elseif ($signal->status == 9)
                                                        <span class="badge  bg-label-dark">کنسل</span>
                                                    @else
                                                        <span class="badge  bg-label-warning">در حال انجام</span>
                                                        @php($touchedProfit = $signal->touchedProfitPercent())
                                                        @if (!is_null($touchedProfit))
                                                            @if ($touchedProfit >= 0)
                                                                <span class="badge bg-label-success">سود:
                                                                    {{ abs($touchedProfit) }}%</span>
                                                            @else
                                                                <span class="badge bg-label-danger">ضرر:
                                                                    {{ abs($touchedProfit) }}%</span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('signalDetails', $signal->id) }}"
                                                        class="btn btn-primary">ویرایش</a>
                                                    @if ($canDeleteSignals)
                                                        <form action="{{ route('signals.destroy', $signal->id) }}"
                                                            method="post" class="d-inline delete-signal-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger delete-signal-btn"
                                                                data-signal-title="{{ $signal->symbol }}"
                                                                data-tracking-code="{{ $signal->tracking_code }}">حذف</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php($x++)
                                        @endforeach
                                    </tbody>
                                </table>
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
    <!-- Vendors JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/i18n/fa.js"></script>
    <!-- Flat Picker -->
    <!-- Main JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <script>
        $('.admin').addClass('active');
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

        document.addEventListener('click', async (e) => {
            const deleteBtn = e.target.closest('.delete-signal-btn');
            if (!deleteBtn) return;

            e.preventDefault();

            const form = deleteBtn.closest('.delete-signal-form');
            const signalTitle = deleteBtn.dataset.signalTitle;
            const trackingCode = deleteBtn.dataset.trackingCode;

            const result = await Swal.fire({
                title: 'حذف سیگنال؟',
                text: `سیگنال "${signalTitle}" با کد ${trackingCode} حذف شود؟`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'بله، حذف کن',
                cancelButtonText: 'لغو'
            });

            if (result.isConfirmed) {
                form.submit();
            }
        });

        @if (session('success'))
            Swal.fire({
                title: 'انجام شد',
                text: @json(session('success')),
                icon: 'success',
                confirmButtonText: 'باشه'
            });
        @endif
    </script>
</body>

</html>
