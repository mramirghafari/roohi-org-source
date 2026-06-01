<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>ویرایش دسته بندی آموزش - روحی بات</title>
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
                                    <span class="text-muted fw-light">مدیریت آموزش ها/</span>
                                    ویرایش دسته بندی
                                </h4>
                            </div>
                            <div class="col-12 col-md-6 text-end d-flex align-items-center justify-content-end">
                                <a href="{{ route('blogCategoriesAdmin.index') }}" class="btn btn-secondary">
                                    بازگشت
                                    <i class="fa fa-arrow-left ms-2"></i>
                                </a>
                            </div>
                        </div>
                        <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <form method="POST"
                                        action="{{ route('blogCategoriesAdmin.update', $category->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="card-header">
                                            <h5 class="card-title m-0">ویرایش دسته بندی</h5>
                                            @if (session('success'))
                                                <div class="alert alert-success mt-2 mb-0">
                                                    {{ session('success') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">

                                            <div class="row mb-3 g-3">

                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="title">عنوان دسته
                                                        بندی</label>
                                                    <input aria-label="عنوان دسته بندی" class="form-control"
                                                        id="title" name="title" placeholder="عنوان دسته"
                                                        type="text"
                                                        value="{{ old('title', $category->title ?? '') }}" />
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="slug">لینک دسته
                                                        بندی</label>
                                                    <input aria-label="لینک دسته بندی" class="form-control"
                                                        id="slug" name="slug" placeholder="لینک دسته بندی"
                                                        type="text" value="{{ $category->slug }}" />
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="parent_id">والد</label>
                                                    <select aria-label="والد دسته بندی" class="form-select"
                                                        id="parent_id" name="parent_id">
                                                        <option value="">بدون والد</option>
                                                        @foreach ($categories as $cat)
                                                            <option value="{{ $cat->id }}"
                                                                {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                                                                {{ $cat->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="description">توضیحات</label>
                                                    <textarea aria-label="توضیحات دسته بندی" class="form-control" id="description" name="description"
                                                        placeholder="توضیحات دسته بندی" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="access">نوع نمایش</label>
                                                    <select aria-label="وضعیت دسته بندی" class="form-select"
                                                        id="access" name="access">
                                                        <option value="free"
                                                            {{ old('access', $category->access ?? '') == 'free' ? 'selected' : '' }}>
                                                            عمومی</option>
                                                        <option value="register"
                                                            {{ old('access', $category->access ?? '') == 'register' ? 'selected' : '' }}>
                                                            کاربران ثبت نام شده</option>
                                                        <option value="vip"
                                                            {{ old('access', $category->access ?? '') == 'vip' ? 'selected' : '' }}>
                                                            کاربران VIP</option>
                                                    </select>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="is_active">وضعیت نمایش</label>
                                                    <select aria-label="وضعیت دسته بندی" class="form-select"
                                                        id="is_active" name="is_active">
                                                        <option value="1"
                                                            {{ old('is_active', $category->is_active ?? 1) == 1 ? 'selected' : '' }}>
                                                            فعال</option>
                                                        <option value="0"
                                                            {{ old('is_active', $category->is_active ?? 1) == 0 ? 'selected' : '' }}>
                                                            غیرفعال</option>
                                                    </select>
                                                </div>


                                            </div>
                                            <button class="btn btn-primary waves-effect waves-light"
                                                type="submit">ویرایش دسته بندی</button>

                                        </div>

                                    </form>
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
