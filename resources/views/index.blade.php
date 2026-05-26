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
    <link rel="shortcut icon" href="{{ asset('/assets/images/favicon.ico') }}" />

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" />
    <!--<link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/swiper/swiper-bundle.min.css') }}" /> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/aos/aos.css') }}" />

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style.css') }}" />

</head>

<body>
    <!-- Header START -->
    <div class="header-absolute">
        <!-- Header START -->
        <header class="header-sticky bg-transparent">

            <!-- Logo Nav START -->
            <nav class="navbar navbar-expand-xl">
                <div class="container">
                    <!-- Logo START -->
                    <a class="navbar-brand me-0" href="{{ asset('/') }}">
                        <img class="light-mode-item navbar-brand-item" src="assets/images/logo.svg" alt="logo" />
                        <img class="dark-mode-item navbar-brand-item" src="assets/images/logo-light.svg"
                            alt="logo" />
                    </a>
                    <!-- Logo END -->

                    <!-- Main navbar START -->
                    <div class="navbar-collapse collapse" id="navbarCollapse">
                        <ul class="navbar-nav navbar-nav-scroll dropdown-hover mx-auto">
                            <!-- Nav item -->
                            <li class="nav-item dropdown">
                                <a class="nav-link active" href="{{ asset('blog/') }}">صفحه اصلی</a>
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
                                            class="bi bi-brightness-high-fill fa-fw mode-switch me-1"
                                            viewBox="0 0 16 16">
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
    </div>
    <!-- Header END -->

    <!-- **************** MAIN CONTENT START **************** -->
    <main>
        <!-- =======================
Hero START -->
        <section class="bg-secondary-grad position-relative overflow-hidden pt-sm-8 pt-lg-9 pb-5">
            <!-- Curve bg -->
            <span class="position-absolute bottom-0 start-0">
                <svg class="fill-body" width="1920" height="254" viewBox="0 0 1920 254" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M556.048 176.63C371.384 97.9289 108.406 143.838 0 176.63V254H1920V0C1863.62 35.5602 1712.53 98.8233 1559.27 67.394C1406.01 35.9648 1206.33 86.6647 1125.65 115.943C1012.72 168.964 740.712 255.331 556.048 176.63Z" />
                </svg>
            </span>

            <div class="container position-relative pt-4 pt-md-0">
                <div class="row">
                    <!-- Content -->
                    <div class="col-md-7 col-lg-6 mb-5 mb-md-0">
                        <h1 class="display-5 mb-3 mb-md-4" style="font-size: 55px">
                            راهنمای هوشمند بازارهای کریپتو
                            <span class="text-primary">ربات روحی AI</span>
                        </h1>
                        <p class="lead mb-3 mb-md-4">تخصص های به روز ما به همراه قدرت نامحدود هوش مصنوعی در بازارهای
                            ارز دیجیتال در اختیار شماست </p>
                        <a class="btn btn-primary-grad icon-link icon-link-hover" href="{{ route('login') }}">ورود به
                            سامانه<i class="bi bi-arrow-left"></i>
                        </a>
                    </div>

                    <!-- Image -->
                    <div class="col-md-5 ms-auto">
                        <img src="{{ asset('assets/images/elements/ai-robot.png') }}" class="aos"
                            data-aos="fade-up" data-aos-delay="200" data-aos-duration="500"
                            data-aos-easing="ease-in-out" alt="AI-robot" />
                    </div>
                </div>
            </div>
        </section>
        <!-- =======================
Hero END -->

        <!-- =======================
Features START -->
        <section class="pt-4 pt-sm-6">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    <!-- Feature item -->
                    <div class="col-md-4 mb-4 mb-sm-0">
                        <div class="card card-body bg-transparent text-center p-0">
                            <!-- Icon -->
                            <div
                                class="icon-xl bg-warning bg-opacity-25 rounded-2 m-auto d-flex justify-content-center align-items-center mb-4">
                                <svg class="text-warning" width="50" height="50" viewBox="0 0 50 50"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M41.5812 5.73541C42.4755 5.6878 43.2267 6.4001 43.2268 7.29567C43.2269 13.8143 42.6409 18.8998 41.541 22.788C40.441 26.6766 38.7961 29.4729 36.6033 31.2632C32.5146 34.6013 27.199 33.9631 21.9796 32.0571C23.4158 28.3359 25.8081 25.0887 29.3104 23.7778C30.1186 23.4753 30.5285 22.575 30.226 21.7668C29.9235 20.9586 29.0232 20.5486 28.215 20.8511C23.8177 22.4969 21.0169 26.2907 19.3648 30.1898C17.4616 27.1276 16.4531 24.1562 16.4066 21.3509C16.3559 18.2854 17.4583 15.553 19.5442 13.2865C23.6288 8.8482 31.3809 6.27845 41.5812 5.73541ZM19.3648 30.1898C19.168 30.6543 18.9875 31.1203 18.8225 31.5843C18.3369 32.9501 17.9746 34.3281 17.7223 35.6495C17.5234 35.2543 17.3162 34.8545 17.1014 34.4543C16.0691 32.531 14.8248 30.5291 13.4449 28.9866C12.1246 27.5107 10.3818 26.1181 8.33203 26.1181C7.46909 26.1181 6.76953 26.8177 6.76953 27.6806C6.76953 28.5436 7.46909 29.2431 8.33203 29.2431C8.97047 29.2431 9.91593 29.7288 11.1159 31.0701C12.2562 32.3448 13.3641 34.0992 14.348 35.9322C15.3244 37.7513 16.1429 39.5801 16.719 40.9601C17.0063 41.6484 17.2317 42.221 17.3845 42.6196C17.4609 42.8189 17.5191 42.9744 17.5578 43.079C17.5771 43.1313 17.5916 43.1709 17.601 43.1967L17.6113 43.2251L17.6135 43.2314L17.6138 43.2323L17.6139 43.2325L17.6139 43.2326C17.8947 44.0168 18.7414 44.4429 19.5386 44.2011C20.3358 43.9592 20.8031 43.1344 20.6007 42.3262C20.3928 41.4965 20.3128 40.0863 20.4886 38.3158C20.6617 36.5727 21.0725 34.5843 21.7669 32.6311C21.8354 32.4387 21.9062 32.2473 21.9796 32.0571C21.5007 31.8822 21.0225 31.6966 20.5462 31.5024C20.2561 31.3842 20.0082 31.1814 19.8348 30.9205C19.6725 30.6764 19.5158 30.4328 19.3648 30.1898Z"
                                        fill="currentColor" />
                                </svg>
                            </div>
                            <!-- Title and content -->
                            <h6 class="mb-3">یادگیری روزانه</h6>
                            <p class="mb-0">مدل های هوش مصنوعی ما روزانه در حال بررسی زنده بازارها و یادگیری از
                                متخصصین ارز دیجیتال میباشند و روزانه رو به توسعه هستند</p>
                        </div>
                    </div>

                    <!-- Feature item -->
                    <div class="col-md-4 mb-4 mb-sm-0">
                        <div class="card card-body bg-transparent text-center p-0">
                            <!-- Icon -->
                            <div
                                class="icon-xl bg-primary bg-opacity-25 rounded-2 m-auto d-flex justify-content-center align-items-center mb-4">
                                <svg class="text-primary" width="50" height="50" viewBox="0 0 50 50"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.4979 31.473C10.1631 31.7097 9.86596 31.9497 9.63182 32.1838C7.74117 34.0744 7.08688 36.5528 6.87055 38.4055C6.76034 39.3494 6.75755 40.1826 6.78209 40.7813C6.7944 41.0822 7.01417 42.7876 7.03109 42.9622C7.20559 42.979 8.91109 43.1988 9.2118 43.2111C9.81073 43.2357 10.6437 43.233 11.5877 43.1226C13.4403 42.9063 15.9188 42.2519 17.8094 40.3613C18.0436 40.1272 18.2835 39.8301 18.5203 39.4953C18.6762 39.2749 18.7542 39.1647 18.9789 38.6159C19.4868 37.3761 19.2859 35.0169 18.5758 33.8809C18.2614 33.3782 17.987 33.1036 17.4383 32.5549C16.8895 32.0061 16.6151 31.7317 16.1123 31.4174C14.9762 30.7074 12.6172 30.5065 11.3774 31.0142C10.8286 31.239 10.7184 31.3169 10.4979 31.473Z"
                                        fill="currentColor" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M35.1041 7.29265C36.9837 6.94724 38.6268 6.82155 39.8045 6.78213C40.5789 6.75622 40.9662 6.74326 41.3677 6.86095C42.1349 7.08586 42.9093 7.86015 43.1341 8.62745C43.2518 9.02893 43.2389 9.41618 43.2129 10.1907C43.1735 11.3683 43.0479 13.0114 42.7024 14.8909C42.0406 18.492 40.5504 23.077 37.1997 26.8837L36.7606 35.3195C36.566 39.0589 33.477 41.9912 29.7324 41.9912C25.9624 41.9912 22.8622 39.0203 22.7014 35.2537L22.6185 33.3108L16.6843 27.3766L14.7413 27.2937C10.9748 27.133 8.00391 24.0326 8.00391 20.2626C8.00391 16.518 10.9361 13.4292 14.6757 13.2345L23.1114 12.7955C26.9181 9.44472 31.5031 7.95447 35.1041 7.29265ZM31.2491 22.9166C31.2491 25.2178 29.3837 27.0833 27.0824 27.0833C24.7812 27.0833 22.9158 25.2178 22.9158 22.9166C22.9158 20.6154 24.7812 18.7499 27.0824 18.7499C29.3837 18.7499 31.2491 20.6154 31.2491 22.9166Z"
                                        fill="currentColor" />
                                </svg>
                            </div>
                            <!-- Title and content -->
                            <h6 class="mb-3">همیشه در دسترس تو</h6>
                            <p class="mb-0">سامانه روحی ترید طوری طراحی شده که موقعیت های سود زا رو بر اساس درخواست
                                شما ارائه میده. پس همیشه در دسترس شماست</p>
                        </div>
                    </div>

                    <!-- Feature item -->
                    <div class="col-md-4 mb-4 mb-sm-0">
                        <div class="card card-body bg-transparent text-center p-0">
                            <!-- Icon -->
                            <div
                                class="icon-xl bg-pink bg-opacity-25 rounded-2 m-auto d-flex justify-content-center align-items-center mb-4">
                                <svg class="text-pink" width="50" height="50" viewBox="0 0 50 50"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M37.1449 4.97022C36.5386 4.66956 35.9396 4.73612 35.5349 4.84152C35.1317 4.94645 34.7405 5.13597 34.3922 5.32947C33.7113 5.70762 32.8413 6.3055 31.809 7.01491L9.1608 22.5792C8.49876 23.034 7.89301 23.4503 7.46149 23.8259C7.0684 24.1682 6.38992 24.8263 6.37528 25.8257C6.3692 26.2417 6.46286 26.6532 6.64847 27.0255C7.0943 27.9201 7.99082 28.2195 8.49332 28.3578C9.04499 28.5097 9.77115 28.6226 10.5648 28.7461L18.0475 29.9103L13.063 38.7134C12.4699 39.7607 11.9669 40.649 11.6597 41.3403C11.5021 41.6953 11.351 42.0944 11.2841 42.5011C11.2163 42.9132 11.2083 43.5007 11.5471 44.0667C11.7835 44.4617 12.1204 44.7869 12.5235 45.0094C13.1011 45.3282 13.6879 45.2997 14.0973 45.2174C14.5013 45.1361 14.8951 44.9713 15.2442 44.8013C15.9244 44.4701 16.7944 43.9361 17.8203 43.3067L40.7311 29.2505C41.3947 28.8434 42.0057 28.4686 42.4465 28.1226C42.8538 27.8028 43.5459 27.1922 43.6203 26.213C43.6515 25.8022 43.5849 25.3899 43.4257 25.0099C43.0465 24.1042 42.1972 23.7428 41.7097 23.5678C41.1824 23.3782 40.4842 23.2153 39.7263 23.0382L31.6572 21.1522L36.7682 11.4183C37.3505 10.3093 37.8413 9.37477 38.1363 8.65395C38.2872 8.28508 38.429 7.87418 38.4855 7.46158C38.5424 7.04718 38.5376 6.44439 38.1674 5.87808C37.9134 5.48979 37.5605 5.17637 37.1449 4.97022Z"
                                        fill="currentColor" />
                                </svg>
                            </div>
                            <!-- Title and content -->
                            <h6 class="mb-3">قابلیت اتو ترید</h6>
                            <p class="mb-0">با توجه به معاملات میتوانید ربات را به سیستم معاملات متصل کنید تا موقعیت
                                های مناسب از دست نرود. ربات به صورت اتوماتیک وارد معاملات میشود.</p>
                        </div>
                    </div>
                </div>
                <!-- Row END -->
            </div>
        </section>
        <!-- =======================
Features END -->

        <!-- =======================
About START -->
        <section class="position-relative pt-0 pb-0 pb-xl-8">
            <div class="container px-xl-7">
                <div class="row align-items-center">

                    <!-- Content -->
                    <div class="col-lg-6 ms-auto">
                        <!-- Skill set -->
                        <div class="d-flex align-items-center" style="max-width: 30rem">
                            <h2 class="display-4 text-primary-grad mb-0">۱۴+</h2>
                            <h6 class="mb-0 ms-3">استراتژی اختصاصی + تجربیات روزانه</h6>
                        </div>

                        <hr class="border-primary border-opacity-50 mt-4 mb-5" />
                        <!-- Divider -->

                        <!-- Tabs and content START -->
                        <!-- Tabs -->
                        <div class="nav nav-pills nav-pills-dark gap-3" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-mission-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-mission" type="button" role="tab"
                                aria-controls="nav-mission" aria-selected="true">
                                <i class="bi bi-bullseye me-2"></i> سیگنال ها
                            </button>
                            <button class="nav-link" id="nav-vision-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-vision" type="button" role="tab"
                                aria-controls="nav-vision" aria-selected="false">
                                <i class="bi bi-eye me-2"></i>چشم‌انداز ما
                            </button>
                            <button class="nav-link" id="nav-goal-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-goal" type="button" role="tab" aria-controls="nav-goal"
                                aria-selected="false">
                                <i class="bi bi-trophy me-2"></i>هدف ما
                            </button>
                        </div>

                        <!-- Tab content -->
                        <div class="tab-content mt-4" id="nav-tabContent">
                            <!-- Mission content -->
                            <div class="tab-pane fade show active" id="nav-mission" role="tabpanel"
                                aria-labelledby="nav-mission-tab" tabindex="0">
                                <p class="mb-2">
                                    سیستم اختصاصی که روزانه در حال یادگیری میباشد و برای هر استراتژی بک تست های زیادی را
                                    انجام میدهد. نواقص را رفع میکند و تلاش میکند تا روزانه بهترین سیگنال ها را ارائه
                                    دهد.
                                </p>
                                <!-- List -->
                                <ul class="list-group list-group-borderless mb-3">
                                    <li class="list-group-item heading-color d-flex pb-0">
                                        <i class="bi bi-patch-check text-success ms-2"></i>
                                        سیگنال های اختصاصی در تمامی بازار های ارز دیجیتال
                                    </li>
                                    <li class="list-group-item heading-color d-flex pb-0">
                                        <i class="bi bi-patch-check text-success ms-2"></i>
                                        موقعیت یابی پیشرفته با ارائه درصد ریسک
                                    </li>
                                    <li class="list-group-item heading-color d-flex pb-0">
                                        <i class="bi bi-patch-check text-success ms-2"></i>
                                        بررسی وضعیت و ارائه نکات مدیریت سرمایه
                                    </li>
                                </ul>
                            </div>

                            <!-- Vision content -->
                            <div class="tab-pane fade" id="nav-vision" role="tabpanel"
                                aria-labelledby="nav-vision-tab" tabindex="0">
                                <p class="mb-2">
                                    طراحی مؤثر هویت برند شما را منتقل می‌کند، اعتماد ایجاد
                                    می‌کند و می‌تواند به طور قابل توجهی بر نرخ تبدیل و وفاداری
                                    مشتری تأثیر بگذارد.
                                </p>
                                <!-- List -->
                                <ul class="list-group list-group-borderless mb-3">
                                    <li class="list-group-item heading-color d-flex pb-0">
                                        <i class="bi bi-patch-check text-success me-2"></i>راه‌حل‌های سفارشی
                                    </li>
                                    <li class="list-group-item heading-color d-flex pb-0">
                                        <i class="bi bi-patch-check text-success me-2"></i>سابقه
                                        اثبات شده
                                    </li>
                                    <li class="list-group-item heading-color d-flex pb-0">
                                        <i class="bi bi-patch-check text-success me-2"></i>مقرون
                                        به صرفه بودن
                                    </li>
                                </ul>
                            </div>

                            <!-- Goal content -->
                            <div class="tab-pane fade" id="nav-goal" role="tabpanel" aria-labelledby="nav-goal-tab"
                                tabindex="0">
                                <p class="mb-2">
                                    ما طیف وسیعی از ابزارها، راهنماها و بهترین روش‌ها را برای
                                    کمک به شما در ایجاد طرح‌ها و وب‌سایت‌ها ارائه می‌دهیم.
                                </p>
                                <!-- List -->
                                <ul class="list-group list-group-borderless mb-3">
                                    <li class="list-group-item heading-color d-flex pb-0">
                                        <i class="bi bi-patch-check text-success me-2"></i>پیشگامان دیجیتال
                                    </li>
                                    <li class="list-group-item heading-color d-flex pb-0">
                                        <i class="bi bi-patch-check text-success me-2"></i>یادگیری
                                        مستمر
                                    </li>
                                    <li class="list-group-item heading-color d-flex pb-0">
                                        <i class="bi bi-patch-check text-success me-2"></i>الهام‌بخش تحول
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Tabs and content END -->
                    </div>

                    <!-- Image -->
                    <div class="col-lg-6 position-relative pe-xl-6 mb-5 mb-lg-0">
                        <div class="position-absolute top-0 start-0 ms-n6">
                            <img src="{{ asset('assets/images/elements/grad-shape/blur-decoration.svg') }}"
                                class="blur-7 opacity-3" alt="Grad shape" />
                        </div>
                        <img src="{{ asset('assets/images/about/07.jpg') }}"
                            class="rounded-4 z-index-2 position-relative" alt="about image" />
                    </div>

                </div>
            </div>

            <!-- AI hand -->
            <div class="position-absolute mt-4 z-index-9 d-none d-xl-block"
                style="right: -100px;bottom: -200px;z-index: 99999;">
                <img src="{{ asset('assets/images/elements/ai-hand.png') }}" class="aos ms-9 ps-6"
                    data-aos="fade-left" data-aos-delay="100" data-aos-duration="800" data-aos-easing="ease-in-out"
                    alt="ai hand" />
            </div>
        </section>
        <!-- =======================
About END -->

        <!-- =======================
Services START -->
        <section class="py-0 position-relative">
            <!-- Robot decoration -->
            <div class="position-absolute end-0 bottom-0 me-lg-7 mb-n7 z-index-2 d-none d-md-block">
                <img src="{{ asset('assets/images/elements/ai-robot-2.png') }}" class="aos h-300px"
                    data-aos="fade-left" data-aos-delay="100" data-aos-duration="800" data-aos-easing="ease-in-out"
                    alt="robot image" />
            </div>

            <div class="bg-dark position-relative overflow-hidden py-8">
                <!-- AI pattern -->
                <div class="position-absolute top-0 start-0 mt-n9">
                    <img src="{{ asset('assets/images/elements/ai-pattern.png') }}" class="mt-n7" alt="" />
                </div>
                <!-- Curve bg -->
                <div class="position-absolute top-0 start-0 mt-n3">
                    <svg class="fill-body" width="1920" height="146" viewBox="0 0 1920 146"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1457.5 123.815C1692.5 141 1811.59 85.4373 1920 50.5V3L0 0.5V23.4269V141C56.3835 103.114 283.285 126.587 439.5 141C607.5 156.5 776.5 126.673 873.5 108.5C1087 68.5 1255.23 109.024 1457.5 123.815Z" />
                    </svg>
                </div>
                <!-- Blur decoration -->
                <div class="position-absolute bottom-0 end-0 mb-n8 me-n9">
                    <img src="{{ asset('assets/images/elements/grad-shape/blur-decoration-2.svg') }}"
                        class="opacity-2 blur-9" alt="Grad shape" />
                </div>

                <div class="container position-relative pt-5 pt-sm-8" data-bs-theme="dark">
                    <div class="row">
                        <!-- Title and content -->
                        <div class="col-lg-4 mb-5 mb-lg-0">
                            <h2 class="mb-3 mb-lg-4">
                                ویژگی ها و مزایای ربات روحی AI
                            </h2>
                            <p class="mb-3 mb-lg-4">
                                دستیار پرسرعت روحی AI به صورت زنده تمامی رمز هارز های مورد نظر شما رو بررسی میکنه و برای
                                ورود به معاملات به صورت آنی به شما پیامک و اعلان میزنه! و هم میتونه با اجازه شده به صورت
                                اتوماتیک ترید کنه...
                            </p>
                            <a href="{{ route('login') }}" class="btn btn-primary">همین حالا ثبت نام کنید</a>
                        </div>

                        <!-- Services START -->
                        <div class="col-lg-7 ms-auto hover-opacity-fade">
                            <!-- Service item -->
                            <div class="hover-item d-flex align-items-center border-bottom position-relative py-4">
                                <!-- Icon -->
                                <img src="{{ asset('assets/images/services/geo-shape/01.svg') }}" class="me-3"
                                    alt="service icon" />
                                <h6 class="mb-0">مشاور اختصاصی هوش مصنوعی</h6>
                                <a href="#"
                                    class="icon-link icon-link-hover text-body stretched-link me-auto"><i
                                        class="bi bi-arrow-left fs-5"></i>
                                </a>
                            </div>

                            <!-- Service item -->
                            <div class="hover-item d-flex align-items-center border-bottom position-relative py-4">
                                <!-- Icon -->
                                <img src="{{ asset('assets/images/services/geo-shape/02.svg') }}" class="me-3"
                                    alt="service icon" />
                                <h6 class="mb-0">ابزارهای اختصاصی ترید</h6>
                                <a href="#"
                                    class="icon-link icon-link-hover text-body stretched-link me-auto"><i
                                        class="bi bi-arrow-left fs-5"></i>
                                </a>
                            </div>

                            <!-- Service item -->
                            <div class="hover-item d-flex align-items-center border-bottom position-relative py-4">
                                <!-- Icon -->
                                <img src="{{ asset('assets/images/services/geo-shape/03.svg') }}" class="me-3"
                                    alt="service icon" />
                                <h6 class="mb-0">تحلیل زنده تمامی رمز ارز ها</h6>
                                <a href="#"
                                    class="icon-link icon-link-hover text-body stretched-link me-auto"><i
                                        class="bi bi-arrow-left fs-5"></i>
                                </a>
                            </div>

                            <!-- Service item -->
                            <div class="hover-item d-flex align-items-center border-bottom position-relative py-4">
                                <!-- Icon -->
                                <img src="{{ asset('assets/images/services/geo-shape/04.svg') }}" class="me-3"
                                    alt="service icon" />
                                <h6 class="mb-0">اتوماسیون مبتنی بر هوش مصنوعی</h6>
                                <a href="#"
                                    class="icon-link icon-link-hover text-body stretched-link me-auto"><i
                                        class="bi bi-arrow-left fs-5"></i>
                                </a>
                            </div>

                            <!-- Service item -->
                            <div class="hover-item d-flex align-items-center border-bottom position-relative py-4">
                                <!-- Icon -->
                                <img src="{{ asset('assets/images/services/geo-shape/05.svg') }}" class="me-3"
                                    alt="service icon" />
                                <h6 class="mb-0">مدیریت سرمایه شما و بررسی ریسک معاملات</h6>
                                <a href="#"
                                    class="icon-link icon-link-hover text-body stretched-link me-auto"><i
                                        class="bi bi-arrow-left fs-5"></i>
                                </a>
                            </div>
                        </div>
                        <!-- Services END -->
                    </div>
                    <!-- Row END -->

                    <!-- Technology logos -->
                    <div class="inner-container text-center mt-8">
                        <h4>فناوری‌ها و ابزارهای هوش مصنوعی که استفاده می‌کنیم</h4>
                        <!-- Logo list -->
                        <ul class="list-inline d-flex justify-content-center flex-wrap gap-4 my-5 my-lg-6">
                            <!-- Logo item -->
                            <li class="list-inline-item me-0">
                                <a href="#"
                                    class="icon-xl btn-transition bg-white border border-white border-opacity-10 d-flex justify-content-center align-items-center rounded-2"
                                    style="--bs-bg-opacity: 0.05">
                                    <img src="{{ asset('assets/images/client/icons/08.svg') }}" class="h-40px"
                                        alt="icon" />
                                </a>
                            </li>

                            <!-- Logo item -->
                            <li class="list-inline-item me-0">
                                <a href="#"
                                    class="icon-xl btn-transition bg-white border border-white border-opacity-10 d-flex justify-content-center align-items-center rounded-2"
                                    style="--bs-bg-opacity: 0.05">
                                    <img src="{{ asset('assets/images/client/icons/04.svg') }}" class="h-40px"
                                        alt="icon" />
                                </a>
                            </li>

                            <!-- Logo item -->
                            <li class="list-inline-item me-0">
                                <a href="#"
                                    class="icon-xl btn-transition bg-white border border-white border-opacity-10 d-flex justify-content-center align-items-center rounded-2"
                                    style="--bs-bg-opacity: 0.05">
                                    <img src="{{ asset('assets/images/client/icons/12.svg') }}" class="h-40px"
                                        alt="icon" />
                                </a>
                            </li>

                            <!-- Logo item -->
                            <li class="list-inline-item me-0">
                                <a href="#"
                                    class="icon-xl btn-transition bg-white border border-white border-opacity-10 d-flex justify-content-center align-items-center rounded-2"
                                    style="--bs-bg-opacity: 0.05">
                                    <img src="{{ asset('assets/images/client/icons/09.svg') }}" class="h-40px"
                                        alt="icon" />
                                </a>
                            </li>

                            <!-- Logo item -->
                            <li class="list-inline-item me-0">
                                <a href="#"
                                    class="icon-xl btn-transition bg-white border border-white border-opacity-10 d-flex justify-content-center align-items-center rounded-2"
                                    style="--bs-bg-opacity: 0.05">
                                    <img src="{{ asset('assets/images/client/icons/05.svg') }}" class="h-40px"
                                        alt="icon" />
                                </a>
                            </li>

                            <!-- Logo item -->
                            <li class="list-inline-item me-0">
                                <a href="#"
                                    class="icon-xl btn-transition bg-white border border-white border-opacity-10 d-flex justify-content-center align-items-center rounded-2"
                                    style="--bs-bg-opacity: 0.05">
                                    <img src="{{ asset('assets/images/client/icons/03.svg') }}" class="h-40px"
                                        alt="icon" />
                                </a>
                            </li>

                            <!-- Logo item -->
                            <li class="list-inline-item me-0">
                                <a href="#"
                                    class="icon-xl btn-transition bg-white border border-white border-opacity-10 d-flex justify-content-center align-items-center rounded-2"
                                    style="--bs-bg-opacity: 0.05">
                                    <img src="{{ asset('assets/images/client/icons/02.svg') }}" class="h-40px"
                                        alt="icon" />
                                </a>
                            </li>

                            <!-- Logo item -->
                            <li class="list-inline-item me-0">
                                <a href="#"
                                    class="icon-xl btn-transition bg-white border border-white border-opacity-10 d-flex justify-content-center align-items-center rounded-2"
                                    style="--bs-bg-opacity: 0.05">
                                    <img src="{{ asset('assets/images/client/icons/10.svg') }}" class="h-40px"
                                        alt="icon" />
                                </a>
                            </li>
                        </ul>

                        <!-- Button -->
                        <a class="btn btn-outline-secondary icon-link icon-link-hover" href="#">کشف قابلیت‌های
                            هوش مصنوعی ما<i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- =======================
Services END -->

        <!-- =======================
Projects START -->
        <?php /*
        <section class="overflow-hidden">
            <div class="container mb-5">
                <!-- Title and slider button -->
                <div class="row">
                    <!-- Title -->
                    <div class="col-sm-8 col-lg-5">
                        <h2 class="text-center text-sm-start mb-0">
                            آموزش های روحی ترید
                        </h2>
                    </div>

                    <!-- Slider button -->
                    <div class="col-sm-4 col-lg-5 ms-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end gap-3 position-relative mt-3">
                            <a href="#"
                                class="btn btn-secondary btn-lg btn-icon rounded-circle mb-0 swiper-button-prev-project rtl-flip">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                            <a href="#"
                                class="btn btn-secondary btn-lg btn-icon rounded-circle mb-0 swiper-button-next-project rtl-flip"><i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects START -->
            <div class="swiper swiper-outside-n5 px-4 px-sm-5"
                data-swiper-options='{
		"spaceBetween": 50,
		"loop": true,
		"autoplay":false,
		"navigation":{
			"nextEl":".swiper-button-next-project",
			"prevEl":".swiper-button-prev-project"
		},
		"breakpoints": {
			"576": {"slidesPerView": 1},
			"768": {"slidesPerView": 3},
			"992": {"slidesPerView": 3},
			"1200": {"slidesPerView": 4}
		}}'>
                <div class="swiper-wrapper">
                    <!-- Project item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale card-content-hover card-metro-hover rounded-4">
                            <!-- Card Image -->
                            <img src="assets/images/portfolio/4by4/01.jpg" class="img-scale" alt="portfolio-img" />

                            <!-- Card elements -->
                            <div class="card-img-overlay hover-content d-flex flex-column align-items-start p-5">
                                <!-- Client logo -->
                                <img src="assets/images/client/logo-light/05.svg" class="h-30px" alt="client logo" />
                                <!-- Title -->
                                <div class="card-text mt-auto">
                                    <h6 class="mb-0">
                                        <a href="#" class="text-white stretched-link">پلتفرم بینش مشتری مبتنی بر
                                            هوش مصنوعی</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale card-content-hover card-metro-hover rounded-4">
                            <!-- Card Image -->
                            <img src="assets/images/portfolio/4by4/02.jpg" class="img-scale" alt="portfolio-img" />

                            <!-- Card elements -->
                            <div class="card-img-overlay hover-content d-flex flex-column align-items-start p-5">
                                <!-- Client logo -->
                                <img src="assets/images/client/logo-light/06.svg" class="h-30px" alt="client logo" />
                                <!-- Title -->
                                <div class="card-text mt-auto">
                                    <h6 class="mb-0">
                                        <a href="#" class="text-white stretched-link">پشتیبانی خودکار مشتری با
                                            NLP</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale card-content-hover card-metro-hover rounded-4">
                            <!-- Card Image -->
                            <img src="assets/images/portfolio/4by4/03.jpg" class="img-scale" alt="portfolio-img" />

                            <!-- Card elements -->
                            <div class="card-img-overlay hover-content d-flex flex-column align-items-start p-5">
                                <!-- Client logo -->
                                <img src="assets/images/client/logo-light/08.svg" class="h-30px" alt="client logo" />
                                <!-- Title -->
                                <div class="card-text mt-auto">
                                    <h6 class="mb-0">
                                        <a href="#" class="text-white stretched-link">مدیریت هوشمند موجودی برای
                                            خرده‌فروشی</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale card-content-hover card-metro-hover rounded-4">
                            <!-- Card Image -->
                            <img src="assets/images/portfolio/4by4/04.jpg" class="img-scale" alt="portfolio-img" />

                            <!-- Card elements -->
                            <div class="card-img-overlay hover-content d-flex flex-column align-items-start p-5">
                                <!-- Client logo -->
                                <img src="assets/images/client/logo-light/02.svg" class="h-30px" alt="client logo" />
                                <!-- Title -->
                                <div class="card-text mt-auto">
                                    <h6 class="mb-0">
                                        <a href="#" class="text-white stretched-link">تشخیص
                                            تقلب مبتنی بر هوش مصنوعی
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale card-content-hover card-metro-hover rounded-4">
                            <!-- Card Image -->
                            <img src="assets/images/portfolio/4by4/05.jpg" class="img-scale" alt="portfolio-img" />

                            <!-- Card elements -->
                            <div class="card-img-overlay hover-content d-flex flex-column align-items-start p-5">
                                <!-- Client logo -->
                                <img src="assets/images/client/logo-light/03.svg" class="h-30px" alt="client logo" />
                                <!-- Title -->
                                <div class="card-text mt-auto">
                                    <h6 class="mb-0">
                                        <a href="#" class="text-white stretched-link">پلتفرم بینش مشتری مبتنی بر
                                            هوش مصنوعی</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Projects END -->

            <!-- CTA -->
            <div class="inner-container-small bg-primary-grad rounded-3 text-center py-3 mt-6 mx-3 mx-md-auto">
                <p class="text-white mb-0 px-2 px-sm-5 px-md-0">
                    🔥 هر روز کلی نکات آموزشی جدید در بخش آموزش روحی ترید
                    <a href="#" class="fw-semibold hover-underline-animation text-white">امروز شروع
                        کنید</a>
                </p>
            </div>
        </section>
        */
        ?>
        <!-- =======================
Projects END -->

        <!-- =======================
Skill sets START -->
        <section class="pt-0">
            <div class="container">
                <div class="row">
                    <!-- Skill sets item -->
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between">
                            <div class="text-center">
                                <!-- Number -->
                                <div class="d-flex">
                                    <h4 class="purecounter display-2 mb-0" data-purecounter-start="0"
                                        data-purecounter-end="980" data-purecounter-delay="300">
                                        0
                                    </h4>
                                    <span class="display-2 text-primary mb-0">+</span>
                                </div>
                                <!-- Content -->
                                <p class="px-3 py-2 bg-body mt-n3 mt-md-n4 position-relative">
                                    اشتراک ربات تهیه شده
                                </p>
                            </div>

                            <div class="vr bg-primary-grad opacity-1 my-3"></div>
                            <!-- Divider -->
                        </div>
                    </div>

                    <!-- Skill sets item -->
                    <div class="col-md-4 text-center">
                        <!-- Number -->
                        <div class="d-flex justify-content-center">
                            <h4 class="purecounter display-2 mb-0" data-purecounter-start="0"
                                data-purecounter-end="119" data-purecounter-delay="300">
                                0
                            </h4>
                            <span class="display-2 text-purple mb-0">+</span>
                        </div>
                        <!-- Content -->
                        <p class="px-3 py-2 bg-body mt-n3 mt-md-n4 position-relative">
                            سیگنال سود ده امروز
                        </p>
                    </div>

                    <!-- Skill sets item -->
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between">
                            <div class="vr bg-primary-grad opacity-1 my-3"></div>
                            <!-- Divider -->

                            <div class="text-center">
                                <!-- Number -->
                                <div class="d-flex">
                                    <span class="display-2 heading-color mb-0">></span>
                                    <h4 class="purecounter display-2 mb-0" data-purecounter-start="0"
                                        data-purecounter-end="13" data-purecounter-delay="300">
                                        0
                                    </h4>
                                    <span class="display-2 text-pink mb-0">استراتژی</span>
                                </div>
                                <!-- Content -->
                                <p class="px-3 py-2 bg-body mt-n3 mt-md-n4 position-relative">
                                    در حال آنالیز بازارها
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- =======================
Skill sets END -->

        <?php /*
        <!-- =======================
Testimonials START -->
        <section class="pt-0">
            <div class="container position-relative">
                <!-- Decoration START -->
                <!-- Avatar decoration -->
                <div
                    class="avatar avatar-xl flex-shrink-0 position-absolute top-0 start-0 mt-6 ms-n3 z-index-2 d-none d-lg-block">
                    <img class="avatar-img rounded-circle position-relative" src="assets/images/avatar/10.jpg"
                        alt="avatar" />
                </div>

                <!-- Avatar decoration -->
                <div
                    class="avatar flex-shrink-0 position-absolute top-0 start-50 translate-middle-x ms-n9 mt-n2 z-index-2 d-none d-lg-block">
                    <img class="avatar-img rounded-circle position-relative" src="assets/images/avatar/02.jpg"
                        alt="avatar" />
                </div>

                <!-- Avatar decoration -->
                <div
                    class="avatar avatar-lg flex-shrink-0 position-absolute top-0 end-0 me-7 mt-n4 z-index-2 d-none d-lg-block">
                    <img class="avatar-img rounded-circle position-relative" src="assets/images/avatar/06.jpg"
                        alt="avatar" />
                </div>

                <!-- Avatar decoration -->
                <div
                    class="avatar avatar-xl flex-shrink-0 position-absolute bottom-50 end-0 mb-n8 me-n4 z-index-2 d-none d-lg-block">
                    <img class="avatar-img rounded-circle position-relative" src="assets/images/avatar/09.jpg"
                        alt="avatar" />
                </div>

                <!-- Avatar decoration -->
                <div
                    class="avatar avatar-lg flex-shrink-0 position-absolute bottom-0 end-0 me-9 mb-4 z-index-2 d-none d-lg-block">
                    <img class="avatar-img rounded-circle position-relative" src="assets/images/avatar/03.jpg"
                        alt="avatar" />
                </div>

                <!-- Avatar decoration -->
                <div
                    class="avatar flex-shrink-0 position-absolute bottom-0 start-0 ms-8 mb-n3 z-index-2 d-none d-lg-block">
                    <img class="avatar-img rounded-circle position-relative" src="assets/images/avatar/01.jpg"
                        alt="avatar" />
                </div>
                <!-- Decoration END -->

                <div class="bg-secondary position-relative rounded-4 overflow-hidden p-5 py-md-7">
                    <!-- Pattern decoration -->
                    <span class="position-absolute end-0 top-0 me-n5 mt-3">
                        <svg class="text-primary opacity-1" width="453" height="138" viewBox="0 0 453 138"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M452.303 -0.000286079V0.792195H337.93L303.294 35.4047H202.253L196.613 29.7642L130.255 29.7642L106.527 53.5151L26.2305 53.5151V52.7227L106.201 52.7227L129.928 28.995L196.939 28.995L202.58 34.6356H302.968L337.603 -0.000286079H452.303Z"
                                fill="currentColor" />
                            <path
                                d="M452.297 78.3851V79.1543L354.473 79.1543L328.321 53.0026H273.617L263.502 42.9102L213.436 42.9102L180.921 75.425L87.6186 75.425L81.5818 69.3882H51.9805V68.5957H81.9081L87.9449 74.6558H180.595L213.109 42.1177L263.828 42.1177L273.944 52.2334H328.648L354.799 78.3851L452.297 78.3851Z"
                                fill="currentColor" />
                            <path
                                d="M452.299 90.7848V91.554L348.531 91.554L323.405 66.4279L220.127 66.4279L177.706 108.849L137.64 108.849L128.806 100.038H41.75V99.2457H129.132L137.966 108.079L177.38 108.079L219.801 65.6587L323.731 65.6587L348.858 90.7848L452.299 90.7848Z"
                                fill="currentColor" />
                            <path
                                d="M452.301 111.437V112.23L297.302 112.23L281.732 96.6364L209.29 96.6364L183.931 121.996H80.0469V121.226H183.605L208.964 95.8672L282.058 95.8672L297.628 111.437L452.301 111.437Z"
                                fill="currentColor" />
                            <path
                                d="M271.641 108.08V108.849H216.191L192.207 132.833H119.648V132.064H191.88L215.864 108.08H271.641Z"
                                fill="currentColor" />
                            <path
                                d="M197.777 45.2641L175.704 67.3369H119.648V66.5677H175.378L197.218 44.7281L197.777 45.2641Z"
                                fill="currentColor" />
                            <path
                                d="M180.202 83.4674V84.2365L95.8964 84.2365L90.2092 89.9004H5.46094V89.1312H89.9062L95.5701 83.4674L180.202 83.4674Z"
                                fill="currentColor" />
                            <path
                                d="M44.5774 97.3488C43.0482 95.8196 40.5689 95.8196 39.0398 97.3488C37.5106 98.878 37.5106 101.357 39.0398 102.886C40.5689 104.416 43.0482 104.416 44.5774 102.886C46.1066 101.357 46.1066 98.878 44.5774 97.3488Z"
                                fill="currentColor" />
                            <path
                                d="M8.3079 86.6461C6.77872 85.1169 4.29941 85.1169 2.77023 86.6461C1.24104 88.1753 1.24104 90.6546 2.77023 92.1838C4.29941 93.713 6.77871 93.713 8.3079 92.1838C9.83708 90.6546 9.83709 88.1753 8.3079 86.6461Z"
                                fill="currentColor" />
                            <path
                                d="M54.761 66.5524C53.2318 65.0232 50.7525 65.0232 49.2234 66.5524C47.6942 68.0816 47.6942 70.5609 49.2234 72.0901C50.7525 73.6192 53.2318 73.6192 54.761 72.0901C56.2902 70.5609 56.2902 68.0816 54.761 66.5524Z"
                                fill="currentColor" />
                            <path
                                d="M30.0721 53.0713C30.0311 50.9091 28.2451 49.1896 26.0829 49.2306C23.9207 49.2716 22.2012 51.0576 22.2422 53.2198C22.2832 55.382 24.0692 57.1015 26.2314 57.0605C28.3936 57.0195 30.1131 55.2335 30.0721 53.0713Z"
                                fill="currentColor" />
                            <path
                                d="M123.492 66.9646C123.492 64.802 121.739 63.0488 119.576 63.0488C117.413 63.0488 115.66 64.802 115.66 66.9646C115.66 69.1272 117.413 70.8804 119.576 70.8804C121.739 70.8804 123.492 69.1272 123.492 66.9646Z"
                                fill="currentColor" />
                            <path
                                d="M84.0815 121.506C84.0815 119.343 82.3284 117.59 80.1658 117.59C78.0031 117.59 76.25 119.343 76.25 121.506C76.25 123.669 78.0031 125.422 80.1658 125.422C82.3284 125.422 84.0815 123.669 84.0815 121.506Z"
                                fill="currentColor" />
                            <path
                                d="M123.652 129.693C122.122 128.164 119.643 128.164 118.114 129.693C116.585 131.223 116.585 133.702 118.114 135.231C119.643 136.76 122.122 136.76 123.652 135.231C125.181 133.702 125.181 131.223 123.652 129.693Z"
                                fill="currentColor" />
                            <path
                                d="M221.777 80.112C220.247 78.5828 217.768 78.5828 216.239 80.112C214.71 81.6411 214.71 84.1204 216.239 85.6496C217.768 87.1788 220.247 87.1788 221.777 85.6496C223.306 84.1204 223.306 81.6411 221.777 80.112Z"
                                fill="currentColor" />
                            <path d="M169.945 49.2281V54.9619H175.679V49.2281H169.945Z" fill="currentColor" />
                            <path d="M155.848 49.2281V54.9619H161.581V49.2281H155.848Z" fill="currentColor" />
                            <path d="M141.766 49.2281V54.9619H147.499V49.2281H141.766Z" fill="currentColor" />
                            <path d="M308.512 80.0182V85.752H314.246V80.0182H308.512Z" fill="currentColor" />
                            <path d="M283.973 80.0182V85.752H289.706V80.0182H283.973Z" fill="currentColor" />
                            <path d="M259.449 80.0182V85.752H265.183V80.0182H259.449Z" fill="currentColor" />
                        </svg>
                    </span>

                    <!-- Pattern decoration -->
                    <span class="position-absolute start-0 bottom-0 mb-3 ms-n5">
                        <svg class="text-primary opacity-1" width="453" height="138" viewBox="0 0 453 138"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M-0.00186092 -0.000286079V0.792195H114.371L149.007 35.4047H250.047L255.688 29.7642L322.046 29.7642L345.774 53.5151L426.07 53.5151V52.7227L346.1 52.7227L322.373 28.995L255.362 28.995L249.721 34.6356H149.333L114.697 -0.000286079H-0.00186092Z"
                                fill="currentColor" />
                            <path
                                d="M0.00357036 78.3851V79.1543L97.8277 79.1543L123.979 53.0026H178.684L188.799 42.9102L238.865 42.9102L271.38 75.425L364.682 75.425L370.719 69.3882H400.32V68.5957H370.393L364.356 74.6558H271.706L239.191 42.1177L188.473 42.1177L178.357 52.2334H123.653L97.5014 78.3851L0.00357036 78.3851Z"
                                fill="currentColor" />
                            <path
                                d="M0.00180058 90.7848V91.554L103.769 91.554L128.896 66.4279L232.174 66.4279L274.595 108.849L314.661 108.849L323.495 100.038H410.551V99.2457H323.169L314.335 108.079L274.921 108.079L232.5 65.6587L128.569 65.6587L103.443 90.7848L0.00180058 90.7848Z"
                                fill="currentColor" />
                            <path
                                d="M0.000130941 111.437V112.23L154.999 112.23L170.569 96.6364L243.01 96.6364L268.37 121.996H372.254V121.226H268.696L243.337 95.8672L170.243 95.8672L154.673 111.437L0.000130941 111.437Z"
                                fill="currentColor" />
                            <path
                                d="M180.66 108.08V108.849H236.11L260.094 132.833H332.652V132.064H260.421L236.436 108.08H180.66Z"
                                fill="currentColor" />
                            <path
                                d="M254.524 45.2641L276.596 67.3369H332.652V66.5677H276.923L255.083 44.7281L254.524 45.2641Z"
                                fill="currentColor" />
                            <path
                                d="M272.099 83.4674V84.2365L356.404 84.2365L362.092 89.9004H446.84V89.1312H362.395L356.731 83.4674L272.099 83.4674Z"
                                fill="currentColor" />
                            <path
                                d="M407.723 97.3488C409.253 95.8196 411.732 95.8196 413.261 97.3488C414.79 98.878 414.79 101.357 413.261 102.886C411.732 104.416 409.253 104.416 407.723 102.886C406.194 101.357 406.194 98.878 407.723 97.3488Z"
                                fill="currentColor" />
                            <path
                                d="M443.993 86.6461C445.522 85.1169 448.001 85.1169 449.531 86.6461C451.06 88.1753 451.06 90.6546 449.531 92.1838C448.001 93.713 445.522 93.713 443.993 92.1838C442.464 90.6546 442.464 88.1753 443.993 86.6461Z"
                                fill="currentColor" />
                            <path
                                d="M397.54 66.5524C399.069 65.0232 401.548 65.0232 403.077 66.5524C404.607 68.0816 404.607 70.5609 403.077 72.0901C401.548 73.6192 399.069 73.6192 397.54 72.0901C396.011 70.5609 396.011 68.0816 397.54 66.5524Z"
                                fill="currentColor" />
                            <path
                                d="M422.229 53.0713C422.27 50.9091 424.056 49.1896 426.218 49.2306C428.38 49.2716 430.1 51.0576 430.059 53.2198C430.018 55.382 428.232 57.1015 426.069 57.0605C423.907 57.0195 422.188 55.2335 422.229 53.0713Z"
                                fill="currentColor" />
                            <path
                                d="M328.809 66.9646C328.809 64.802 330.562 63.0488 332.725 63.0488C334.887 63.0488 336.641 64.802 336.641 66.9646C336.641 69.1272 334.887 70.8804 332.725 70.8804C330.562 70.8804 328.809 69.1272 328.809 66.9646Z"
                                fill="currentColor" />
                            <path
                                d="M368.219 121.506C368.219 119.343 369.972 117.59 372.135 117.59C374.298 117.59 376.051 119.343 376.051 121.506C376.051 123.669 374.298 125.422 372.135 125.422C369.972 125.422 368.219 123.669 368.219 121.506Z"
                                fill="currentColor" />
                            <path
                                d="M328.649 129.693C330.178 128.164 332.658 128.164 334.187 129.693C335.716 131.223 335.716 133.702 334.187 135.231C332.658 136.76 330.178 136.76 328.649 135.231C327.12 133.702 327.12 131.223 328.649 129.693Z"
                                fill="currentColor" />
                            <path
                                d="M230.524 80.112C232.053 78.5828 234.533 78.5828 236.062 80.112C237.591 81.6411 237.591 84.1204 236.062 85.6496C234.533 87.1788 232.053 87.1788 230.524 85.6496C228.995 84.1204 228.995 81.6411 230.524 80.112Z"
                                fill="currentColor" />
                            <path d="M282.355 49.2281V54.9619H276.622V49.2281H282.355Z" fill="currentColor" />
                            <path d="M296.453 49.2281V54.9619H290.719V49.2281H296.453Z" fill="currentColor" />
                            <path d="M310.535 49.2281V54.9619H304.801V49.2281H310.535Z" fill="currentColor" />
                            <path d="M143.789 80.0182V85.752H138.055V80.0182H143.789Z" fill="currentColor" />
                            <path d="M168.328 80.0182V85.752H162.594V80.0182H168.328Z" fill="currentColor" />
                            <path d="M192.852 80.0182V85.752H187.118V80.0182H192.852Z" fill="currentColor" />
                        </svg>
                    </span>

                    <!-- Slider contents START -->
                    <div class="row position-relative">
                        <div class="col-md-8 mx-auto">
                            <!-- Slider START -->
                            <div class="swiper mt-2 mt-md-4"
                                data-swiper-options='{
						"spaceBetween": 30,
						"autoplay":{
							"delay": 4000,
							"disableOnInteraction": false,
							"pauseOnMouseEnter": true
						},
						"pagination":{
							"el":".swiper-pagination",
							"clickable":"true"
						}}'>
                                <div class="swiper-wrapper mb-5">
                                    <!-- Testimonials item -->
                                    <div class="swiper-slide">
                                        <div class="text-center">
                                            <!-- Rating star -->
                                            <ul class="list-inline mb-3">
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-half text-warning"></i>
                                                </li>
                                            </ul>
                                            <!-- Testimonials text -->
                                            <blockquote class="mb-4">
                                                <p class="fs-6 heading-color mb-0">
                                                    اشتیاق ما برای تعالی مشتری تنها یکی از دلایلی است که
                                                    ما رهبر بازار هستیم. ما همیشه بسیار سخت کار کرده‌ایم
                                                    تا بهترین تجربه را به مشتریان خود بدهیم.
                                                </p>
                                            </blockquote>
                                            <!-- Testimonials info -->
                                            <div>
                                                <h6 class="mb-0">ژاکلین میلر</h6>
                                                <span>طراح محصول</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Testimonials item -->
                                    <div class="swiper-slide">
                                        <div class="text-center">
                                            <!-- Rating star -->
                                            <ul class="list-inline mb-3">
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                            </ul>
                                            <!-- Testimonials text -->
                                            <blockquote class="mb-4">
                                                <p class="fs-6 heading-color mb-0">
                                                    تیم آنها فراتر از انتظار ما عمل کرد تا نیازهای ما را
                                                    درک کند و راه‌حلی ارائه دهد که فراتر از انتظارات ما
                                                    بود. تخصصی که آنها در طول فرآیند نشان دادند واقعاً
                                                    چشمگیر بود.
                                                </p>
                                            </blockquote>

                                            <!-- Testimonials info -->
                                            <div>
                                                <h6 class="mb-0">لوئیس فرگوسن</h6>
                                                <span>توسعه‌دهنده وب</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Testimonials item -->
                                    <div class="swiper-slide">
                                        <div class="text-center">
                                            <!-- Rating star -->
                                            <ul class="list-inline mb-3">
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                                <li class="list-inline-item fs-6 me-0">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                </li>
                                            </ul>
                                            <!-- Testimonials text -->
                                            <blockquote class="mb-4">
                                                <p class="fs-6 heading-color mb-0">
                                                    کیفیت خدمات و توجه به جزئیات که تیم ارائه می‌دهد
                                                    قابل تحسین است. آنها نه تنها به وعده‌های خود عمل
                                                    کردند، بلکه ارزش افزوده زیادی نیز به پروژه ما اضافه
                                                    کردند.
                                                </p>
                                            </blockquote>
                                            <!-- Testimonials info -->
                                            <div>
                                                <h6 class="mb-0">اما واتسون</h6>
                                                <span>طراح UI/UX</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Slider Pagination -->
                                <div
                                    class="swiper-pagination swiper-pagination-primary position-absolute bottom-0 mb-3">
                                </div>
                            </div>
                            <!-- Slider END -->
                        </div>
                    </div>
                    <!-- Slider contents END -->
                </div>
            </div>
        </section>
        <!-- =======================
Testimonials END -->

        <!-- =======================
Client START -->
        <section class="pt-0 pb-md-6">
            <div class="container">
                <div class="row">
                    <!-- Title and content -->
                    <div class="col-lg-4 mb-4 mb-sm-5 mb-lg-0">
                        <h2 class="mb-3 mb-lg-4">ابزارهای پیشرفته بازارهای مالی</h2>
                        <p class="mb-3 mb-lg-4">
                            اینجا در کنار هوش مصنوعی و تحلیل های حرفه ای، ابزارهای پیشرفته برای بازارهای مالی نیز در
                            اختیار شماست
                        </p>
                        <a class="btn btn-primary icon-link icon-link-hover" href="{{ route('login') }}">ورود به
                            سامانه<i class="bi bi-arrow-left"></i>
                        </a>
                    </div>

                    <!-- Client logo START -->
                    <div class="col-lg-8">
                        <div class="row border-md-transparent g-0">
                            <!-- Client item -->
                            <div class="col-6 col-md-4 border-primary border-opacity-10 border-end border-bottom">
                                <div class="text-center p-3 p-md-5">
                                    <img src="assets/images/client/logo-light/03.svg" class="dark-mode-item h-40px"
                                        alt="client logo" />
                                    <img src="assets/images/client/logo-dark/03.svg" class="light-mode-item h-40px"
                                        alt="client logo" />
                                </div>
                            </div>
                            <!-- Client item -->
                            <div class="col-6 col-md-4 border-primary border-opacity-10 border-end border-bottom">
                                <div class="text-center p-3 p-md-5">
                                    <img src="assets/images/client/logo-light/08.svg" class="dark-mode-item h-40px"
                                        alt="client logo" />
                                    <img src="assets/images/client/logo-dark/08.svg" class="light-mode-item h-40px"
                                        alt="client logo" />
                                </div>
                            </div>
                            <!-- Client item -->
                            <div class="col-6 col-md-4 border-primary border-opacity-10 border-bottom">
                                <div class="text-center p-3 p-md-5">
                                    <img src="assets/images/client/logo-light/09.svg" class="dark-mode-item h-40px"
                                        alt="client logo" />
                                    <img src="assets/images/client/logo-dark/09.svg" class="light-mode-item h-40px"
                                        alt="client logo" />
                                </div>
                            </div>
                            <!-- Client item -->
                            <div class="col-6 col-md-4 border-primary border-opacity-10 border-end">
                                <div class="text-center p-3 p-md-5">
                                    <img src="assets/images/client/logo-light/02.svg" class="dark-mode-item h-40px"
                                        alt="client logo" />
                                    <img src="assets/images/client/logo-dark/02.svg" class="light-mode-item h-40px"
                                        alt="client logo" />
                                </div>
                            </div>
                            <!-- Client item -->
                            <div class="col-6 col-md-4 border-primary border-opacity-10 border-end">
                                <div class="text-center p-3 p-md-5">
                                    <img src="assets/images/client/logo-light/10.svg" class="dark-mode-item h-40px"
                                        alt="client logo" />
                                    <img src="assets/images/client/logo-dark/10.svg" class="light-mode-item h-40px"
                                        alt="client logo" />
                                </div>
                            </div>
                            <!-- Client item -->
                            <div class="col-6 col-md-4 border-primary border-opacity-10">
                                <div class="text-center p-3 p-md-5">
                                    <img src="assets/images/client/logo-light/06.svg" class="dark-mode-item h-40px"
                                        alt="client logo" />
                                    <img src="assets/images/client/logo-dark/06.svg" class="light-mode-item h-40px"
                                        alt="client logo" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Client logo END -->
                </div>
            </div>
        </section>
        <!-- =======================
Client END -->
*/
        ?>
        <!-- =======================
Awards START -->
        <section class="position-relative py-0">
            <!-- AI robot decoration -->
            <div class="position-absolute top-100 start-0 translate-middle ms-9 mt-n8 z-index-9 d-none d-lg-block">
                <img src="{{ asset('assets/images/elements/ai-robot-3.png') }}" class="aos" data-aos="fade-up"
                    data-aos-delay="100" data-aos-duration="800" data-aos-easing="ease-in-out" alt="ai robot" />
            </div>

            <div class="bg-secondary-grad position-relative overflow-hidden py-6 py-md-8">
                <!-- Curve decoration -->
                <span class="position-absolute top-0 start-0 mt-n5">
                    <svg class="fill-body" width="1930" height="137" viewBox="0 0 1930 137" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M464.909 117.12C228.685 132.607 108.971 82.5335 0 51.0476V1.5L1930 0V26.649V132.607C1873.32 98.4636 1645.24 119.618 1488.21 132.607C1319.34 146.576 1149.46 119.696 1051.95 103.318C837.339 67.2694 668.231 103.79 464.909 117.12Z" />
                    </svg>
                </span>

                <div class="container ps-lg-6 pt-6">
                    <div class="row g-4 align-items-center">
                        <!-- Title -->
                        <div class="col-lg-9 col-xxl-4">
                            <h3 class="text-center text-sm-start">مجوز ها و گواهینامه ها</h3>
                        </div>

                        <!-- Awards -->
                        <div class="col-lg-9 col-xxl-8 d-sm-flex justify-content-sm-between ms-auto">
                            <!-- Item -->
                            <div class="card card-body bg-transparent text-center m-auto" style="max-width: 15rem">
                                <!-- Award content -->
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('assets/images/elements/wing-1.svg') }}" class="rtl-flip"
                                        alt="award wing" />
                                    <ul class="list-group list-group-borderless align-items-center pe-0">
                                        <li class="list-group-item fw-semibold heading-color d-flex pb-0">
                                            لوگوی ای نماد
                                        </li>
                                        <li class="list-group-item fw-semibold text-primary d-flex pb-0">
                                            ۱۴۰۲
                                        </li>
                                    </ul>
                                    <img src="{{ asset('assets/images/elements/wing-2.svg') }}" class="rtl-flip"
                                        alt="award wing" />
                                </div>

                                <!-- Info -->
                                <span class="fw-semibold opacity-6">بر اساس</span>
                                <img src="{{ asset('assets/images/elements/clutch.svg') }}" class="h-30px mt-2"
                                    alt="logo" />
                            </div>

                            <!-- Item -->
                            <div class="card card-body bg-transparent text-center m-auto" style="max-width: 15rem">
                                <!-- Award content -->
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('assets/images/elements/wing-1.svg') }}" class="rtl-flip"
                                        alt="award wing" />
                                    <ul class="list-group list-group-borderless align-items-center pe-0">
                                        <li class="list-group-item fw-semibold heading-color d-flex pb-0">
                                            زرین پال
                                        </li>
                                        <li class="list-group-item fw-semibold text-primary d-flex pb-0">
                                            ۱۴۰۱
                                        </li>
                                    </ul>
                                    <img src="{{ asset('assets/images/elements/wing-2.svg') }}" class="rtl-flip"
                                        alt="award wing" />
                                </div>

                                <!-- Info -->
                                <span class="fw-semibold opacity-6">بر اساس</span>
                                <img src="{{ asset('assets/images/elements/fwa.svg') }}" class="h-30px mt-2"
                                    alt="logo" />
                            </div>

                            <!-- Item -->
                            <div class="card card-body bg-transparent text-center m-auto" style="max-width: 15rem">
                                <!-- Award content -->
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('assets/images/elements/wing-1.svg') }}" class="rtl-flip"
                                        alt="award wing" />
                                    <ul class="list-group list-group-borderless align-items-center pe-0">
                                        <li class="list-group-item fw-semibold heading-color d-flex pb-0">
                                            لوگوی دانش بنیان
                                        </li>
                                        <li class="list-group-item fw-semibold text-primary d-flex pb-0">
                                            ۱۴۰۰
                                        </li>
                                    </ul>
                                    <img src="{{ asset('assets/images/elements/wing-2.svg') }}" class="rtl-flip"
                                        alt="award wing" />
                                </div>

                                <!-- Info -->
                                <span class="fw-semibold opacity-6">بر اساس</span>
                                <img src="{{ asset('assets/images/elements/dribbble.svg') }}" class="h-30px mt-2"
                                    alt="logo" />
                            </div>
                        </div>
                    </div>
                    <!-- Row END -->
                </div>
            </div>
        </section>
        <!-- =======================
Awards END -->
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
                        <img class="h-40px" src="{{ asset('assets/images/logo-light.svg') }}" alt="logo" />
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
                            <a referrerpolicy='origin' target='_blank'
                                href='https://trustseal.enamad.ir/?id=5776462&Code=8S5qtXuFHhCuJUbiXMmm7sk8hJ1zU6Qj'><img
                                    referrerpolicy='origin'
                                    src='https://trustseal.enamad.ir/logo.aspx?id=5776462&Code=8S5qtXuFHhCuJUbiXMmm7sk8hJ1zU6Qj'
                                    alt='' style='cursor:pointer'
                                    code='8S5qtXuFHhCuJUbiXMmm7sk8hJ1zU6Qj'></a>
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
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!--Vendors-->
    <!-- <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script> -->
    <script src="{{ asset('assets/vendor/purecounterjs/dist/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>

    <!-- Theme Functions -->
    <script src="{{ asset('assets/js/functions.js') }}"></script>
    <style>
        @media(max-width: 1000px) {
            .ms-n6 {
                margin-left: 0px !important;
            }

            .start-0 {
                left: none !important;
            }
        }
    </style>

    @include('dashboard.sections.installApp')



</body>

</html>
