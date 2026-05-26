<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <title>ورود به سامانه روحی AI</title>

    <!-- Meta Tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="امیر غفاری" />
    <meta name="description" content="ربات ROOHI AI" />
    
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
    <link rel="shortcut icon" href="assets/images/favicon.ico" />

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap-icons/bootstrap-icons.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" />

</head>

<body>
    <!-- **************** MAIN CONTENT START **************** -->
    <main>
        <section class="bg-secondary position-relative vh-100">
            <div class="container h-100 d-flex flex-column justify-content-center">
                <div class="row justify-content-center align-items-center">
                    <!-- Main content START -->
                    <div class="col-sm-10 col-md-8 col-lg-7 col-xl-6 col-xxl-5 position-relative">
                        <!-- UFO decoration -->
                        <div class="vert-move position-absolute top-0 start-0 ms-n5 mt-n5 z-index-9 d-none d-sm-block">
                            <img src="assets/images/elements/ufo.png" class="h-100px rotate-343" alt="تصویر یوفو" />
                        </div>
                        <!-- Blur ring decoration -->
                        <div class="position-absolute bottom-0 end-0 mb-n8 me-n5 d-none d-sm-block">
                            <img src="assets/images/elements/grad-shape/blur-decoration.svg" class="blur-8 opacity-2"
                                alt="شکل گرادیان" />
                        </div>

                        <!-- Forgot password START -->
                        <div
                            class="card card-body bg-body bg-opacity-25 bg-blur border border-white border-opacity-10 position-relative rounded-4 shadow-primary text-center p-4 p-sm-5">
                            <!-- Title -->
                            <h1 class="mb-2 h3 fw-bold">ورود به ربات ROOHI AI</h1>
                            <p class="mb-0">
                                شماره همراه خود را وارد کنید
                            </p>
                            <!-- Form START -->
                            <form id="mobileForm" class="mt-2 mt-sm-4" method="POST" action="{{ route('sendOtp') }}">
                                @csrf
                                <!-- Email -->
                                <div class="mb-3">
                                    <input type="text" id="number" class="form-control" maxlength="11"
                                        value="09" name="mobile" oninput="validateMobile(this)"
                                        onfocus="addPrefix(this)" onblur="keepPrefix(this)"
                                        placeholder="مثال: 09121234567" />
                                    <style>
                                        /* حذف اسپینر عددی در کروم و فایرفاکس */
                                        input[type=number]::-webkit-inner-spin-button,
                                        input[type=number]::-webkit-outer-spin-button {
                                            -webkit-appearance: none;
                                            margin: 0;
                                        }

                                        input[type=number] {
                                            -moz-appearance: textfield;
                                            text-align: left !important;
                                            direction: ltr !important;
                                        }
                                    </style>

                                    <script>
                                        function addPrefix(el) {
                                            if (!el.value.startsWith("09")) {
                                                el.value = "09";
                                            }
                                        }

                                        function keepPrefix(el) {
                                            if (!el.value.startsWith("09") || el.value.length < 2) {
                                                el.value = "09";
                                            }
                                        }

                                        function validateMobile(el) {
                                            // فقط عدد بپذیرد
                                            el.value = el.value.replace(/\D/g, "");

                                            // اگر حذف شد تکیه "09" را دوباره بگذارد
                                            if (!el.value.startsWith("09")) {
                                                el.value = "09";
                                            }

                                            // حداکثر ۱۱ رقم
                                            if (el.value.length > 11) {
                                                el.value = el.value.slice(0, 11);
                                            }
                                        }
                                    </script>
                                </div>
                                <small style="color: red;" id="mobileError"></small>
                                @if ($errors->any())
                                    <span class="text-danger mb-2 d-block">
                                        {{ implode('', $errors->all(':message')) }}
                                    </span>
                                @endif

                                @if (session('error'))
                                    <span class="text-danger mb-2 d-block">{{ session('error') }}</span>
                                @endif

                                <!-- Button -->
                                <div class="d-grid my-2">
                                    <button id="send" type="submit" class="btn btn-primary">
                                        ارسال کد
                                    </button>
                                </div>

                                <script>
                                    function validateMobile(el) {
                                        el.value = el.value.replace(/\D/g, "");
                                        if (!el.value.startsWith("09")) el.value = "09";
                                        if (el.value.length > 11) el.value = el.value.slice(0, 11);
                                    }

                                    document.getElementById("mobileForm").addEventListener("submit", function(e) {
                                        const mobile = document.getElementById("mobile").value;
                                        const errorBox = document.getElementById("mobileError");

                                        if (mobile.length !== 11) {
                                            e.preventDefault(); // جلو سابمیت رو می‌گیره
                                            errorBox.innerHTML = "لطفا شماره موبایل را به صورت کامل وارد نمایید.";
                                        } else {
                                            errorBox.innerHTML = "";
                                        }
                                    });
                                </script>

                                <div class="mb-4 text-center">
                                    <a href="{{ asset('/') }}" class="text-primary-hover"><i
                                            class="bi bi-arrow-right"></i> بازگشت به صفحه اصلی</a>
                                </div>

                                <!-- Copyright -->
                                <div class="text-body small mt-3">
                                    کلیه حقوق محفوظ است ©1404 .
                                </div>
                            </form>
                            <!-- Form END -->
                        </div>
                        <!-- Forgot password END -->
                    </div>
                </div>
                <!-- Row END -->
            </div>
        </section>
    </main>
    <!-- **************** MAIN CONTENT END **************** -->

    <!-- Back to top -->
    <div class="back-top"></div>

    <!-- Bootstrap JS -->
    <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Functions -->
    <script src="assets/js/functions.js"></script>

    @include('dashboard.sections.installApp')
</body>

</html>
