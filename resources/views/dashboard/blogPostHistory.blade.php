<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>لیست مقالات آموزشی - روحی بات</title>
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
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h4 class="py-3 mb-4">
                                    <span class="text-muted fw-light">مدیریت آموزش ها /</span>
                                    لیست پست ها و آموزش ها
                                </h4>
                            </div>
                            <div class="col-12 col-md-6 d-flex justify-content-end align-items-center">
                                <div class="text-md-end">
                                    <a href="{{ route('blogPosts.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus bx-sm"></i> ایجاد پست جدید
                                    </a>
                                </div>
                            </div>
                            <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                            <div class="card">
                                <div class="card-datatable table-responsive pt-0">
                                    <table class="datatables-direct-basic table">
                                        <thead>
                                            <tr>
                                                <th>ردیف</th>
                                                <th>تصویر</th>
                                                <th>عنوان</th>
                                                <th>دسته بندی</th>
                                                <th>تاریخ انتشار</th>
                                                <th>وضعیت</th>
                                                <th>عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($x = 1)
                                            @foreach ($BlogPosts as $blog)
                                                <tr>
                                                    <td>{{ $x }}</td>
                                                    <td>
                                                        <a href="{{ route('blogPosts.edit', $blog->id) }}">
                                                            <img id="coverPreview"
                                                                src="{{ asset('/') . $blog->cover_image }}"
                                                                style="max-width:80px;border:1px solid #ddd;border-radius:6px;padding:4px;" />
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('blogPosts.edit', $blog->id) }}">
                                                            {{ $blog->title }}
                                                        </a>
                                                    </td>
                                                    <td> {{ $blog->category->title }}
                                                    </td>
                                                    <td>{{ verta($blog->created_at)->format('Y/m/d') }}</td>
                                                    <td>
                                                        @if ($blog->status == 'published')
                                                            <span class="badge bg-label-success">منتشر شده</span>
                                                        @elseif($blog->status == 'draft')
                                                            <span class="badge bg-label-info">پیش نویس</span>
                                                        @elseif($blog->status == 'archived')
                                                            <span class="badge bg-label-secondary">آرشیو شده</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('blogPosts.edit', $blog->id) }}"
                                                            class="btn btn-sm btn-primary">ویرایش</a>
                                                        <form action="{{ route('blogPosts.destroy', $blog->id) }}"
                                                            method="post" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger delete-btn"
                                                                data-category-title="{{ $blog->title }}">حذف</button>
                                                        </form>
                                                    </td>
                                                </tr>
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

            // SweetAlert برای حذف دسته بندی
            document.addEventListener('click', async (e) => {
                const deleteBtn = e.target.closest('.delete-btn');
                if (!deleteBtn) return;

                e.preventDefault();

                const categoryTitle = deleteBtn.dataset.categoryTitle;
                const form = deleteBtn.closest('.delete-form');

                const result = await Swal.fire({
                    title: 'آیا مطمئن هستید؟',
                    text: ` پست "${categoryTitle}" حذف شود؟`,
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
        </script>
</body>

</html>
