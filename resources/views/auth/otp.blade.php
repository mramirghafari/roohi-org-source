<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <title>کد ورود به سامانه روحی AI</title>

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
    <link rel="shortcut icon" href="{{ asset('/') }}/assets/images/favicon.ico" />

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/') }}/assets/vendor/bootstrap-icons/bootstrap-icons.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}/assets/css/style.css" />

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
                            <img src="{{ asset('/') }}/assets/images/elements/ufo.png" class="h-100px rotate-343"
                                alt="تصویر یوفو" />
                        </div>
                        <!-- Blur ring decoration -->
                        <div class="position-absolute bottom-0 end-0 mb-n8 me-n5 d-none d-sm-block">
                            <img src="{{ asset('/') }}/assets/images/elements/grad-shape/blur-decoration.svg"
                                class="blur-8 opacity-2" alt="شکل گرادیان" />
                        </div>

                        <!-- Forgot password START -->
                        <div
                            class="card card-body bg-body bg-opacity-25 bg-blur border border-white border-opacity-10 position-relative rounded-4 shadow-primary text-center p-4 p-sm-5">
                            <!-- Title -->
                            <h1 class="mb-2 h3 fw-bold">کد امنیتی ورود</h1>
                            <p class="mb-0">
                                کد ارسال شده به شماره موبایل {{ session('mobile_otp') }} را وارد نمایید:
                            </p>
                            <!-- Form START -->
                            <form id="otpForm" class="mt-2 mt-sm-4" method="POST" action="{{ route('otpProcess') }}">
                                @csrf
                                <!-- Email -->
                                <div class="mb-3">
                                    <input type="text" id="otp_full" inputmode="numeric"
                                        autocomplete="one-time-code" pattern="[0-9]*"
                                        style="display: inline; width:1px; height:1px; opacity:0;">

                                    <div id="otp"
                                        class="inputs d-flex flex-row-reverse justify-content-center mt-2">
                                        <input type="text" inputmode="numeric" maxlength="1" class="otp-input"
                                            name="otp[]" autofocus>
                                        <input type="text" inputmode="numeric" maxlength="1" class="otp-input"
                                            name="otp[]">
                                        <input type="text" inputmode="numeric" maxlength="1" class="otp-input"
                                            name="otp[]">
                                        <input type="text" inputmode="numeric" maxlength="1" class="otp-input"
                                            name="otp[]">
                                        <input type="text" inputmode="numeric" maxlength="1" class="otp-input"
                                            name="otp[]">
                                        <input type="text" inputmode="numeric" maxlength="1" class="otp-input"
                                            name="otp[]">
                                    </div>
                                </div>
                                <small style="color: red;" id="mobileError"></small>

                                <!-- Button -->
                                <div class="d-grid my-2">
                                    <button id="send" type="submit" class="btn btn-primary">
                                        ورود به سامانه
                                    </button>
                                </div>

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
                        <style>
                            /* حذف اسپینر عددی در کروم و فایرفاکس */
                            input[type=number]::-webkit-inner-spin-button,
                            input[type=number]::-webkit-outer-spin-button {
                                -webkit-appearance: none;
                                margin: 0;
                            }

                            input[type=number] {
                                -moz-appearance: textfield;
                                text-align: center !important;
                                direction: ltr !important;
                            }
                        </style>
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
    <script src="{{ asset('/') }}/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Functions -->
    <script src="{{ asset('/') }}/assets/js/functions.js"></script>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        const otpForm = document.getElementById('otpForm');

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                // اگر کاراکتر وارد شد، برو به بعدی
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                // اگر آخرین فیلد پر شد، فرم را ارسال کن
                if (index === inputs.length - 1 && input.value.length === 1) {
                    const sendBtn = document.getElementById('send');

                    // Disable button + change text
                    sendBtn.disabled = true;
                    sendBtn.innerText = 'در حال بررسی...';
                    otpForm.submit();
                }
            });

            input.addEventListener('keydown', (e) => {
                // اگر بک‌اسپیس زد و خالی بود، برو به قبلی
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>

    <script>
        (function() {
            const boxes = Array.from(document.querySelectorAll('.otp-input'));
            const full = document.getElementById('otp_full');

            function distribute(code) {
                if (!code) return;
                const digits = String(code).replace(/\D/g, '').slice(0, boxes.length);
                if (digits.length === 0) return;

                boxes.forEach((b, i) => b.value = digits[i] || '');
                const lastFilled = boxes[Math.min(digits.length, boxes.length) - 1];
                lastFilled?.focus();

                // اگر خواستی اتومات سابمیت:
                // if (digits.length === boxes.length) boxes[0].form?.submit();
            }

            // 1) وقتی OTP روی input مخفی autofill شد
            if (full) {
                full.addEventListener('input', () => distribute(full.value));
            }

            // 2) Paste روی هر کدوم از باکس‌ها (کاربر کد رو کامل paste کنه)
            boxes.forEach((box, idx) => {
                box.addEventListener('paste', (e) => {
                    const text = (e.clipboardData || window.clipboardData).getData('text');
                    if (text) {
                        e.preventDefault();
                        distribute(text);
                    }
                });

                box.addEventListener('input', (e) => {
                    // فقط یک رقم
                    box.value = box.value.replace(/\D/g, '').slice(0, 1);
                    if (box.value && idx < boxes.length - 1) boxes[idx + 1].focus();
                });

                box.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !box.value && idx > 0) {
                        boxes[idx - 1].focus();
                    }
                });
            });

            // 3) WebOTP (Android Chrome) -> پر کردن خودکار واقعی
            (async () => {
                if (!('OTPCredential' in window)) return;

                try {
                    const ac = new AbortController();
                    setTimeout(() => ac.abort(), 2 * 60 * 1000);

                    const otp = await navigator.credentials.get({
                        otp: {
                            transport: ['sms']
                        },
                        signal: ac.signal
                    });

                    if (otp && otp.code) {
                        // اول بریز تو full تا autofill هم یک‌دست باشد
                        if (full) full.value = otp.code;
                        distribute(otp.code);
                    }
                } catch (e) {}
            })();
        })();
    </script>


    @include('dashboard.sections.installApp')
</body>

</html>
