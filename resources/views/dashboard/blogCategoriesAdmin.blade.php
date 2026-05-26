<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>دسته بندی های آموزش - روحی بات</title>
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

    <link
        href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/@form-validation/form-validation.css" rel="stylesheet"/>
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/animate-css/animate.css" rel="stylesheet"/>
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.css" rel="stylesheet"/>

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
                            <span class="text-muted fw-light">مدیریت آموزش ها/</span>
                            دسته بندی ها
                        </h4>
                        @if (session('success'))
                            <div class="alert alert-success mt-2 mb-0">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <form method="POST" action="{{ route('blogCategoriesAdmin.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header">
                                            <h5 class="card-title m-0">ثبت دسته بندی جدید</h5>
                                        </div>
                                        <div class="card-body">

                                            <div class="row mb-3 g-3">

                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="title">عنوان دسته
                                                        بندی</label>
                                                    <input aria-label="عنوان دسته بندی" class="form-control"
                                                        id="title" name="title" placeholder="عنوان دسته"
                                                        type="text" />
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="slug">لینک دسته
                                                        بندی</label>
                                                    <input aria-label="لینک دسته بندی" class="form-control"
                                                        id="slug" name="slug" placeholder="لینک دسته بندی"
                                                        type="text" />
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="parent_id">والد</label>
                                                    <select aria-label="والد دسته بندی" class="form-select"
                                                        id="parent_id" name="parent_id">
                                                        <option value="">بدون والد</option>
                                                        @foreach ($categories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->title }}
                                                            </option> @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12">
    <label class="form-label mb-0" for="description">توضیحات</label>
    <textarea aria-label="توضیحات دسته بندی" class="form-control" id="description" name="description"
        placeholder="توضیحات دسته بندی" rows="3"></textarea>
    </div>

    <div class="col-12">
        <label class="form-label mb-0" for="access">نوع نمایش</label>
        <select aria-label="وضعیت دسته بندی" class="form-select" id="access" name="access">
            <option value="free">عمومی</option>
            <option value="register">کاربران ثبت نام شده</option>
            <option value="vip">کاربران VIP</option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label mb-0" for="is_active">وضعیت نمایش</label>
        <select aria-label="وضعیت دسته بندی" class="form-select" id="is_active" name="is_active">
            <option value="1">فعال</option>
            <option value="0">غیرفعال</option>
        </select>
    </div>


    </div>
    <button class="btn btn-primary waves-effect waves-light" type="submit">ایجاد دسته بندی</button>

    </div>

    </form>
    </div>
    </div>
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-direct-basic table">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>عنوان دسته</th>
                            <th>والد</th>
                            <th>تعداد مقاله</th>
                            <th>وضعیت</th>
                            <th>نمایش</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($x = 1)
                        @foreach ($categories as $cat)
                            <tr>
                                <td>{{ $x++ }}</td>
                                <td>{{ $cat->title }}</td>
                                <td>{{ $cat->parent ? $cat->parent->title : 'بدون والد' }}</td>
                                <td>{{ $cat->posts_count ?? 0 }}</td>
                                <td>{{ $cat->is_active ? 'فعال' : 'غیرفعال' }}</td>
                                <td>
                                    @if ($cat->access == 'free')
                                        <span class="badge bg-label-success">عمومی</span>
                                    @elseif($cat->access == 'register')
                                        <span class="badge bg-label-primary">ثبت نام شده</span>
                                    @elseif($cat->access == 'vip')
                                        <span class="badge bg-label-warning">VIP</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('blogCategoriesAdmin.edit', $cat->id) }}"
                                        class="btn btn-sm btn-primary">ویرایش</a>
                                    <form action="{{ route('blogCategoriesAdmin.destroy', $cat->id) }}" method="post"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                            data-category-title="{{ $cat->title }}">حذف</button>
                                    </form>
                                </td>
                                @php($x++)
                        @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    </div>

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
        $('.blog_admin').addClass('active');

        // SweetAlert برای حذف دسته بندی
        document.addEventListener('click', async (e) => {
            const deleteBtn = e.target.closest('.delete-btn');
            if (!deleteBtn) return;

            e.preventDefault();

            const categoryTitle = deleteBtn.dataset.categoryTitle;
            const form = deleteBtn.closest('.delete-form');

            const result = await Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: `دسته بندی "${categoryTitle}" حذف شود؟`,
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
