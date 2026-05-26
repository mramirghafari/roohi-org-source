<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>کانال سیگنال - روحی ترید</title>
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
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css"
        rel="stylesheet" />

    <!-- Page CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/pages/app-chat.css" rel="stylesheet" />
    <!-- Helpers -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ asset('/dashboard_theme') }}/assets/css/rtl.css" rel="stylesheet" />
    <style>
        .signal-section {
            display: flex;
            flex-direction: row;
            background: #1e1e2f;
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            max-width: 25rem;
            margin: 30px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            font-family: sans-serif;
        }

        .signal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: column;
        }

        .signal-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: bold;
        }

        .signal-status.buy {
            background: #2ecc71;
        }

        .signal-status.sell {
            background: #e74c3c;
        }

        .signal-info p {
            margin: 6px 0;
        }

        canvas {
            margin-top: 20px;
        }
    </style>

    <style>
        /* Highlight flash animation for target chat item - toggle opacity effect */
        .highlight-flash {
            animation: highlight-flash 0.6s ease-in-out 0s 4;
            border-radius: 8px;
        }

        @keyframes highlight-flash {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
            }

            50% {
                opacity: 0.15;
                transform: scale(0.98);
                box-shadow: 0 0 20px 5px rgba(102, 126, 234, 0.4);
            }
        }

        /* Skeleton loading animation for lazy images */
        .lazy-image {
            display: block;
            width: 100%;
            height: 675px;
            background: linear-gradient(90deg, #2a2a3e 0%, #3a3a4e 50%, #2a2a3e 100%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Once image is loaded, remove the animation */
        .lazy-image[src*="/signals/"] {
            animation: none;
            background: none;
        }

        /* Scroll to bottom button */
        .scroll-to-bottom-btn {
            position: fixed;
            bottom: 65px;
            left: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .scroll-to-bottom-btn.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .scroll-to-bottom-btn:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            transform: scale(1.1);
        }

        .scroll-to-bottom-btn:active {
            transform: scale(0.95);
        }

        .scroll-to-bottom-btn svg {
            width: 24px;
            height: 24px;
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
                                <div class="col app-chat-sidebar-left app-sidebar overflow-hidden"
                                    id="app-chat-sidebar-left">

                                    <div class="sidebar-body px-4 pb-4">



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

                                                    <div>
                                                        <div class="chat-contact-info flex-grow-1 ms-2">
                                                            <small class="user-status text-muted">سیگنال های دریافتی
                                                                شما:</small>
                                                            <small class="text-success"
                                                                style="font-size: 1rem;">{{ count($signals) }}+</small>
                                                        </div>
                                                        <div class="chat-contact-info flex-grow-1 ms-2">
                                                            <small class="user-status text-muted">آخرین سیگنال دریافتی
                                                                شما:</small>
                                                            <small class="text-info" style="font-size: 1rem;">
                                                                {{ verta($lastSignalAt)->format('m/d H:i:s') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="chat-history-body bg-body">
                                            <ul class="list-unstyled chat-history">
                                                @foreach ($signals as $signal)
                                                    <li class="chat-message d-block"
                                                        id="chatid{{ $signal->tracking_code }}">
                                                        <div class="d-flex overflow-hidden">
                                                            <div class="chat-message-wrapper flex-grow-1">
                                                                <div class="signal-section my-1">
                                                                    <div>
                                                                        <div class="signal-header">

                                                                            <p class="m-1 d-inline">
                                                                                <span class="text-info">شماره سیگنال:
                                                                                </span>
                                                                                {{ $signal->tracking_code }}
                                                                            </p>
                                                                            <h4 class="text-danger">📈 سیگنال ترید
                                                                                ({{ $signal->symbol }})
                                                                            </h4>
                                                                        </div>
                                                                        <div class="signal-info">
                                                                            @if ($signal->photo != null)
                                                                                <img class="col-12 lazy-image"
                                                                                    data-src="{{ asset('/signals') }}/{{ $signal->photo }}"
                                                                                    src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                                                                    alt="signal-{{ $signal->tracking_code }}">
                                                                            @endif
                                                                            <div class="flex-lg-row col-12">

                                                                                <p>نوع سیگنال:
                                                                                    @if ($signal->type == 1)
                                                                                        <strong class="text-danger">فروش
                                                                                            (Short)</strong>
                                                                                    @else
                                                                                        <strong
                                                                                            class="text-success">خرید
                                                                                            (Long)</strong>
                                                                                    @endif
                                                                                </p>
                                                                                <div
                                                                                    style="display: flex;flex-direction: column;align-items: end;">
                                                                                    @if ($signal->entryPrice1 != null)
                                                                                        <p>Entry price 1 :
                                                                                            <strong>
                                                                                                {{ $signal->entryPrice1 }}</strong>
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                width="20"
                                                                                                height="20"
                                                                                                viewBox="0 0 24 24"
                                                                                                fill="currentColor"
                                                                                                class="icon icon-tabler icons-tabler-filled icon-tabler-chart-bubble">
                                                                                                <path stroke="none"
                                                                                                    d="M0 0h24v24H0z"
                                                                                                    fill="none" />
                                                                                                <path
                                                                                                    d="M6 12a4 4 0 1 1 -3.995 4.2l-.005 -.2l.005 -.2a4 4 0 0 1 3.995 -3.8z" />
                                                                                                <path
                                                                                                    d="M16 16a3 3 0 1 1 -2.995 3.176l-.005 -.176l.005 -.176a3 3 0 0 1 2.995 -2.824z" />
                                                                                                <path
                                                                                                    d="M14.5 2a5.5 5.5 0 1 1 -5.496 5.721l-.004 -.221l.004 -.221a5.5 5.5 0 0 1 5.496 -5.279z" />
                                                                                            </svg>
                                                                                        </p>
                                                                                    @endif
                                                                                    @if ($signal->entryPrice2 != null)
                                                                                        <p>Entry price 2 :
                                                                                            <strong>
                                                                                                {{ $signal->entryPrice2 }}</strong>
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                width="20"
                                                                                                height="20"
                                                                                                viewBox="0 0 24 24"
                                                                                                fill="currentColor"
                                                                                                class="icon icon-tabler icons-tabler-filled icon-tabler-chart-bubble">
                                                                                                <path stroke="none"
                                                                                                    d="M0 0h24v24H0z"
                                                                                                    fill="none" />
                                                                                                <path
                                                                                                    d="M6 12a4 4 0 1 1 -3.995 4.2l-.005 -.2l.005 -.2a4 4 0 0 1 3.995 -3.8z" />
                                                                                                <path
                                                                                                    d="M16 16a3 3 0 1 1 -2.995 3.176l-.005 -.176l.005 -.176a3 3 0 0 1 2.995 -2.824z" />
                                                                                                <path
                                                                                                    d="M14.5 2a5.5 5.5 0 1 1 -5.496 5.721l-.004 -.221l.004 -.221a5.5 5.5 0 0 1 5.496 -5.279z" />
                                                                                            </svg>
                                                                                        </p>
                                                                                    @endif
                                                                                    @if ($signal->target1 != null)
                                                                                        <p>Tp1 :
                                                                                            <strong>
                                                                                                {{ $signal->target1 }}</strong>
                                                                                            <i
                                                                                                class="tf-icons ti ti-chart-arrows-vertical text-info"></i>
                                                                                        </p>
                                                                                    @endif

                                                                                    @if ($signal->target2 != null)
                                                                                        <p>Tp2 :
                                                                                            <strong>
                                                                                                {{ $signal->target2 }}</strong>
                                                                                            <i
                                                                                                class="tf-icons ti ti-chart-arrows-vertical text-info"></i>
                                                                                        </p>
                                                                                    @endif

                                                                                    @if ($signal->target3 != null)
                                                                                        <p>Tp3 :
                                                                                            <strong>
                                                                                                {{ $signal->target3 }}</strong>
                                                                                            <i
                                                                                                class="tf-icons ti ti-chart-arrows-vertical text-info"></i>
                                                                                        </p>
                                                                                    @endif

                                                                                    @if ($signal->target4 != null)
                                                                                        <p>Tp4 :
                                                                                            <strong>
                                                                                                {{ $signal->target4 }}</strong>
                                                                                            <i
                                                                                                class="tf-icons ti ti-chart-arrows-vertical text-info"></i>
                                                                                        </p>
                                                                                    @endif

                                                                                    @if ($signal->target5 != null)
                                                                                        <p>Tp5 :
                                                                                            <strong>
                                                                                                {{ $signal->target5 }}</strong>
                                                                                            <i
                                                                                                class="tf-icons ti ti-chart-arrows-vertical text-info"></i>
                                                                                        </p>
                                                                                    @endif

                                                                                    @if ($signal->sl != null)
                                                                                        <p>Sl:
                                                                                            <strong>{{ $signal->sl }}</strong>
                                                                                            <i
                                                                                                class="tf-icons ti ti-chart-arrows text-danger"></i>
                                                                                        </p>
                                                                                    @endif

                                                                                    @if ($signal->laverege != null)
                                                                                        <p>Leverage :
                                                                                            <strong>{{ $signal->laverege }}</strong>
                                                                                            <i
                                                                                                class="tf-icons ti ti-chart-candle text-success"></i>
                                                                                        </p>
                                                                                    @endif
                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        @if ($signal->content != null)
                                                                            <h4 class="text-info m-1">تحلیل:</h4>
                                                                            <p class="m-1"> {{ $signal->content }}
                                                                            </p>
                                                                        @endif

                                                                        @if ($signal->tp_level > 0 && $signal->tp_level < 5 && $signal->final_result == 0)
                                                                            <p
                                                                                class="alert bg-success col=12 text-center">
                                                                                وضعیت: TP{{ $signal->tp_level }} Touch
                                                                                (Profit: {{ $signal->profit . '%' }})
                                                                            </p>
                                                                        @endif

                                                                        @if ($signal->final_result == 1)
                                                                            <p
                                                                                class="alert bg-success col=12 text-center">
                                                                                وضعیت: FULL TP (Profit:
                                                                                {{ $signal->profit . '%' }})
                                                                            </p>
                                                                        @endif

                                                                        @if ($signal->final_result == 2)
                                                                            <p
                                                                                class="alert bg-danger col=12 text-center">
                                                                                وضعیت: Stop Loss (Loss:
                                                                                {{ '-' . $signal->profit . '%' }})
                                                                            </p>
                                                                        @endif

                                                                        @if ($signal->final_result == 3)
                                                                            <p
                                                                                class="alert bg-dark col=12 text-center text-danger">
                                                                                وضعیت: Canceled!!
                                                                            </p>
                                                                        @endif

                                                                        @if ($signal->tp_level == 0 && $signal->status == 0)
                                                                            <p
                                                                                class="alert bg-label-warning col=12 text-center">
                                                                                در حال انجام...
                                                                            </p>
                                                                        @endif



                                                                    </div>



                                                                </div>
                                                                <div class="text-end text-muted mt-1">
                                                                    <i
                                                                        class="ti ti-checks ti-xs me-1 text-success"></i>
                                                                    <small>{{ verta($signal->created_at)->format('m/d H:i:s') }}</small>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <!-- /Chat History -->

                                <!-- Scroll to Bottom Button -->
                                <button class="scroll-to-bottom-btn" id="scrollToBottomBtn"
                                    title="برو به آخرین سیگنال">
                                    <i class="ti ti-arrow-down"></i>
                                </button>

                                <!-- /Sidebar Right -->
                                <div class="app-overlay"></div>
                            </div>
                        </div>
                    </div>
                    <!--/ Content -->
                    @include('dashboard.sections.footer')
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
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Main JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <!-- Page JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/app-chat.js"></script>

    <script>
        $('.signals').addClass('active');
    </script>

    <script>
        (function() {
            // find nearest scrollable ancestor; fallback to selector
            function getScrollableAncestor(el, fallbackSelector) {
                if (!el) return null;
                let node = el.parentElement;
                while (node) {
                    const style = window.getComputedStyle(node);
                    const overflowY = style.overflowY;
                    if ((overflowY === 'auto' || overflowY === 'scroll') && node.scrollHeight > node.clientHeight) {
                        return node;
                    }
                    node = node.parentElement;
                }
                if (fallbackSelector) return document.querySelector(fallbackSelector);
                return null;
            }

            // robust scroll-to-element that targets nearest scrollable ancestor
            function scrollToElement(el, alignCenter = true, smooth = true) {
                if (!el) return;
                const container = getScrollableAncestor(el, '.chat-history-body') || document.scrollingElement ||
                    document.documentElement;

                // compute element offset relative to container using offsetTop chain
                function offsetTopRelativeToContainer(element, containerEl) {
                    let top = 0;
                    let node = element;
                    while (node && node !== containerEl && node.offsetParent) {
                        top += node.offsetTop;
                        node = node.offsetParent;
                    }
                    // if we didn't reach containerEl, fallback to getBoundingClientRect method
                    if (node !== containerEl) {
                        const elRect = element.getBoundingClientRect();
                        const contRect = containerEl.getBoundingClientRect ? containerEl.getBoundingClientRect() : {
                            top: 0
                        };
                        return elRect.top - contRect.top + (containerEl.scrollTop || 0);
                    }
                    return top;
                }

                const offset = offsetTopRelativeToContainer(el, container);
                let target = offset;
                if (alignCenter) target = Math.max(0, offset - (container.clientHeight / 2) + (el.clientHeight / 2));

                // animate scroll ourselves for consistent smoothness
                if (!smooth || typeof window.requestAnimationFrame !== 'function') {
                    try {
                        container.scrollTop = target;
                    } catch (e) {
                        if ('scrollTo' in container) container.scrollTo(0, target);
                    }
                    return;
                }

                // cancel previous animation on this container
                if (container._scrollAnim) {
                    cancelAnimationFrame(container._scrollAnim);
                    container._scrollAnim = null;
                }

                const start = container.scrollTop || 0;
                const change = target - start;
                const duration = 300; // ms - reduced for faster scroll
                const startTime = performance.now();

                function easeInOutQuad(t) {
                    return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
                }

                function animate(now) {
                    const elapsed = Math.min(1, (now - startTime) / duration);
                    const progress = easeInOutQuad(elapsed);
                    const current = Math.round(start + change * progress);
                    try {
                        container.scrollTop = current;
                    } catch (e) {
                        if ('scrollTo' in container) container.scrollTo(0, current);
                    }
                    if (elapsed < 1) {
                        container._scrollAnim = requestAnimationFrame(animate);
                    } else {
                        container._scrollAnim = null;
                    }
                }
                container._scrollAnim = requestAnimationFrame(animate);
            }

            // helper to flash highlight on element briefly
            function flashElement(element, duration = 1800) {
                if (!element) return;
                element.classList.add('highlight-flash');
                setTimeout(function() {
                    element.classList.remove('highlight-flash');
                }, duration);
            }

            // Click handler for anchor links or elements with data-chatid/data-target that target chat ids
            document.addEventListener('click', function(ev) {
                let a = ev.target.closest && ev.target.closest('a[href^="#chatid"]');
                let targetAttr = null;
                if (!a) {
                    const elWithData = ev.target.closest && ev.target.closest('[data-chatid],[data-target]');
                    if (elWithData) {
                        targetAttr = elWithData.getAttribute('data-chatid') || elWithData.getAttribute(
                            'data-target');
                    }
                }
                if (!a && !targetAttr) return;
                ev.preventDefault();
                const hash = a ? a.getAttribute('href') : targetAttr;
                if (!hash) return;
                const id = hash.replace(/^#/, '');
                const el = document.getElementById(id);
                if (el) {
                    scrollToElementWithRetries(el, 6, 180);
                    history.replaceState(null, '', hash);
                }
            });

            // If page loaded with hash #chatid..., scroll to it and flash (with retries)
            window.addEventListener('load', function() {
                const hash = window.location.hash;
                if (hash && hash.startsWith('#chatid')) {
                    const id = hash.replace(/^#/, '');
                    const el = document.getElementById(id);
                    if (el) {
                        setTimeout(function() {
                            scrollToElementWithRetries(el, 6, 200);
                        }, 160);
                    }
                }
            });

            // respond to manual hash changes
            window.addEventListener('hashchange', function() {
                const hash = window.location.hash;
                if (hash && hash.startsWith('#chatid')) {
                    const id = hash.replace(/^#/, '');
                    const el = document.getElementById(id);
                    if (el) scrollToElementWithRetries(el, 6, 180);
                }
            });

            // scroll helper with retries
            function scrollToElementWithRetries(el, attempts = 4, delay = 200) {
                let i = 0;
                let flashApplied = false;
                const tryScroll = function() {
                    scrollToElement(el, true, true);
                    i++;
                    if (i < attempts) {
                        setTimeout(tryScroll, delay);
                    } else if (!flashApplied) {
                        // Flash only after all scroll attempts complete
                        flashApplied = true;
                        setTimeout(function() {
                            flashElement(el);
                        }, 800);
                    }
                };
                tryScroll();
            }

            // Lazy load images using Intersection Observer
            if (window.IntersectionObserver) {
                const lazyImages = document.querySelectorAll('img.lazy-image');
                const imageObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const dataSrc = img.getAttribute('data-src');
                            if (dataSrc) {
                                img.onload = function() {
                                    img.classList.remove('lazy-image');
                                };
                                img.onerror = function() {
                                    img.classList.remove('lazy-image');
                                };
                                img.src = dataSrc;
                                img.removeAttribute('data-src');
                                imageObserver.unobserve(img);
                            }
                        }
                    });
                }, {
                    rootMargin: '50px'
                });

                lazyImages.forEach(function(img) {
                    imageObserver.observe(img);
                });
            }

            // Scroll to bottom button functionality
            const scrollToBottomBtn = document.getElementById('scrollToBottomBtn');
            const chatHistoryBody = document.querySelector('.chat-history-body');

            if (scrollToBottomBtn && chatHistoryBody) {
                // Show/hide button based on scroll position
                chatHistoryBody.addEventListener('scroll', function() {
                    const scrollTop = chatHistoryBody.scrollTop;
                    const scrollHeight = chatHistoryBody.scrollHeight;
                    const clientHeight = chatHistoryBody.clientHeight;
                    const scrolledFromTop = scrollTop;
                    const distanceToBottom = scrollHeight - scrollTop - clientHeight;

                    // Show button if scrolled more than 300px from top and not at bottom
                    if (scrolledFromTop > 300 && distanceToBottom > 100) {
                        scrollToBottomBtn.classList.add('show');
                    } else {
                        scrollToBottomBtn.classList.remove('show');
                    }
                });

                // Click handler to scroll to bottom
                scrollToBottomBtn.addEventListener('click', function() {
                    const target = chatHistoryBody.scrollHeight;
                    const start = chatHistoryBody.scrollTop;
                    const change = target - start;
                    const duration = 400;
                    const startTime = performance.now();

                    function easeInOutQuad(t) {
                        return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
                    }

                    function animate(now) {
                        const elapsed = Math.min(1, (now - startTime) / duration);
                        const progress = easeInOutQuad(elapsed);
                        chatHistoryBody.scrollTop = start + change * progress;
                        if (elapsed < 1) {
                            requestAnimationFrame(animate);
                        }
                    }
                    requestAnimationFrame(animate);
                });
            }
        })();
    </script>

</body>

</html>
