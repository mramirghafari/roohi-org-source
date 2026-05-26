<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>ایجاد آموزش جدید - روحی بات</title>
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
                           ثبت آموزش جدید
                        </h4>
                        @if (session('success'))
                            <div class="alert alert-success my-2">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                        
                                    <form method="POST" action="{{ route('blogPosts.store') }}" enctype="multipart/form-data">
                                        @csrf
                        
                                        <div class="card-header">
                                            <h5 class="card-title m-0">ثبت پست آموزشی جدید</h5>
                                        </div>
                        
                                        <div class="card-body">
                                            <div class="row mb-3 g-3">

                                                {{-- title --}}
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label mb-0" for="title">عنوان پست</label>
                                                    <input aria-label="عنوان پست" class="form-control" id="title" name="title" placeholder="عنوان پست"
                                                        type="text" value="{{ old('title') }}" />
                                                    @error('title')
                                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                    @enderror
                                                </div>
                        
                                                {{-- category_id --}}
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label mb-0" for="category_id">دسته‌بندی</label>
                                                    <select aria-label="دسته‌بندی" class="form-select" id="category_id" name="category_id">
                                                        <option value="">انتخاب دسته‌بندی</option>
                                                        @foreach ($categories as $cat)
                                                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                                                {{ $cat->title }}
                                                            </option> @endforeach
                                                    </select>
                                                    @error('category_id')
<small class="text-danger
        d-block mt-1">{{ $message }}</small>
@enderror
</div>

{{-- slug --}}
<div class="col-12">
    <label class="form-label mb-0" for="slug">اسلاگ (لینک)</label>
    <input aria-label="اسلاگ" class="form-control" id="slug" name="slug"
        placeholder="مثلاً: laravel-wallet-system" type="text" value="{{ old('slug') }}" />
    @error('slug')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>

{{-- excerpt --}}
<div class="col-12">
    <label class="form-label mb-0" for="excerpt">خلاصه</label>
    <textarea aria-label="خلاصه" class="form-control" id="excerpt" name="excerpt" placeholder="خلاصه کوتاه پست"
        rows="3">{{ old('excerpt') }}</textarea>
    @error('excerpt')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
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

{{-- cover_image --}}
<div class="col-12">

    <label class="form-label mb-0" for="cover_image">تصویر کاور</label>

    <input aria-label="تصویر کاور" class="form-control" id="cover_image" name="cover_image" type="file"
        accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml" onchange="previewCover(this)" />

    @error('cover_image')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror


    {{-- Preview --}}
    <div class="mt-2">
        <img id="coverPreview" src=""
            style="display:none;
                    max-width:200px;
                    max-height:120px;
                    border:1px solid #ddd;
                    border-radius:6px;
                    padding:4px;" />
    </div>

</div>


{{-- status --}}
<div class="col-12">
    <label class="form-label mb-0" for="status">وضعیت</label>
    <select aria-label="وضعیت" class="form-select" id="status" name="status">
        <option value="draft" @selected(old('status', 'draft') === 'draft')>پیش‌نویس</option>
        <option value="published" @selected(old('status') === 'published')>منتشر شده</option>
    </select>
    @error('status')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>

{{-- published_at --}}
<div class="col-12">
    <label class="form-label mb-0" for="published_at">تاریخ انتشار (اختیاری)</label>
    <input aria-label="تاریخ انتشار" class="form-control" id="published_at" name="published_at"
        type="datetime-local" value="{{ old('published_at') }}" />
    @error('published_at')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>

</div>

<button class="btn btn-primary waves-effect waves-light" type="submit">
    ایجاد پست
</button>
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
<script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
<script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>

<script src="https://cdn.tiny.cloud/1/bvxvftrx88giecqfpqijbb2yi9t3rewncfha0li58gbdtwvb/tinymce/8/tinymce.min.js"
    referrerpolicy="origin" crossorigin="anonymous"></script>
</script>


<script>
    $('.blog_admin').addClass('active');

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
            "advlist", "anchor", "autolink", "charmap", "code", "fullscreen",
            "help", "image", "insertdatetime", "link", "lists", "media",
            "preview", "searchreplace", "table", "visualblocks",
        ],
        toolbar: "undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: true,

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

<script>
    function previewCover(input) {

        const preview = document.getElementById('coverPreview');

        if (!input.files || !input.files[0]) {
            preview.style.display = 'none';
            return;
        }

        const file = input.files[0];

        // Allowed types
        const allowed = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/webp',
            'image/svg+xml'
        ];

        if (!allowed.includes(file.type)) {

            alert('فرمت تصویر مجاز نیست');

            input.value = '';
            preview.style.display = 'none';

            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(file);
    }
</script>

</body>

</html>
