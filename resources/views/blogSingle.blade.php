<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <title>{{ $Blog->title }} - روحی ترید</title>

    <!-- Meta Tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="AMiR Ghaffari" />
    <meta name="description" content="دستیار روحی، ربات متخصص استراتژی ها در بازارهای کریپتو" />

    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#000c63">
    <link rel="apple-touch-icon" href="/icons/pwa-192.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Roohi AI">

    <!-- Dark mode -->
    <script>
        const storedTheme = localStorage.getItem("theme");

        const getPreferredTheme = () => {
            if (storedTheme) {
                return storedTheme;
            }
            return window.matchMedia("(prefers-color-scheme: light)").matches ?
                "light" :
                "light";
        };

        const setTheme = function(theme) {
            if (
                theme === "auto" &&
                window.matchMedia("(prefers-color-scheme: dark)").matches
            ) {
                document.documentElement.setAttribute("data-bs-theme", "dark");
            } else {
                document.documentElement.setAttribute("data-bs-theme", theme);
            }
        };

        setTheme(getPreferredTheme());

        window.addEventListener("DOMContentLoaded", () => {
            var el = document.querySelector(".theme-icon-active");
            if (el != "undefined" && el != null) {
                const showActiveTheme = (theme) => {
                    const activeThemeIcon = document.querySelector(
                        ".theme-icon-active use"
                    );
                    const btnToActive = document.querySelector(
                        `[data-bs-theme-value="${theme}"]`
                    );
                    const svgOfActiveBtn = btnToActive
                        .querySelector(".mode-switch use")
                        .getAttribute("href");

                    document
                        .querySelectorAll("[data-bs-theme-value]")
                        .forEach((element) => {
                            element.classList.remove("active");
                        });

                    btnToActive.classList.add("active");
                    activeThemeIcon.setAttribute("href", svgOfActiveBtn);
                };

                window
                    .matchMedia("(prefers-color-scheme: dark)")
                    .addEventListener("change", () => {
                        if (storedTheme !== "light" || storedTheme !== "dark") {
                            setTheme(getPreferredTheme());
                        }
                    });

                showActiveTheme(getPreferredTheme());

                document
                    .querySelectorAll("[data-bs-theme-value]")
                    .forEach((toggle) => {
                        toggle.addEventListener("click", () => {
                            const theme = toggle.getAttribute("data-bs-theme-value");
                            localStorage.setItem("theme", theme);
                            setTheme(theme);
                            showActiveTheme(theme);
                        });
                    });
            }
        });
    </script>

    <!-- Favicon -->
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('/') }}/assets/images/favicon.ico" />

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/') }}/assets/vendor/bootstrap-icons/bootstrap-icons.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}/assets/vendor/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}/assets/vendor/glightbox/css/glightbox.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}/assets/vendor/aos/aos.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}/assets/css/style.css" />
</head>

<body>
    <!-- Header START -->
    <header class="header-sticky header-absolute">
        <!-- Logo Nav START -->
        <nav class="navbar navbar-expand-xl">
            <div class="container">
                <!-- Logo START -->
                <a class="navbar-brand me-0" href="index.html">
                    <img class="light-mode-item navbar-brand-item" src="{{ asset('/') }}/assets/images/logo.svg"
                        alt="لوگو" />
                    <img class="dark-mode-item navbar-brand-item" src="{{ asset('/') }}/assets/images/logo-light.svg"
                        alt="لوگو" />
                </a>
                <!-- Logo END -->

                <!-- Main navbar START -->
                <div class="navbar-collapse collapse" id="navbarCollapse">
                    <ul class="navbar-nav navbar-nav-scroll dropdown-hover mx-auto">
                        <!-- Nav item -->
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ asset('/') }}">صفحه اصلی</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link active" href="{{ route('blog') }}">آموزش ها</a>
                        </li>


                        <!-- Nav item -->
                        <li class="nav-item dropdown">
                            <a class="nav-link " href="#">خدمات ما</a>
                        </li>

                        <!-- Nav item -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-auto-close="outside"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">مستندات</a>
                            <div class="dropdown-menu dropdown-menu-size-xl dropdown-menu-center p-xl-3">
                                <div class="row row-cols-1 row-cols-md-2 pt-2">
                                    <!-- Doc menu -->
                                    <div class="col">
                                        <div
                                            class="dropdown-item bg-secondary-hover d-flex align-items-center justify-content-between position-relative text-wrap py-3">
                                            <div class="d-flex">
                                                <!-- Icon -->
                                                <div
                                                    class="icon-md bg-primary bg-opacity-15 text-primary rounded flex-shrink-0">
                                                    <i class="bi bi-file-earmark-text fs-6"></i>
                                                </div>
                                                <!-- Content -->
                                                <div class="mx-3">
                                                    <p class="stretched-link heading-color fw-bold mb-0">
                                                        مستندات
                                                    </p>
                                                    <p class="mb-0 text-body small">
                                                        استفاده از وب سرویس ما برای پروژه های مختلف
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <a class="icon-link icon-link-hover text-primary-hover stretched-link"
                                                href="#" target="_blank"><i class="bi bi-chevron-left"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Doc menu -->
                                    <div class="col">
                                        <div
                                            class="dropdown-item bg-secondary-hover d-flex align-items-center justify-content-between position-relative text-wrap py-3">
                                            <div class="d-flex">
                                                <!-- Icon -->
                                                <div
                                                    class="icon-md bg-pink bg-opacity-15 text-pink rounded flex-shrink-0">
                                                    <i class="bi bi-stickies fs-6"></i>
                                                </div>
                                                <!-- Content -->
                                                <div class="mx-3">
                                                    <p class="stretched-link heading-color fw-bold mb-0">
                                                        برنامه نویسی AI
                                                    </p>
                                                    <p class="mb-0 text-body small">
                                                        برنامه نویسی مدل های AI و پروژه های وب و موبایل
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <a class="icon-link icon-link-hover text-primary-hover stretched-link"
                                                href="#" target="_blank"><i class="bi bi-chevron-left"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Doc menu -->
                                    <div class="col">
                                        <div
                                            class="dropdown-item bg-secondary-hover d-flex align-items-center justify-content-between position-relative text-wrap py-3">
                                            <div class="d-flex">
                                                <!-- Icon -->
                                                <div
                                                    class="icon-md bg-success bg-opacity-15 text-success rounded flex-shrink-0">
                                                    <i class="bi bi-bullseye fs-6"></i>
                                                </div>
                                                <!-- Content -->
                                                <div class="mx-3">
                                                    <p class="stretched-link heading-color fw-bold mb-0">
                                                        تخصص و استراتژی ها
                                                    </p>
                                                    <p class="mb-0 text-body small">
                                                        دانش تخصصی و استراتژی های تیم روحی ترید
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <a class="icon-link icon-link-hover text-primary-hover stretched-link"
                                                href="#" target="_blank"><i class="bi bi-chevron-left"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Doc menu -->
                                    <div class="col">
                                        <div
                                            class="dropdown-item bg-secondary-hover d-flex align-items-center justify-content-between position-relative text-wrap py-3">
                                            <div class="d-flex">
                                                <!-- Icon -->
                                                <div
                                                    class="icon-md bg-warning bg-opacity-15 text-warning rounded flex-shrink-0">
                                                    <i class="bi bi-mask fs-6"></i>
                                                </div>
                                                <!-- Content -->
                                                <div class="mx-3">
                                                    <p class="stretched-link heading-color fw-bold mb-0">
                                                        نکات پلی‌رایت
                                                    </p>
                                                    <p class="mb-0 text-body small">
                                                        نکات و راهنمای عمیق برای اتوماسیون مرورگر بدون
                                                        هد
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <a class="icon-link icon-link-hover text-primary-hover stretched-link"
                                                href="#" target="_blank"><i class="bi bi-chevron-left"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Doc menu -->
                                    <div class="col">
                                        <div
                                            class="dropdown-item bg-secondary-hover d-flex align-items-center justify-content-between position-relative text-wrap py-3">
                                            <div class="d-flex">
                                                <!-- Icon -->
                                                <div
                                                    class="icon-md bg-info bg-opacity-15 text-info rounded flex-shrink-0">
                                                    <i class="bi bi-grid-fill fs-6"></i>
                                                </div>
                                                <!-- Content -->
                                                <div class="mx-3">
                                                    <p class="stretched-link heading-color fw-bold mb-0">
                                                        ادغام‌ها
                                                    </p>
                                                    <p class="mb-0 text-body small">
                                                        استفاده از مزایای ادغام با سرویس‌های دیگر
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <a class="icon-link icon-link-hover text-primary-hover stretched-link"
                                                href="integrations.html" target="_blank"><i
                                                    class="bi bi-chevron-left"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Doc menu -->
                                    <div class="col">
                                        <div
                                            class="dropdown-item bg-secondary-hover d-flex align-items-center justify-content-between position-relative text-wrap py-3">
                                            <div class="d-flex">
                                                <!-- Icon -->
                                                <div
                                                    class="icon-md bg-purple bg-opacity-15 text-purple rounded flex-shrink-0">
                                                    <i class="bi bi-chat-dots fs-6"></i>
                                                </div>
                                                <!-- Content -->
                                                <div class="mx-3">
                                                    <p class="stretched-link heading-color fw-bold mb-0">
                                                        پشتیبانی
                                                    </p>
                                                    <p class="mb-0 text-body small">
                                                        نیاز به کمک دارید؟ پشتیبانی مشتریان ما در خدمت
                                                        شماست
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <a class="icon-link icon-link-hover text-primary-hover stretched-link"
                                                href="#" target="_blank"><i class="bi bi-chevron-left"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Nav item -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact') }}">تماس با ما</a>
                        </li>
                    </ul>
                </div>
                <!-- Main navbar END -->

                <!-- Buttons -->
                <ul class="nav align-items-center dropdown-hover ms-sm-2">
                    <!-- Dark mode option START -->
                    <li class="nav-item dropdown dropdown-animation">
                        <button class="btn btn-link mb-0 px-2 lh-1" id="bd-theme" type="button"
                            aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                class="bi bi-circle-half theme-icon-active fill-mode fa-fw" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
                                <use href="#"></use>
                            </svg>
                        </button>

                        <ul class="dropdown-menu min-w-auto dropdown-menu-end" aria-labelledby="bd-theme">
                            <li class="mb-1">
                                <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="light">
                                    <svg width="16" height="16" fill="currentColor"
                                        class="bi bi-brightness-high-fill fa-fw mode-switch me-1" viewBox="0 0 16 16">
                                        <path
                                            d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
                                        <use href="#"></use>
                                    </svg>روشن
                                </button>
                            </li>
                            <li class="mb-1">
                                <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-moon-stars-fill fa-fw mode-switch me-1"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
                                        <path
                                            d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
                                        <use href="#"></use>
                                    </svg>تیره
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active"
                                    data-bs-theme-value="auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-circle-half fa-fw mode-switch me-1"
                                        viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
                                        <use href="#"></use>
                                    </svg>خودکار
                                </button>
                            </li>
                        </ul>
                    </li>
                    <!-- Dark mode option END -->

                    <!-- Sign up button -->
                    <li class="nav-item ms-2 d-none d-sm-block">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-sm btn-primary-grad mb-0">ورود به سامانه</a>
                        @endguest
                        @auth

                            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary-grad mb-0">پیشخوان</a>
                        @endauth

                    </li>
                    <!-- Responsive navbar toggler -->
                    <li class="nav-item">
                        <button class="navbar-toggler ms-sm-3 p-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-animation">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Logo Nav END -->
    </header>
    <!-- Header END -->

    <!-- **************** MAIN CONTENT START **************** -->
    <main>
        <!-- =======================
Blog detail START -->
        <section class="position-relative overflow-hidden pt-xl-8">
            <!-- Blur decoration START -->
            <div class="position-absolute start-0 top-0">
                <img src="assets/images/elements/grad-shape/blur-decoration-2.svg"
                    class="opacity-1 blur-9 h-300px rotate-335" alt="Grad shape" />
            </div>

            <div class="position-absolute end-0 top-0">
                <img src="assets/images/elements/grad-shape/blur-decoration-2.svg"
                    class="opacity-1 blur-8 h-300px rotate-335" alt="Grad shape" />
            </div>
            <!-- Blur decoration END -->

            <div class="container position-relative pt-4 pt-sm-5">
                <div class="row">
                    <!-- Title -->
                    <div class="col-lg-8 mx-auto text-center mb-4 mb-sm-6">
                        <a href="#" class="badge text-bg-dark mb-4">{{ $Blog->category->title }}</a>
                        <h1 class="h2 mb-0">{{ $Blog->title }}</h1>
                    </div>

                    <!-- Image -->
                    <div class="col-12 mx-auto text-center mb-4 mb-sm-6">
                        <img src="{{ asset('/') . $Blog->cover_image }}" class="img-fluid rounded"
                            alt="{{ $Blog->title }}" style="width: 1270px;height: 715px;" />
                    </div>

                    <!-- Images -->
                    <?php /*
                    <div class="col-12 mb-4 mb-sm-6">
                        <div class="row g-4 g-lg-6">
                            <div class="col-sm-4">
                                <a href="{{ asset('/') }}/assets/images/blog/4by4/01.jpg" data-glightbox=""
                                    data-gallery="image-popup">
                                    <img src="{{ asset('/') }}/assets/images/blog/4by4/01.jpg"
                                        class="rounded h-100" alt="تصویر مقاله" />
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a href="{{ asset('/') }}/assets/images/blog/4by4/03.jpg" data-glightbox=""
                                    data-gallery="image-popup">
                                    <img src="{{ asset('/') }}/assets/images/blog/4by4/03.jpg"
                                        class="rounded h-100" alt="تصویر مقاله" />
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a href="{{ asset('/') }}/assets/images/blog/4by4/04.jpg" data-glightbox=""
                                    data-gallery="image-popup">
                                    <img src="{{ asset('/') }}/assets/images/blog/4by4/04.jpg"
                                        class="rounded h-100" alt="تصویر مقاله" />
                                </a>
                            </div>
                        </div>
                    </div> */
                    ?>

                    <!-- List content -->
                    <div class="col-lg-10 mx-auto mb-4 mb-sm-6">
                        <h6>خلاصه:</h6>
                        <!-- Blockquote -->
                        <blockquote class="card card-body bg-secondary bg-opacity-50 overflow-hidden p-sm-5 mb-5">
                            <!-- Vertical line -->
                            <div class="vr bg-primary-grad h-100 position-absolute top-0 start-0"
                                style="width: 3px; opacity: 100%"></div>

                            <!-- Quote icon -->
                            <span class="display-4 text-primary position-absolute top-0 start-0 opacity-1 mt-n3"><i
                                    class="bi bi-quote"></i></span>

                            <q class="fs-6 heading-color"> {{ $Blog->excerpt }}</q>
                            <div class="blockquote-footer mb-0 mt-3">محتوای کامل در ادامه...</div>
                        </blockquote>

                        <h6></h6>
                        <p>
                            {!! $Blog->content !!}
                        </p>

                        <!-- Tags -->
                        <div class="align-items-center mb-5">
                            <h6>برچسب‌های محبوب:</h6>
                            <ul class="list-inline d-flex flex-wrap gap-2 mb-0">
                                <li class="list-inline-item">
                                    <a class="btn btn-secondary btn-sm mb-lg-0" href="#">بلاگ</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn btn-secondary btn-sm mb-lg-0" href="#">کسب‌وکار</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn btn-secondary btn-sm mb-lg-0" href="#">بوت‌استرپ</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn btn-secondary btn-sm mb-lg-0" href="#">علم داده</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn btn-secondary btn-sm mb-lg-0" href="#">یادگیری عمیق</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn btn-secondary btn-sm mb-lg-0" href="#">یادگیری عمیق</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn btn-secondary btn-sm mb-lg-0" href="#">یادگیری عمیق</a>
                                </li>
                            </ul>
                        </div>

                        <!-- Helpful box -->
                        <div
                            class="bg-secondary bg-opacity-50 rounded d-md-flex justify-content-between align-items-center text-center px-4 py-3">
                            <!-- Title -->
                            <h6 class="mb-0">آیا این مقاله مفید بود؟</h6>
                            <small class="py-3 p-md-0 d-block">۲۵ نفر از ۷۸ نفر این را مفید یافتند</small>
                            <!-- Check buttons -->
                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                <!-- Yes button -->
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" />
                                <label class="btn btn-outline-primary btn-sm mb-0" for="btnradio1"><i
                                        class="bi bi-hand-thumbs-up"></i> بله</label>
                                <!-- No button -->
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" />
                                <label class="btn btn-outline-primary btn-sm mb-0" for="btnradio2">
                                    خیر <i class="bi bi-hand-thumbs-down"></i></label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- =======================
Blog detail END -->

        <!-- =======================
Related blogs START -->
        @if (count($relatedPosts) >= 3)
            <section class="bg-secondary overflow-hidden">
                <div class="container">
                    <!-- Title -->
                    <h3 class="mb-5 text-center">مقالات مرتبط</h3>

                    <!-- Projects START -->
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <!-- Project item -->
                            @foreach ($relatedPosts as $rpost)
                                <div class="swiper-slide">
                                    <article class="card card-img-scale bg-transparent overflow-hidden h-100 p-0">
                                        <!-- Badge -->
                                        <div class="d-flex gap-2 position-absolute top-0 start-0 z-index-2 m-4">
                                            <span class="badge bg-dark">{{ $rpost->category->title }}</span>
                                            <span
                                                class="badge bg-white text-dark">{{ verta($rpost->published_at)->format('d F Y') }}</span>
                                        </div>

                                        <!-- Card image -->
                                        <div class="card-img-scale-wrapper rounded-4">
                                            <a href="{{ route('homeSingleBlog', $post->slug) }}">
                                                <img src="{{ asset('/') . $rpost->cover_image }}"
                                                    class="rounded-4 img-scale" alt="{{ $rpost->title }}"
                                                    style="width: 375px;height: 280px;" />
                                            </a>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body px-2">
                                            <!-- Title -->
                                            <h6 class="card-title mb-2">
                                                <a
                                                    href="{{ route('homeSingleBlog', $post->slug) }}">{{ $rpost->title }}</a>
                                            </h6>
                                            <a class="icon-link icon-link-hover stretched-link"
                                                href="{{ route('homeSingleBlog', $post->slug) }}">مطالعه
                                                بیشتر<i class="bi bi-arrow-left"></i>
                                            </a>
                                        </div>
                                    </article>
                                </div>
                            @endforeach


                        </div>

                        <!-- Slider Pagination -->
                        <div class="swiper-pagination swiper-pagination-primary position-relative mt-0"></div>
                    </div>
                    <!-- Projects END -->
                </div>
            </section>
        @endif
        <!-- =======================
Related blogs END -->
    </main>
    <!-- **************** MAIN CONTENT END **************** -->

    <!-- =======================
Footer START -->
    <footer class="bg-dark pt-6 pt-md-8 position-relative" data-bs-theme="dark">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <!-- Widget 1 START -->
                <div class="col-lg-4">
                    <!-- logo -->
                    <a href="{{ asset('/') }}">
                        <img class="h-40px" src="assets/images/logo-light.svg" alt="logo" />
                    </a>

                    <p class="my-3 my-lg-4">
                        ربات سیگنال مبتنی بر هوش مصنوعی روحی AI، پیشگام در زمینه مشاوره و ارائه سیگنال های سودده در
                        بازار های ارز دیجیتال
                    </p>
                    <!-- Social icon -->
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a class="btn btn-xs btn-icon btn-secondary" href="#"><i
                                    class="bi bi-facebook lh-base"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a class="btn btn-xs btn-icon btn-secondary" href="#"><i
                                    class="bi bi-instagram lh-base"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a class="btn btn-xs btn-icon btn-secondary" href="#"><i
                                    class="bi bi-twitter-x lh-base"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a class="btn btn-xs btn-icon btn-secondary" href="#"><i
                                    class="bi bi-linkedin lh-base"></i></a>
                        </li>
                    </ul>
                </div>
                <!-- Widget 1 END -->

                <!-- Widget 2 START -->
                <div class="col-lg-6 col-xxl-4">
                    <div class="row g-4">
                        <!-- Link block -->
                        <div class="col-6">

                        </div>

                        <!-- Link block -->
                        <div class="col-6">
                        </div>
                    </div>
                </div>
                <!-- Widget 2 END -->
            </div>

            <!-- Divider -->
            <hr class="mt-xl-5 mb-0 opacity-1" />

            <!-- Bottom footer -->
            <div class="d-md-flex justify-content-between align-items-center text-center text-lg-start py-4">
                <!-- copyright text -->
                <div class="text-body small mb-3 mb-md-0">
                    کلیه حقوق محفوظ است ©۱۴۰۴

                </div>

                <!-- Policy link -->
                <ul class="nav d-flex justify-content-center gap-1 mb-0">
                    <li class="nav-item">
                        <a class="nav-link small py-0" href="#">حریم خصوصی</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small py-0 pe-0" href="#">قوانین و شرایط</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- =======================
Footer END -->

    <!-- Back to top -->
    <div class="back-top"></div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('/') }}/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Vendor -->
    <script src="{{ asset('/') }}/assets/vendor/glightbox/js/glightbox.js"></script>
    <script src="{{ asset('/') }}/assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Theme Functions -->
    <script src="{{ asset('/') }}/assets/js/functions.js"></script>
</body>

</html>
