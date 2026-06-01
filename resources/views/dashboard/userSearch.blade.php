<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>جستجوی کاربر - روحی بات</title>
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
                                <form method="GET" action="{{ route('users.search') }}">
                                    <div class="card">
                                        <div
                                            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                                            <h5 class="card-title mb-sm-0 me-2">جستجوی کاربر</h5>
                                            <div class="action-btns">
                                                <button class="btn btn-primary">بررسی</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if (!empty($isSalesTeamScope))
                                                <div class="alert alert-info mt-3">جستجو فقط بین کاربران گروه‌های اختصاص
                                                    داده‌شده به شما انجام می‌شود.</div>
                                            @endif
                                            <div class="row">
                                                <div class="col-lg-8 mx-auto">

                                                    <h5 class="my-4">شماره موبایل یا یوزرنیم تلگرام یا اسم کاربر جستجو
                                                        کنید:</h5>
                                                    <div class="row g-3 mb-5">
                                                        <div class="col-12">
                                                            <input class="form-control" name="user"
                                                                placeholder="اطلاعات کاربر"
                                                                value="{{ isset($search) ? $search : '' }}"
                                                                type="text" />
                                                        </div>
                                                    </div>

                                                    @if (isset($search) && $search != null)
                                                        <div class="row g-3">
                                                            <div class="card">
                                                                <h5 class="card-header">نتایج جستجو</h5>
                                                                <div class="table-responsive text-nowrap">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>ردیف</th>
                                                                                <th>نام کاربر</th>
                                                                                <th>شماره موبایل</th>
                                                                                <th>LBank UID</th>
                                                                                <th>موجودی ال بانک</th>
                                                                                <th>وضعیت</th>
                                                                                <th>عملیات</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="table-border-bottom-0">
                                                                            @php($x = 1)
                                                                            @foreach ($Users as $user)
                                                                                <tr>
                                                                                    <td><bdi>{{ $x }}</bdi>
                                                                                    </td>
                                                                                    <td><bdi>{{ $user->nam }}</bdi>
                                                                                    </td>
                                                                                    <td><bdi>{{ $user->mobile }}</bdi>
                                                                                    <td><bdi>{{ $user->lbank_uid }}</bdi>
                                                                                    </td>

                                                                                    <td>
                                                                                        <span
                                                                                            class="badge bg-label-primary me-1">
                                                                                            {{ $user->latestBalance ? $user->latestBalance->balance : 'سینک نشده' }}</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($user->has_active_vip)
                                                                                            <span
                                                                                                class="badge bg-label-success me-1">فعال</span>
                                                                                        @else
                                                                                            <span
                                                                                                class="badge bg-label-danger me-1">غیرفعال</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td><a href="{{ route('users.detail', $user->id) }}"
                                                                                            class="btn btn-sm btn-info">مشاهده</a>
                                                                                    </td>
                                                                                </tr>
                                                                                @php($x++)
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
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
    <script></script>
</body>

</html>
