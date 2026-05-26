<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ env('DASHBOARD_THEME_PATH') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>آموزش ها - روحی بات</title>

    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#000c63">
    <link rel="apple-touch-icon" href="/icons/pwa-192.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Roohi AI">

    <!-- Favicon -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <!-- Icons -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/toastr/toastr.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/animate-css/animate.css" rel="stylesheet" />
    <!-- Vendors CSS -->

    <!-- Helpers -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/css/rtl.css" rel="stylesheet" />
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
                            <div class="col-12 col-md-9">

                                @foreach ($posts as $post)
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100">
                                            <a href="{{ route('internalSingleBlog', $post->slug) }}">
                                                <img alt="{{ $post->title }}" class="card-img-top"
                                                    src="{{ asset('/') . $post->cover_image }}"
                                                    style="width: 345px; height: 250px">
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $post->title }}</h5>
                                                <div class="d-flex gap-2 position-absolute top-0 start-0 z-index-2 m-4">
                                                    <span class="badge bg-info">{{ $post->category->title }}</span>
                                                    <span
                                                        class="badge bg-white text-dark">{{ verta($post->published_at)->format('d F Y') }}</span>
                                                </div>
                                                <p class="card-text">
                                                    {{ $post->excerpt }}
                                                </p>
                                                <a class="btn btn-outline-primary waves-effect"
                                                    href="{{ route('internalSingleBlog', $post->slug) }}">مشاهده
                                                    مطلب...</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="list-group">
                                            @foreach ($categories as $cat)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="{{ route('internalBlogCat', $cat->slug) }}">
                                                        {{ $cat->title }}
                                                    </a>
                                                    @if ($cat->access == 'free')
                                                        <span class="badge bg-primary">عمومی</span>
                                                    @elseif($cat->access == 'register')
                                                        <span class="badge bg-info">اعضای سایت</span>
                                                    @elseif($cat->access == 'vip')
                                                        <span class="badge bg-warning">VIP</span>
                                                    @endif

                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/bootstrap.js"></script>

    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/toastr/toastr.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/toastr/toastr.js"></script>
    <!-- Main JS -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/js/main.js"></script>
    <!-- Page JS -->
    <script>
        $('.blog').addClass('active')
    </script>

    @include('dashboard.sections.installApp')
</body>

</html>
