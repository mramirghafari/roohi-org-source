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
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/select2/select2.css" rel="stylesheet" />
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

                                    <a class="btn {{ Request::routeIs(['users.search']) ? 'btn-primary' : 'btn-label-secondary' }} d-grid w-100 mb-2 waves-effect"
                                        href="{{ route('users.search') }}">جستجوی کاربر</a>
                                </div>
                            </div>
                            <div class="col-12 col-md-10">
                                @if (!empty($isSalesTeamScope))
                                    <div class="alert alert-info">در این بخش فقط کاربران گروه‌های اختصاص داده‌شده به شما
                                        نمایش داده می‌شوند.</div>
                                @endif
                                <div class="card">
                                    @if (!empty($canBulkGroupAssign))
                                        <form method="POST" action="{{ route('users.bulkGroups') }}"
                                            id="bulk-group-form">
                                            @csrf
                                            <div class="card-body border-bottom bulk-group-toolbar">
                                                <div class="row g-3 align-items-end">
                                                    <div class="col-12 col-xl-6 bulk-toolbar-field">
                                                        <label class="form-label mb-2">افزودن دسته‌جمعی کاربران
                                                            انتخاب‌شده به گروه</label>
                                                        <select class="form-select js-bulk-group-select" name="group_id"
                                                            required data-placeholder="انتخاب گروه مقصد">
                                                            <option value=""></option>
                                                            @foreach ($bulkGroups ?? collect() as $group)
                                                                <option value="{{ $group->id }}">
                                                                    {{ $group->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-6 col-xl-3 bulk-toolbar-field">
                                                        <label class="form-label mb-2">تعداد انتخاب‌شده</label>
                                                        <div class="bulk-selected-box">
                                                            <span id="bulk-selected-count">0</span>
                                                            <small>کاربر انتخاب شده</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6 col-xl-3 bulk-toolbar-field">
                                                        <label class="form-label mb-2">عملیات گروهی</label>
                                                        <button type="submit"
                                                            class="btn btn-primary w-100 bulk-submit-btn">افزودن به
                                                            گروه</button>
                                                    </div>
                                                </div>
                                            </div>
                                    @endif
                                    <div class="card-datatable table-responsive pt-0">
                                        <table class="datatables-direct-basic table">
                                            <thead>
                                                <tr>
                                                    @if (!empty($canBulkGroupAssign))
                                                        <th><input type="checkbox" class="form-check-input"
                                                                id="bulk-select-all"></th>
                                                    @endif
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
                                            </tbody>
                                        </table>
                                    </div>
                                    @if (!empty($canBulkGroupAssign))
                                        </form>
                                    @endif
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
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/select2/select2.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/select2/i18n/fa.js"></script>
        <!-- Flat Picker -->

        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

        <!-- Excel export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>


        <!-- Main JS -->
        <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
        <style>
            .bulk-group-toolbar {
                background: linear-gradient(135deg, #f8f7ff 0%, #ffffff 70%);
            }

            .bulk-toolbar-field {
                display: flex;
                flex-direction: column;
            }

            .bulk-toolbar-field .form-label {
                min-height: 20px;
                color: #5d596c;
                font-size: 0.78rem;
                font-weight: 600;
            }

            .bulk-selected-box {
                min-height: 48px;
                border: 1px solid #ebe9f1;
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                background: #fff;
                color: #5d596c;
                font-weight: 600;
            }

            .bulk-selected-box span {
                min-width: 34px;
                height: 34px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: #7367f0;
                color: #fff;
            }

            .bulk-submit-btn {
                min-height: 48px;
                font-weight: 700;
            }

            .bulk-group-toolbar .select2-container--default .select2-selection--single {
                min-height: 48px;
                border-color: #7367f0;
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
            }

            .bulk-group-toolbar .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 46px;
                padding-right: 1rem;
                color: #5d596c;
            }

            .bulk-group-toolbar .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 46px;
            }

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
                if ($.fn.select2) {
                    $('.js-bulk-group-select').select2({
                        width: '100%',
                        dir: 'rtl',
                        placeholder: $('.js-bulk-group-select').data('placeholder'),
                        allowClear: true
                    });
                }

                var dt_without_ajax_table = $('.datatables-direct-basic');

                if (dt_without_ajax_table.length) {
                    dt_without_ajax = dt_without_ajax_table.DataTable({
                        searching: true,
                        lengthChange: true,
                        ordering: true,
                        processing: true,
                        serverSide: true,
                        ajax: '{{ $usersAjaxUrl }}',
                        pageLength: 100,
                        lengthMenu: [
                            [25, 50, 100],
                            [25, 50, 100]
                        ],
                        order: [],
                        columnDefs: [{
                                targets: '_all',
                                orderable: false
                            },
                            {
                                targets: {{ !empty($canBulkGroupAssign) ? 3 : 2 }},
                                visible: false,
                                searchable: true
                            }
                        ],
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

                    dt_without_ajax.on('draw', function() {
                        const selectAll = document.getElementById('bulk-select-all');
                        const selectedCount = document.getElementById('bulk-selected-count');

                        if (selectAll) {
                            selectAll.checked = false;
                        }

                        if (selectedCount) {
                            selectedCount.textContent = '0';
                        }
                    });

                    // دکمه export دستی
                    $('<div class="dt-buttons" style="padding: 10px;">' +
                        '<a href="{{ route('users.export.excel', ['scope' => $exportScope ?? 'all']) }}" class="btn btn-primary">' +
                        'خروجی اکسل' +
                        '</a></div>').insertBefore('.dataTables_wrapper');
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const selectAll = document.getElementById('bulk-select-all');
                const selectedCount = document.getElementById('bulk-selected-count');
                const form = document.getElementById('bulk-group-form');

                const updateCount = () => {
                    if (!selectedCount) {
                        return;
                    }
                    selectedCount.textContent = document.querySelectorAll('.bulk-user-checkbox:checked').length;
                };

                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        document.querySelectorAll('.bulk-user-checkbox').forEach(function(checkbox) {
                            checkbox.checked = selectAll.checked;
                        });
                        updateCount();
                    });
                }

                document.addEventListener('change', function(event) {
                    if (event.target.classList.contains('bulk-user-checkbox')) {
                        updateCount();
                    }
                });

                if (form) {
                    form.addEventListener('submit', function(event) {
                        if (document.querySelectorAll('.bulk-user-checkbox:checked').length === 0) {
                            event.preventDefault();
                            alert('حداقل یک کاربر را انتخاب کنید.');
                            return;
                        }

                        if (!$('.js-bulk-group-select').val()) {
                            event.preventDefault();
                            alert('یک گروه مقصد انتخاب کنید.');
                        }
                    });
                }
            });
        </script>
</body>

</html>
