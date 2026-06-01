<aside class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0" id="layout-menu">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <!-- Dashboards -->
            <li class="menu-item dashboard">
                <a class="menu-link" href="{{ route('dashboard') }}">
                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                    <div>پیشخوان</div>
                </a>
            </li>
            <!-- Layouts -->
            @if (auth()->user()->isAdmin == 1)
                <li class="menu-item admin">
                    <a class="menu-link menu-toggle" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="currentColor"
                            class="icon icon-tabler icons-tabler-filled icon-tabler-direction-arrows">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1 -20 0l.004 -.28c.148 -5.393 4.566 -9.72 9.996 -9.72m-.293 13.293a1 1 0 0 0 -1.414 1.414l1 1a1 1 0 0 0 1.414 0l1 -1a1 1 0 0 0 0 -1.414l-.094 -.083a1 1 0 0 0 -1.32 .083l-.293 .292zm-3 -5a1 1 0 0 0 -1.414 0l-1 1a1 1 0 0 0 0 1.414l1 1a1 1 0 0 0 1.414 0l.083 -.094a1 1 0 0 0 -.083 -1.32l-.292 -.293l.292 -.293a1 1 0 0 0 0 -1.414m8 0a1 1 0 0 0 -1.414 0l-.083 .094a1 1 0 0 0 .083 1.32l.292 .292l-.292 .294a1 1 0 0 0 1.414 1.414l1 -1a1 1 0 0 0 0 -1.414zm-4 -4a1 1 0 0 0 -1.414 0l-1 1a1 1 0 0 0 0 1.414l.094 .083a1 1 0 0 0 1.32 -.083l.293 -.292l.293 .292a1 1 0 0 0 1.414 -1.414z" />
                        </svg>
                        <div>مدیریت سیگنال ها</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('newSignal') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="menu-icon icon icon-tabler icons-tabler-outline icon-tabler-send">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 14l11 -11" />
                                    <path
                                        d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                </svg>
                                <div>ارسال سیگنال جدید</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('signalsHistory') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="menu-icon icon icon-tabler icons-tabler-outline icon-tabler-chart-histogram">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 3v18h18" />
                                    <path d="M20 18v3" />
                                    <path d="M16 16v5" />
                                    <path d="M12 13v8" />
                                    <path d="M8 16v5" />
                                    <path d="M3 11c6 0 5 -5 9 -5s3 5 9 5" />
                                </svg>
                                <div> تاریخچه سیگنال ها </div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="menu-item signals">
                <a class="menu-link" href="{{ route('channel') }}">
                    <i class="menu-icon fab fa-500px"></i>
                    <div>سیگنال ها</div>
                </a>
            </li>
            <!-- Apps -->

            <!-- Pages -->
            @if (auth()->user()->isAdmin == 0)
                <li class="menu-item subscription">
                    <a class="menu-link" href="{{ route('subscription') }}">
                        <i class="menu-icon tf-icons ti ti-crown"></i>
                        <div>اشتراک</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAdmin == 1)
                <li class="menu-item bots">
                    <a class="menu-link menu-toggle" href="javascript:void(0)">
                        <i class="menu-icon tf-icons ti ti-toggle-left"></i>
                        <div>ربات ها</div>
                    </a>
                    <ul class="menu-sub">
                        <!-- Cards -->
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('bot1.dashboard') }}">
                                <div>ربات قدیمی @AiroohiBot</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('bot2.dashboard') }}">
                                <div>ربات جدید @roohiai_bot</div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (auth()->user()->isAdmin == 1 ||
                    auth()->user()->supportGroups()->exists() ||
                    auth()->user()->hasRole('sales-manager') ||
                    auth()->user()->hasRole('sales-expert'))
                <li class="menu-item users">
                    <a class="menu-link menu-toggle" href="javascript:void(0)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                        </svg>
                        <div>کاربران</div>
                    </a>
                    <ul class="menu-sub">
                        <!-- Cards -->
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('users.index') }}">
                                <div>مدیریت کاربران</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('users.all') }}">
                                <div>کل کاربران</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('users.activeUsers') }}">
                                <div>کاربران فعال</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('users.deactiveUsers') }}">
                                <div>کاربران غیرفعال</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('users.leftUsers') }}">
                                <div>کاربران رفته</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('users.search') }}">
                                <div>جستجوی کاربر</div>
                            </a>
                        </li>
                        @if (auth()->user()->isAdmin == 1)
                            <li class="menu-item">
                                <a class="menu-link" href="{{ route('lbank.checkBalance') }}">
                                    <div>چک بالانس ال بانک</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link" href="{{ route('users.sendNotif') }}">
                                    <div>ارسال اعلان</div>
                                </a>
                            </li>
                            <li class="menu-item managers">
                                <a class="menu-link" href="{{ route('managers.index') }}">
                                    <div>مدیران</div>
                                </a>
                            </li>
                            <li class="menu-item user-roles">
                                <a class="menu-link" href="{{ route('user-roles.index') }}">
                                    <div>نقش های کاربری</div>
                                </a>
                            </li>
                            <li class="menu-item audit-logs">
                                <a class="menu-link" href="{{ route('audit-logs.index') }}">
                                    <div>لاگ فعالیت ها</div>
                                </a>
                            </li>
                            <li class="menu-item user-groups">
                                <a class="menu-link" href="{{ route('user-groups.index') }}">
                                    <div>گروه های کاربری</div>
                                </a>
                            </li>
                            <li class="menu-item subscription-plans">
                                <a class="menu-link" href="{{ route('subscription-plans.index') }}">
                                    <div>تعریف اشتراک‌ها</div>
                                </a>
                            </li>
                            <li class="menu-item subscription-payments">
                                <a class="menu-link" href="{{ route('subscription-payments.index') }}">
                                    <div>بررسی خرید اشتراک</div>
                                </a>
                            </li>
                            <li class="menu-item user-camps">
                                <a class="menu-link" href="{{ route('payment-campaigns.index') }}">
                                    <div>مدیریت کمپین</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="menu-item blog_admin">
                    <a class="menu-link menu-toggle" href="javascript:void(0)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-pencil-share">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                            <path d="M13.5 6.5l4 4" />
                            <path d="M16 22l5 -5" />
                            <path d="M21 21.5v-4.5h-4.5" />
                        </svg>
                        <div>مدیریت آموزش ها</div>
                    </a>
                    <ul class="menu-sub">
                        <!-- Cards -->
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('blogCategoriesAdmin.index') }}">
                                <div>مدیریت دسته بندی</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('blogAdmin') }}">
                                <div>مدیریت اموزش</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item settings">
                    <a class="menu-link" href="{{ route('settings.index') }}">
                        <i class="menu-icon tf-icons ti ti-settings"></i>
                        <div>تنظیمات</div>
                    </a>
                </li>
            @endif


            <li class="menu-item blog">
                <a class="menu-link" href="{{ route('internalBlog') }}">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-school">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                        <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                    </svg>

                    <div>آمورش ها</div>
                </a>
            </li>
            @if (auth()->user()->isAdmin == 1 || auth()->user()->is_support == 1)
                <li class="menu-item tickets_admin">
                    <a class="menu-link " href="{{ route('support.tickets.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-lifebuoy">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 12a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M15 15l3.35 3.35" />
                            <path d="M9 15l-3.35 3.35" />
                            <path d="M5.65 5.65l3.35 3.35" />
                            <path d="M18.35 5.65l-3.35 3.35" />
                        </svg>
                        <div>تیم پشتیبانی</div>
                    </a>
                </li>
            @endif
            <li class="menu-item tickets">
                <a class="menu-link " href="{{ route('tickets') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-lifebuoy">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 12a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M15 15l3.35 3.35" />
                        <path d="M9 15l-3.35 3.35" />
                        <path d="M5.65 5.65l3.35 3.35" />
                        <path d="M18.35 5.65l-3.35 3.35" />
                    </svg>
                    <div>تیکت پشتیبانی</div>
                </a>
            </li>
            @php
                use App\Models\UserApiLbank;
                $exists = UserApiLbank::where('user_id', auth()->user()->id)
                    ->where('is_connected', 1)
                    ->exists();
            @endphp
            @if ($exists)
                <li class="menu-item autotrade">
                    <a class="menu-link" href="https://remote.roohitrade.ir" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-device-imac-x">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M13 17h-9a1 1 0 0 1 -1 -1v-12a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v9" />
                            <path d="M3 13h18" />
                            <path d="M8 21h5" />
                            <path d="M10 17l-.5 4" />
                            <path d="M22 22l-5 -5" />
                            <path d="M17 22l5 -5" />
                        </svg>

                        <div>اتصال به LBank</div>
                    </a>
                </li>
            @endif
            <?php /*
            <li class="menu-item autotrade">
                <a class="menu-link" href="{{ route('autoTradeSetting') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-link">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 15l6 -6" />
                        <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                        <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                    </svg>
                    <div>اتصال وب سرویس LBank</div>
                </a>
            </li> */
            ?>

        </ul>
    </div>
</aside>
