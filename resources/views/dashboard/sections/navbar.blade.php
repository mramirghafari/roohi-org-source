<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-xxl">
        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
            <a class="app-brand-link gap-2" href="{{ asset('/') }}">
                <img src="{{ asset('/assets/images/logo.svg') }}" width="150" class="img-fluid" />
            </a>
            <a class="layout-menu-toggle menu-link text-large ms-auto d-xl-none" href="javascript:void(0);">
                <i class="ti ti-x ti-sm align-middle"></i>
            </a>
        </div>
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-sm"></i>
            </a>
        </div>
        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">

                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-4 me-xl-1">
                    <a aria-expanded="false" class="nav-link dropdown-toggle hide-arrow" data-bs-auto-close="outside"
                        data-bs-toggle="dropdown" href="javascript:void(0);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                            style="margin-top: 6px" fill="currentColor"
                            class="icon icon-tabler icons-tabler-filled icon-tabler-bell-ringing">
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
                        @if (auth()->user()->unreadNotifs->count())
                            <span class="badge bg-danger rounded-pill badge-notifications">
                                {{ auth()->user()->unreadNotifs->count() }}
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                        <li class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto">اعلانات</h5>
                                <a class="dropdown-notifications-all text-body readall" data-bs-placement="top"
                                    data-bs-toggle="tooltip" href="{{ route('readAllNotifs') }}" title="خواندن همه">
                                    <i class="ti ti-mail-opened fs-4"></i>
                                </a>
                            </div>
                        </li>
                        <li class="dropdown-notifications-list scrollable-container">
                            <ul class="list-group list-group-flush">
                                @foreach (auth()->user()->notifs()->latest()->take(3)->get() as $notif)
                                    <li class="list-group-item list-group-item-action dropdown-notifications-item notif_item {{ $notif->status == 1 ? 'read' : 'unread' }}"
                                        data-bs-target="#notif{{ $notif->id }}" data-bs-toggle="modal"
                                        data-notif-id="{{ $notif->id }}" type="button">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar notif-icon">
                                                    @if ($notif->status == 0)
                                                        <span class="avatar-initial rounded-circle bg-label-danger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="currentColor"
                                                                class="icon icon-tabler icons-tabler-filled icon-tabler-mail">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M22 7.535v9.465a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-9.465l9.445 6.297l.116 .066a1 1 0 0 0 .878 0l.116 -.066l9.445 -6.297z" />
                                                                <path
                                                                    d="M19 4c1.08 0 2.027 .57 2.555 1.427l-9.555 6.37l-9.555 -6.37a2.999 2.999 0 0 1 2.354 -1.42l.201 -.007h14z" />
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <span class="avatar-initial rounded-circle bg-label-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-mail-opened">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M3 9l9 6l9 -6l-9 -6l-9 6" />
                                                                <path
                                                                    d="M21 9v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" />
                                                                <path d="M3 19l6 -6" />
                                                                <path d="M15 13l6 6" />
                                                            </svg>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-2">{{ $notif->title }}</h6>
                                                <p class="mb-1">{{ $notif->message }}</p>
                                                <small
                                                    class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="flex-shrink-0 dropdown-notifications-actions">
                                                @if ($notif->status == 0)
                                                    <a class="dropdown-notifications-read" href="javascript:void(0)">
                                                        <span class="badge badge-dot"></span>
                                                    </a>
                                                @endif
                                                <a class="dropdown-notifications-archive" href="javascript:void(0)">
                                                    <span class="ti ti-x"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="dropdown-menu-footer border-top">
                            <a class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center"
                                href="{{ route('notifications') }}">
                                نمایش همه اعلانات
                            </a>
                        </li>
                    </ul>
                </li>

                <!--/ Notification -->
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" data-bs-toggle="dropdown" href="javascript:void(0);">
                        <div class="avatar avatar-online">
                            <img alt class="h-auto rounded-circle" src="{{ asset('/assets/images/avatar.jpg') }}" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard.profile') }}">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img alt class="h-auto rounded-circle"
                                                src="{{ asset('/assets/images/avatar.jpg') }}" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block mb-1">{{ auth()->user()->nam }}</span>
                                        <small
                                            class="text-muted">{{ auth()->user()->isAdmin ? 'مدیرکل' : 'کاربر عادی' }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard.profile') }}">
                                <i class="ti ti-user-check me-2 ti-sm"></i>
                                <span class="align-middle">پروفایل من</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('subscription') }}">
                                <i class="ti ti-crown me-2 ti-sm"></i>
                                <span class="align-middle">اشتراک</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('wallet') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-wallet">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" />
                                    <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />
                                </svg>
                                <span class="align-middle">کیف پول </span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('referrals') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-users-group">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                    <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                                    <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                                </svg>
                                <span class="align-middle">دعوت های من</span>
                            </a>
                        </li>

                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('subscription') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-ball-football">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M12 7l4.76 3.45l-1.76 5.55h-6l-1.76 -5.55l4.76 -3.45" />
                                    <path
                                        d="M12 7v-4m3 13l2.5 3m-.74 -8.55l3.74 -1.45m-11.44 7.05l-2.56 2.95m.74 -8.55l-3.74 -1.45" />
                                </svg>
                                <span class="align-middle">تیکت پشتیباتی</span>
                            </a>
                        </li>

                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="ti ti-logout me-2 ti-sm"></i>
                                    خروج از حساب</button>
                            </form>

                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>

    </div>
</nav>

@foreach (auth()->user()->notifs()->latest()->take(10)->get() as $notif)
    <!-- Modal -->
    <div aria-hidden="true" class="modal fade" id="notif{{ $notif->id }}" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">{{ $notif->title }}
                    </h5>
                    <button aria-label="بستن" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p>{!! nl2br(
                            preg_replace(
                                '/(https?:\/\/[^\s<]+)/u',
                                '<a href="$1" target="_blank" rel="noopener noreferrer">اینجا کلیک کنید</a>',
                                e($notif->content),
                            ),
                        ) !!}</p>
                        <small
                            style="display: inline-block;direction: ltr;">{{ verta($notif->created_at)->format('Y/m/d H:i:s') }}</small>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endforeach
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const CSRF_TOKEN = @json(csrf_token());
        const READ_URL = @json(route('notifRead'));

        const openedIconHTML = `
            <span class="avatar-initial rounded-circle bg-label-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-mail-opened">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M3 9l9 6l9 -6l-9 -6l-9 6"/>
                    <path d="M21 9v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10"/>
                    <path d="M3 19l6 -6"/>
                    <path d="M15 13l6 6"/>
                </svg>
            </span>
        `;

        // 🔔 badge شمارنده
        const badge = document.querySelector('.badge-notifications');

        document.querySelectorAll('.notif_item').forEach(el => {

            el.addEventListener('click', async () => {

                // فقط unread
                if (!el.classList.contains('unread')) return;

                const notifId = el.dataset.notifId;
                if (!notifId) return;

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

                        // 1️⃣ تغییر کلاس آیتم
                        el.classList.add('read');
                        el.classList.remove('unread');

                        // 2️⃣ تغییر آیکون
                        const iconWrapper = el.querySelector('.notif-icon');
                        if (iconWrapper) {
                            iconWrapper.innerHTML = openedIconHTML;
                        }

                        // 3️⃣ کم کردن badge
                        if (badge) {
                            let count = parseInt(badge.textContent.trim(), 10);

                            if (!isNaN(count) && count > 0) {
                                count--;
                                if (count <= 0) {
                                    badge.remove(); // یا badge.classList.add('d-none')
                                } else {
                                    badge.textContent = count;
                                }
                            }
                        }
                    }

                } catch (err) {
                    console.error('notif read error:', err);
                }
            });

        });


    });
</script>
