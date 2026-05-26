<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <title>ربات هوشمند روحی AI</title>

    <!-- Meta Tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="Amir Ghaffari" />
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
    <link rel="shortcut icon" href="{{ asset('/') }}/assets/images/favicon.ico" />

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/') }}/assets/vendor/bootstrap-icons/bootstrap-icons.css" />
    <!--<link rel="stylesheet" type="text/css" href="{{ asset('/') }}/assets/vendor/swiper/swiper-bundle.min.css" /> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}/assets/vendor/aos/aos.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}/assets/css/style.css" />

</head>

<body>
    <!-- Header START -->
    <header class="header-sticky header-absolute" data-bs-theme="dark">
        <!-- Logo Nav START -->
        <nav class="navbar navbar-expand-xl">
            <div class="container">
                <!-- Logo START -->
                <a class="navbar-brand me-0" href="{{ asset('/') }}">
                    <img class="light-mode-item navbar-brand-item" src="assets/images/logo.svg" alt="logo" />
                    <img class="dark-mode-item navbar-brand-item" src="assets/images/logo-light.svg" alt="logo" />
                </a>
                <!-- Logo END -->

                <!-- Main navbar START -->
                <div class="navbar-collapse collapse" id="navbarCollapse">
                    <ul class="navbar-nav navbar-nav-scroll dropdown-hover mx-auto">
                        <!-- Nav item -->
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ asset('blog/') }}">صفحه اصلی</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ route('blog') }}">آموزش ها</a>
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
                                    </svg>تاریک
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

                    <!-- Schedule button -->
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
Hero START -->
        <section class="bg-dark position-relative overflow-hidden pt-xl-8" data-bs-theme="dark">
            <!-- Blur decoration -->
            <div class="position-absolute bottom-0 end-0 mb-n9">
                <img src="assets/images/elements/grad-shape/blur-decoration-2.svg" class="opacity-2 blur-9"
                    alt="شکل گرادیان" />
            </div>

            <div class="container position-relative">
                <!-- Title and content -->
                <h3 class="display-5 mt-3" style="font-size: 25px">ثبت نام سیستم اقتصادی اکو روحی</h3>

                <p class="mb-1" style="font-size: 25px">ظرفیت 20 نفر</p>
                <div class="price-box mb-4">
                    <p class="old-price">قیمت اصلی
                        <strike style="color: red; font-size: 22px"> 30 میلیون تومان</strike>
                    </p>
                    <p class="new-price text-center" style="color: #29ff29;font-size: 23px">فرصت ویژه پرداخت فقط 4
                        میلیون و 900
                        هزار تومان</p>
                </div>

            </div>
        </section>
        <!-- =======================
Hero END -->

        <!-- =======================
Contact info START -->
        <section class="pt-0 mt-n6 mt-lg-n7 mt-xl-n8">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="card payment-card">

                            <div class="card-body p-4">
                                @if (session('payment_message'))
                                    <div
                                        class="alert @if (session('payment_state') === 'success') alert-success @elseif(session('payment_state') === 'cancel') alert-warning @elseif(session('payment_state') === 'expired') alert-secondary @else alert-danger @endif">
                                        <div>{{ session('payment_message') }}</div>
                                        @if (session('payment_state') === 'success')
                                            <hr>
                                            <div><strong>کد پیگیری:</strong> {{ session('payment_tracking_code') }}
                                            </div>
                                            @if (session('payment_ref_id'))
                                                <div><strong>شماره مرجع زرین پال:</strong>
                                                    {{ session('payment_ref_id') }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif


                                <form action="{{ route('payment.request') }}" method="POST" novalidate>
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="full_name">نام کامل</label>
                                        <input type="text"
                                            class="form-control @error('full_name') is-invalid @enderror"
                                            id="full_name" name="full_name" value="{{ old('full_name') }}"
                                            placeholder="مثال: علی رضایی" maxlength="150"
                                            pattern="^(?!.*[0-9۰-۹])[\u0600-\u06FF\u200C\s]+$"
                                            oninput="this.value=this.value.replace(/[^\u0600-\u06FF\u200C\s]/g,'');"
                                            required>
                                        @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="mobile">موبایل</label>
                                        <input type="text"
                                            class="form-control @error('mobile') is-invalid @enderror" id="mobile"
                                            name="mobile" value="{{ old('mobile') }}" placeholder="09123456789"
                                            inputmode="numeric" maxlength="11" pattern="09[0-9]{9}"
                                            oninput="this.value=this.value.replace(/[۰-۹]/g,function(d){return '۰۱۲۳۴۵۶۷۸۹'.indexOf(d);}).replace(/[٠-٩]/g,function(d){return '٠١٢٣٤٥٦٧٨٩'.indexOf(d);}).replace(/\D/g,'').slice(0,11);"
                                            required>
                                        @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-success btn-pay w-100">پرداخت و ثبت
                                        نام</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- =======================
Contact info END -->

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

    <!-- Theme Functions -->
    <script src="{{ asset('/') }}/assets/js/functions.js"></script>
</body>

</html>
