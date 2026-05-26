<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ env('DASHBOARD_THEME_PATH') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>اعلانات - روحی بات</title>
    <meta content="" name="description" />
    <!-- Favicon -->
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/img/favicon/favicon.ico" rel="icon"
        type="image/x-icon" />
    <!-- Icons -->
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/demo.css" rel="stylesheet" />
    <!-- Vendors CSS -->

    <!-- Helpers -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/rtl.css" rel="stylesheet" />
    <style>
        .delete_notif {
            cursor: pointer;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            user-select: none;
            transition: transform 0.2s ease;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .delete_notif:active {
            transform: scale(0.95);
        }

        .delete_notif * {
            pointer-events: none;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Navbar -->
            @include('dashboard.sections.navbar')
            <!-- / Navbar -->
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
                        <h4 class="py-3 mb-4">
                            <span class="text-muted fw-light">حساب کاربری /</span>
                            اعلانات
                        </h4>
                        @if (session('success'))
                            <p class="alert alert-success mb-5">{{ session('success') }}</p>
                        @endif
                        <div class="row g-4">

                            @foreach ($notifications as $notification)
                                <div class="list-group notif_item bg-white my-1">
                                    <a class="list-group-item list-group-item-action d-flex align-items-center p-3 {{ $notification->status == 1 ? 'read' : 'unread' }}"
                                        data-notif-id="{{ $notification->id }}"
                                        data-notif-modal="notif{{ $notification->id }}" type="button">
                                        <div class="col-12 d-flex justify-content-between">
                                            <div
                                                class="badge {{ $notification->status == 1 ? 'bg-success' : 'bg-danger' }} rounded p-1 me-3">
                                                @if ($notification->status == 1)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30"
                                                        height="30" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-bell-ringing-2 m-1">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M19.364 4.636a2 2 0 0 1 0 2.828a7 7 0 0 1 -1.414 7.072l-2.122 2.12a4 4 0 0 0 -.707 3.536l-11.313 -11.312a4 4 0 0 0 3.535 -.707l2.121 -2.123a7 7 0 0 1 7.072 -1.414a2 2 0 0 1 2.828 0" />
                                                        <path
                                                            d="M7.343 12.414l-.707 .707a3 3 0 0 0 4.243 4.243l.707 -.707" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30"
                                                        height="30" viewBox="0 0 24 24" fill="currentColor"
                                                        class="icon icon-tabler icons-tabler-filled icon-tabler-bell-ringing m-1">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M17.451 2.344a1 1 0 0 1 1.41 -.099a12.05 12.05 0 0 1 3.048 4.064a1 1 0 1 1 -1.818 .836a10.05 10.05 0 0 0 -2.54 -3.39a1 1 0 0 1 -.1 -1.41z" />
                                                        <path
                                                            d="M5.136 2.245a1 1 0 0 1 1.312 1.51a10.05 10.05 0 0 0 -2.54 3.39a1 1 0 1 1 -1.817 -.835a12.05 12.05 0 0 1 3.045 -4.065z" />
                                                        <path
                                                            d="M14.235 19c.865 0 1.322 1.024 .745 1.668a3.992 3.992 0 0 1 -2.98 1.332a3.992 3.992 0 0 1 -2.98 -1.332c-.552 -.616 -.158 -1.579 .634 -1.661l.11 -.006h4.471z" />
                                                        <path
                                                            d="M12 2c1.358 0 2.506 .903 2.875 2.141l.046 .171l.008 .043a8.013 8.013 0 0 1 4.024 6.069l.028 .287l.019 .289v2.931l.021 .136a3 3 0 0 0 1.143 1.847l.167 .117l.162 .099c.86 .487 .56 1.766 -.377 1.864l-.116 .006h-16c-1.028 0 -1.387 -1.364 -.493 -1.87a3 3 0 0 0 1.472 -2.063l.021 -.143l.001 -2.97a8 8 0 0 1 3.821 -6.454l.248 -.146l.01 -.043a3.003 3.003 0 0 1 2.562 -2.29l.182 -.017l.176 -.004z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="col d-flex flex-column">
                                                <h6 class="mb-2">{{ $notification->title }}</h6>
                                                <small
                                                    class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="col text-end d-flex align-items-center justify-content-end">
                                                <small class="text-danger delete_notif"
                                                    data-notif-id="{{ $notification->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 7l16 0" />
                                                        <path d="M10 11l0 6" />
                                                        <path d="M14 11l0 6" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                    </svg>
                                                </small>
                                            </div>

                                        </div>
                                    </a>
                                </div>
                                <!-- Modal -->
                                <div aria-hidden="true" class="modal fade" id="notif{{ $notification->id }}"
                                    tabindex="-1">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">
                                                    {{ $notification->title }}
                                                </h5>
                                                <button aria-label="بستن" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <p>{!! nl2br(
                                                        preg_replace(
                                                            '/(https?:\/\/[^\s<]+)/u',
                                                            '<a href="$1" target="_blank" rel="noopener noreferrer">اینجا کلیک کنید</a>',
                                                            e($notification->content),
                                                        ),
                                                    ) !!}</p>
                                                    <small
                                                        style="display: inline-block;direction: ltr;">{{ verta($notification->created_at)->format('Y/m/d H:i:s') }}</small>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach


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
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <!-- Main JS -->
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/main.js"></script>
    <!-- Page JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const CSRF_TOKEN = @json(csrf_token());
            const READ_URL = @json(route('notifRead'));
            const DELETE_URL = @json(route('notifDelete'));

            const readIconSVG = `
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bell-ringing-2 m-1">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M19.364 4.636a2 2 0 0 1 0 2.828a7 7 0 0 1 -1.414 7.072l-2.122 2.12a4 4 0 0 0 -.707 3.536l-11.313 -11.312a4 4 0 0 0 3.535 -.707l2.121 -2.123a7 7 0 0 1 7.072 -1.414a2 2 0 0 1 2.828 0" />
                    <path d="M7.343 12.414l-.707 .707a3 3 0 0 0 4.243 4.243l.707 -.707" />
                </svg>
            `;

            const badge = document.querySelector('.badge-notifications');

            document.querySelectorAll('.list-group-item[data-notif-id]').forEach(el => {

                el.addEventListener('click', async (e) => {

                    // اگر بر روی delete_notif کلیک شد، مودال باز نشود
                    if (e.target.closest('.delete_notif')) return;

                    const notifId = el.dataset.notifId;
                    const modalId = el.dataset.notifModal;
                    const isUnread = el.classList.contains('unread');

                    if (!notifId) return;

                    // AJAX فقط برای unread
                    if (isUnread) {
                        try {
                            const res = await fetch(READ_URL, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': CSRF_TOKEN
                                },
                                body: JSON.stringify({
                                    notif_id: notifId
                                })
                            });

                            if (!res.ok) return;

                            const data = await res.json();

                            if (data.success) {

                                el.classList.add('read');
                                el.classList.remove('unread');

                                // update badge color wrapper
                                const badgeWrapper = el.querySelector('.badge');
                                if (badgeWrapper) {
                                    badgeWrapper.classList.remove('bg-danger');
                                    badgeWrapper.classList.add('bg-success');
                                    badgeWrapper.innerHTML = readIconSVG;
                                }

                                // decrement top navbar badge if present
                                if (badge) {
                                    let count = parseInt(badge.textContent.trim(), 10);
                                    if (!isNaN(count) && count > 0) {
                                        count--;
                                        if (count <= 0) {
                                            badge.remove();
                                        } else {
                                            badge.textContent = count;
                                        }
                                    }
                                }
                            }

                        } catch (err) {
                            console.error('notif read error:', err);
                        }
                    }

                    // باز کردن مودال برای همه (read و unread)
                    if (modalId) {
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            const bsModal = new bootstrap.Modal(modal);
                            bsModal.show();
                        }
                    }
                });

            });

            // ❌ حذف نوتیفیکیشن
            const handleNotifDelete = async (e) => {
                const btn = e.target.closest('.delete_notif');
                if (!btn) return;

                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                const notifId = btn.dataset.notifId;
                if (!notifId) return;

                try {
                    const res = await fetch(DELETE_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            notif_id: notifId
                        })
                    });

                    if (!res.ok) return;

                    const data = await res.json();

                    if (data.success) {
                        // یافتن والد notif_item
                        const notifItem = btn.closest('.notif_item');
                        if (notifItem) {
                            // فید اوت و حذف
                            notifItem.style.transition = 'opacity 0.3s ease';
                            notifItem.style.opacity = '0';
                            setTimeout(() => {
                                notifItem.remove();
                            }, 300);
                        }
                    }

                } catch (err) {
                    console.error('notif delete error:', err);
                }
            };

            // Click + Touch events
            document.addEventListener('click', handleNotifDelete, true);
            document.addEventListener('touchend', handleNotifDelete, true);

        });
    </script>
</body>

</html>
