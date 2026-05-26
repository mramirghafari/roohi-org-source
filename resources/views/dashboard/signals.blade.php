<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ env('DASHBOARD_THEME_PATH') }}/assets/" data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8"/>
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" name="viewport"/>
    <title>سیگنال های من - روحی بات</title>
    <meta content="" name="description"/>
    <!-- Favicon -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon"/>
    <!-- Icons -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet"/>
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet"/>
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/fonts/flag-icons.css" rel="stylesheet"/>
    <!-- Core CSS -->
    <link  href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/css/rtl/core.css" rel="stylesheet"/>
    <link  href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet"/>
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/css/demo.css" rel="stylesheet"/>
    <!-- Vendors CSS -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/node-waves/node-waves.css" rel="stylesheet"/>
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet"/>
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/typeahead-js/typeahead.css" rel="stylesheet"/>
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css" rel="stylesheet"/>
    <!-- Page CSS -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/css/pages/app-chat.css" rel="stylesheet"/>
    <!-- Helpers -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/css/rtl.css" rel="stylesheet"/>
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
                    <div class="app-chat card overflow-hidden">
                        <div class="row g-0">
                            <!-- Sidebar Left -->
                            <div class="col app-chat-sidebar-left app-sidebar overflow-hidden" id="app-chat-sidebar-left">
                                <div class="chat-sidebar-left-user sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
                                    <div class="avatar avatar-xl avatar-online">
                                        <img alt="آواتار" class="rounded-circle" src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/avatars/1.png"/>
                                    </div>
                                    <h5 class="mt-2 mb-0">جواد عزتی</h5>
                                    <span>Admin</span>
                                    <i class="ti ti-x ti-sm cursor-pointer close-sidebar" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-left"></i>
                                </div>
                                <div class="sidebar-body px-4 pb-4">
                                    <div class="my-4">
                                        <small class="text-muted text-uppercase">درباره</small>
                                        <textarea class="form-control chat-sidebar-left-user-about mt-3" id="chat-sidebar-left-user-about" maxlength="120" rows="4"> علاقه‌مند به فناوری و نوآوری، همیشه در جستجوی چالش‌های جدید در علوم کامپیوتر و فناوری اطلاعات. با تجربه در توسعه نرم‌افزارهای متن‌باز و تحقیقات در حوزه‌های هوش مصنوعی و اینترنت اشیاء، به دنبال ارتقاء مهارت‌های فنی و مشارکت در پروژه‌های نوآورانه</textarea>
                                    </div>
                                    <div class="my-4">
                                        <small class="text-muted text-uppercase">وضعیت</small>
                                        <div class="d-grid gap-2 mt-3">
                                            <div class="form-check form-check-success">
                                                <input checked class="form-check-input" id="user-active" name="chat-user-status" type="radio" value="active"/>
                                                <label class="form-check-label" for="user-active">فعال</label>
                                            </div>
                                            <div class="form-check form-check-danger">
                                                <input class="form-check-input" id="user-busy" name="chat-user-status" type="radio" value="busy"/>
                                                <label class="form-check-label" for="user-busy">گرفتار</label>
                                            </div>
                                            <div class="form-check form-check-warning">
                                                <input class="form-check-input" id="user-away" name="chat-user-status" type="radio" value="away"/>
                                                <label class="form-check-label" for="user-away">دور</label>
                                            </div>
                                            <div class="form-check form-check-secondary">
                                                <input class="form-check-input" id="user-offline" name="chat-user-status" type="radio" value="offline"/>
                                                <label class="form-check-label" for="user-offline">آفلاین</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-4">
                                        <small class="text-muted text-uppercase">تنظیمات</small>
                                        <ul class="list-unstyled d-grid gap-2 me-3 mt-3">
                                            <li class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="ti ti-message me-1 ti-sm"></i>
                                                    <span class="align-middle">تایید دو مرحله ای</span>
                                                </div>
                                                <label class="switch switch-primary me-4 switch-sm">
                                                    <input checked="" class="switch-input" type="checkbox"/>
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on"></span>
                                                        <span class="switch-off"></span>
                                                    </span>
                                                </label>
                                            </li>
                                            <li class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="ti ti-bell me-1 ti-sm"></i>
                                                    <span class="align-middle">اعلان</span>
                                                </div>
                                                <label class="switch switch-primary me-4 switch-sm">
                                                    <input class="switch-input" type="checkbox"/>
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on"></span>
                                                        <span class="switch-off"></span>
                                                    </span>
                                                </label>
                                            </li>
                                            <li>
                                                <i class="ti ti-user-plus me-1 ti-sm"></i>
                                                <span class="align-middle">دعوت دوستان</span>
                                            </li>
                                            <li>
                                                <i class="ti ti-trash me-1 ti-sm"></i>
                                                <span class="align-middle">حذف حساب</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="d-flex mt-4">
                                        <button class="btn btn-primary" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-left"> خروج</button>
                                    </div>
                                </div>
                            </div>
                            <!-- /Sidebar Left-->
                            <!-- Chat & Contacts -->
                            <!-- /Chat contacts -->
                            <!-- Chat History -->
                            <div class="col app-chat-history bg-body">
                                <div class="chat-history-wrapper">
                                    <div class="chat-history-header border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex overflow-hidden align-items-center">
                                                <i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                                                <div class="flex-shrink-0 avatar">
                                                    <img alt="آواتار" class="rounded-circle" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right" src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/avatars/2.png"/>
                                                </div>
                                                <div class="chat-contact-info flex-grow-1 ms-2">
                                                    <h6 class="m-0">دریافت سیگنال</h6>
                                                    <small class="user-status text-muted">Recive Signal</small>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="chat-history-body bg-body">
                                        <ul class="list-unstyled chat-history">
                                            <li class="chat-message chat-message-right">
                                                <div class="d-flex overflow-hidden">
                                                    <div class="chat-message-wrapper flex-grow-1">
                                                        <div class="chat-message-text">
                                                            <p class="mb-0">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.</p>
                                                        </div>
                                                        <div class="text-end text-muted mt-1">
                                                            <i class="ti ti-checks ti-xs me-1 text-success"></i>
                                                            <small>10:00 ق.ظ</small>
                                                        </div>
                                                    </div>
                                                    <div class="user-avatar flex-shrink-0 ms-3">
                                                        <div class="avatar avatar-sm">
                                                            <img alt="آواتار" class="rounded-circle" src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/avatars/1.png"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="chat-message chat-message-right">
                                                <div class="d-flex overflow-hidden">
                                                    <div class="chat-message-wrapper flex-grow-1">
                                                        <div class="chat-message-text">
                                                            <p class="mb-0">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.</p>
                                                        </div>
                                                        <div class="text-end text-muted mt-1">
                                                            <i class="ti ti-checks ti-xs me-1 text-success"></i>
                                                            <small>10:03 ق.ظ</small>
                                                        </div>
                                                    </div>
                                                    <div class="user-avatar flex-shrink-0 ms-3">
                                                        <div class="avatar avatar-sm">
                                                            <img alt="آواتار" class="rounded-circle" src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/avatars/1.png"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="chat-message chat-message-right">
                                                <div class="d-flex overflow-hidden">
                                                    <div class="chat-message-wrapper flex-grow-1">
                                                        <div class="chat-message-text">
                                                            <p class="mb-0">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.</p>
                                                        </div>
                                                        <div class="text-end text-muted mt-1">
                                                            <i class="ti ti-checks ti-xs me-1 text-success"></i>
                                                            <small>10:06 ق.ظ</small>
                                                        </div>
                                                    </div>
                                                    <div class="user-avatar flex-shrink-0 ms-3">
                                                        <div class="avatar avatar-sm">
                                                            <img alt="آواتار" class="rounded-circle" src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/avatars/1.png"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="chat-message chat-message-right">
                                                <div class="d-flex overflow-hidden">
                                                    <div class="chat-message-wrapper flex-grow-1">
                                                        <div class="chat-message-text">
                                                            <p class="mb-0">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.</p>
                                                        </div>
                                                        <div class="text-end text-muted mt-1">
                                                            <i class="ti ti-checks ti-xs me-1 text-success"></i>
                                                            <small>10:10 ق.ظ</small>
                                                        </div>
                                                    </div>
                                                    <div class="user-avatar flex-shrink-0 ms-3">
                                                        <div class="avatar avatar-sm">
                                                            <img alt="آواتار" class="rounded-circle" src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/avatars/1.png"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="chat-message chat-message-right">
                                                <div class="d-flex overflow-hidden">
                                                    <div class="chat-message-wrapper flex-grow-1 w-50">
                                                        <div class="chat-message-text">
                                                            <p class="mb-0">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.</p>
                                                        </div>
                                                        <div class="text-end text-muted mt-1">
                                                            <i class="ti ti-checks ti-xs me-1"></i>
                                                            <small>10:15 ق.ظ</small>
                                                        </div>
                                                    </div>
                                                    <div class="user-avatar flex-shrink-0 ms-3">
                                                        <div class="avatar avatar-sm">
                                                            <img alt="آواتار" class="rounded-circle" src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/avatars/1.png"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- Chat message form -->
                                    <div class="chat-history-footer shadow-sm">
                                        <form class="form-send-message d-flex justify-content-between align-items-center">
                                            <input class="form-control message-input border-0 me-3 shadow-none" placeholder="ارز مورد نظر خود را اینجا تایپ کنید"/>
                                            <div class="message-actions d-flex align-items-center">
                                                <i class="speech-to-text ti ti-microphone ti-sm cursor-pointer"></i>
                                                <label class="form-label mb-0" for="attach-doc">
                                                    <i class="ti ti-photo ti-sm cursor-pointer mx-3"></i>
                                                    <input hidden id="attach-doc" type="file"/>
                                                </label>
                                                <button class="btn btn-primary d-flex send-msg-btn">
                                                    <span class="align-middle d-md-inline-block d-none">ارسال</span>
                                                    <i class="ti ti-send ms-md-1 ms-0 ic-mirror"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /Chat History -->
                            <!-- Sidebar Right -->
                            <div class="col app-chat-sidebar-right app-sidebar overflow-hidden" id="app-chat-sidebar-right">
                                <div class="sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
                                    <div class="avatar avatar-xl avatar-online">
                                        <img alt="آواتار" class="rounded-circle" src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/avatars/2.png"/>
                                    </div>
                                    <h6 class="mt-2 mb-0">دریافت سیگنال</h6>
                                    <span>Recive Signal</span>
                                    <i class="ti ti-x ti-sm cursor-pointer close-sidebar d-block" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right"></i>
                                </div>
                                <div class="sidebar-body px-4 pb-4">
                                    <div class="my-4">
                                        <small class="text-muted text-uppercase">مورد</small>
                                        <p class="mb-0 mt-3"> بعدی. توسعه دهنده js یک توسعه دهنده نرم افزار است که از Next استفاده می کند. چارچوب js در کنار ReactJS برای ساخت برنامه های کاربردی وب.</p>
                                    </div>
                                    <div class="my-4">
                                        <small class="text-muted text-uppercase">اطلاعات شخصی</small>
                                        <ul class="list-unstyled d-grid gap-2 mt-3">
                                            <li class="d-flex align-items-center">
                                                <i class="ti ti-mail ti-sm"></i>
                                                <span class="align-middle ms-2">josephGreen@email.com</span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <i class="ti ti-phone-call ti-sm"></i>
                                                <span class="align-middle ms-2">
                                                    <bdi>+1(123) 456 - 7890</bdi>
                                                </span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <i class="ti ti-clock ti-sm"></i>
                                                <span class="align-middle ms-2">دوشنبه - جمعه 10AM - 8PM</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="mt-4">
                                        <small class="text-muted text-uppercase">گزینه</small>
                                        <ul class="list-unstyled d-grid gap-2 mt-3">
                                            <li class="cursor-pointer d-flex align-items-center">
                                                <i class="ti ti-badge ti-sm"></i>
                                                <span class="align-middle ms-2">افزودن برچسب</span>
                                            </li>
                                            <li class="cursor-pointer d-flex align-items-center">
                                                <i class="ti ti-star ti-sm"></i>
                                                <span class="align-middle ms-2">تماس مهم</span>
                                            </li>
                                            <li class="cursor-pointer d-flex align-items-center">
                                                <i class="ti ti-photo ti-sm"></i>
                                                <span class="align-middle ms-2">رسانه های اشتراکی</span>
                                            </li>
                                            <li class="cursor-pointer d-flex align-items-center">
                                                <i class="ti ti-trash ti-sm"></i>
                                                <span class="align-middle ms-2">حذف اشنا</span>
                                            </li>
                                            <li class="cursor-pointer d-flex align-items-center">
                                                <i class="ti ti-ban ti-sm"></i>
                                                <span class="align-middle ms-2">مسدود کردن مخاطب</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /Sidebar Right -->
                            <div class="app-overlay"></div>
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
<script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
<script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/hammer/hammer.js"></script>
<script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/i18n/i18n.js"></script>
<script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
<script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/menu.js"></script>
<!-- endbuild -->
<!-- Vendors JS -->
<script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js"></script>
<!-- Main JS -->
<script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/js/main.js"></script>
<!-- Page JS -->
<script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/js/app-chat.js"></script>
<script>
    $('.signals').addClass('active')
</script>
</body>

</html>
