<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>ارسال اعلان همگانی - روحی بات</title>
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
                            <span class="text-muted fw-light">کاربران / </span>
                            ارسال اعلان همگانی
                        </h4>
                        @if (session('success'))
                            <div class="alert alert-success my-2">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <form method="POST" action="{{ route('sendNotif.process') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header">
                                            <h5 class="card-title m-0">ارسال اعلان جدید</h5>
                                        </div>
                                        <div class="card-body">

                                            <div class="row mb-3 g-3">

                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="title">تیتر اعلان</label>
                                                    <input aria-label="تیتر اعلان" class="form-control"
                                                        id="title" name="title" placeholder="تیتر اعلان"
                                                        type="text" />
                                                </div>
                                                
                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="send_to">ارسال به:</label>
                                                    <select aria-label="ارسال به" class="form-select"
                                                        id="send_to" name="send_to">
                                                        <option value="">انتخاب کنید...</option>
                                                        <option value="1">همه کاربران</option>
                                                        <option value="2">کاربران VIP</option>
                                                        <option value="3">کاربران با اشتراک کمتر از 10 روز</option>
                                                        <option value="4">کاربران با اشتراک کمتر از 7 روز</option>
                                                        <option value="5">کاربران با اشتراک کمتر از 3 روز</option>
                                                        <option value="6">کاربران با اشتراک کمتر از 2 روز</option>
                                                        <option value="7">کاربران رفته</option>
                                                        <option value="8">کاربران احراز نشده</option>
                                                        <option value="9">کاربران بدون یو آیدی ال بانک</option>
                                                        <option value="10">کاربران بدون یو ایدی کوین لوکالی</option>
                                                        <option value="11">با موجودی کمتر از 50 دلار در ال بانک</option>
                                                        <option value="12">با موجودی کمتر از 50 دلار در کوین لوکالی</option>
                                                       
                                                    </select>
                                                </div>
                                                {{-- content --}}
                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="content">محتوا</label>
                                                    <textarea aria-label="محتوا" class="form-control" id="content" name="content" placeholder="متن کامل پست..."
                                                        rows="10">{{ old('content') }}</textarea>
                                                    @error('content')
                                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                    @enderror
                                                </div>


                                                <div class="col-12">
                                                    <label class="form-label mb-0" for="sms">ارسال SMS</label>
                                                    <select aria-label="ارسال SMS" class="form-select" id="sms" name="sms">
                                                        <option value="0">غیرفعال</option>
                                                        <option value="1">پیامک ارسال شود.</option>
                                                    </select>
                                                </div>

    </div>
    <button class="btn btn-primary waves-effect waves-light" type="submit">ارسال اعلان (صفبندی)</button>

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
                            <th>تیتر اعلان</th>
                            <th>گروه ارسالی</th>
                            <th>تعداد ارسالی</th>
                            <th>تعداد خوانده شده</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($x = 1)
                        @foreach ($ArchiveNotifs as $notifItem)
                            <tr>
                                <td>{{ $x++ }}</td>
                                <td>{{ $notifItem->title }}</td>
                                <td>
                                    
                                    @if ($notifItem->usersGroup == 1)
                                        <span class="badge bg-label-success">همه کاربران</span>
                                    @elseif($notifItem->usersGroup == 2)
                                        <span class="badge bg-label-success">کاربران VIP</span>
                                        @elseif($notifItem->usersGroup == 3)
                                        <span class="badge bg-label-success">کاربران با اشتراک کمتر از 10 روز</span>
                                        @elseif($notifItem->usersGroup == 4)
                                        <span class="badge bg-label-success">کاربران با اشتراک کمتر از 7 روز</span>
                                        @elseif($notifItem->usersGroup == 5)
                                        <span class="badge bg-label-success">کاربران با اشتراک کمتر از 3 روز</span>
                                        @elseif($notifItem->usersGroup == 6)
                                        <span class="badge bg-label-success">کاربران با اشتراک کمتر از 2 روز</span>
                                        @elseif($notifItem->usersGroup == 7)
                                        <span class="badge bg-label-success">کاربران رفته</span>
                                    @endif
                                </td>
                                <td>0</td>
                                <td>0</td>
                                
                                <td>
                                    <a href="{{ route('users.editNotif', $notifItem->id) }}"
                                        class="btn btn-sm btn-primary">ویرایش</a>
                                    <form action="{{ route('users.deleteNotif', $notifItem->id) }}" method="post"
                                        class="d-inline delete-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                            data-category-title="{{ $notifItem->title }}">حذف</button>
                                    </form>
                                </td>
                                @php($x++) @endforeach
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
    <div class="content-backdrop
        fade">
    </div>
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

    <script src="https://cdn.tiny.cloud/1/bvxvftrx88giecqfpqijbb2yi9t3rewncfha0li58gbdtwvb/tinymce/8/tinymce.min.js"
        referrerpolicy="origin" crossorigin="anonymous"></script>

    <script>
        $('.users').addClass('active');

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
    <script>
        tinymce.init({
            selector: '#content', // همون id فیلد محتوا
            height: 400,
            directionality: 'rtl',
            language: 'fa',

            plugins: [

            ],
            toolbar: "undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            menubar: false,

            branding: false,

            images_upload_url: '#',

            images_upload_credentials: true,

            setup: function(editor) {
                editor.on('change', function() {
                    tinymce.triggerSave(); // مهم برای submit فرم
                });
            }
        });
    </script>
    </body>

</html>
