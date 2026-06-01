<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>داشبورد پشتیبان تیم فروش - روحی بات</title>
    <link href="{{ asset('/dashboard_theme') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/css/demo.css" rel="stylesheet" />
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/js/config.js"></script>
    <link href="{{ asset('/dashboard_theme') }}/assets/css/rtl.css" rel="stylesheet" />
</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            @include('dashboard.sections.navbar')
            <div class="layout-page">
                <div class="content-wrapper">
                    @include('dashboard.sections.aside')
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                            <div>
                                <h4 class="mb-1"><span class="text-muted fw-light">تیم فروش /</span> پیشخوان فروش
                                </h4>
                                <p class="text-muted mb-0">
                                    @if (!empty($isFullSalesScope))
                                        آمار بر اساس همه کاربران و گروه‌های فروش نمایش داده می‌شود.
                                    @else
                                        آمار فقط بر اساس گروه‌هایی است که به شما اختصاص داده شده‌اند.
                                    @endif
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('users.all') }}" class="btn btn-primary">مشاهده کاربران</a>
                                <a href="{{ route('users.activeUsers') }}" class="btn btn-label-success">کاربران
                                    فعال</a>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="avatar mb-3"><span
                                                class="avatar-initial rounded bg-label-primary"><i
                                                    class="ti ti-users-group"></i></span></div>
                                        <h6 class="mb-2">تعداد گروه‌ها</h6>
                                        <h3 class="mb-1 text-primary">{{ number_format($groupsCount) }}</h3>
                                        <small
                                            class="text-muted">{{ !empty($isFullSalesScope) ? 'گروه‌های فعال فروش' : 'گروه‌های اختصاصی شما' }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="avatar mb-3"><span class="avatar-initial rounded bg-label-info"><i
                                                    class="ti ti-users"></i></span></div>
                                        <h6 class="mb-2">تعداد کاربران</h6>
                                        <h3 class="mb-1 text-info">{{ number_format($usersCount) }}</h3>
                                        <small
                                            class="text-muted">{{ !empty($isFullSalesScope) ? 'کل کاربران سایت' : 'کل کاربران گروه‌های شما' }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="avatar mb-3"><span
                                                class="avatar-initial rounded bg-label-success"><i
                                                    class="ti ti-crown"></i></span></div>
                                        <h6 class="mb-2">مشتریان فعال</h6>
                                        <h3 class="mb-1 text-success">{{ number_format($activeUsersCount) }}</h3>
                                        <small class="text-muted">دارای اشتراک فعال</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="avatar mb-3"><span
                                                class="avatar-initial rounded bg-label-warning"><i
                                                    class="ti ti-alert-circle"></i></span></div>
                                        <h6 class="mb-2">کاربران رفته</h6>
                                        <h3 class="mb-1 text-warning">{{ number_format($expiredUsersCount) }}</h3>
                                        <small class="text-muted">قبلاً VIP داشته‌اند</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-12 col-xl-5">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="mb-3">
                                            {{ !empty($isFullSalesScope) ? 'گروه‌های فروش' : 'گروه‌های اختصاصی' }}</h6>
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>گروه</th>
                                                        <th>کاربران</th>
                                                        <th>فعال</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($assignedGroups as $group)
                                                        <tr>
                                                            <td>
                                                                <div class="fw-semibold">{{ $group->name }}</div>
                                                                @if ($group->description)
                                                                    <small
                                                                        class="text-muted">{{ $group->description }}</small>
                                                                @endif
                                                            </td>
                                                            <td>{{ number_format($group->members_count) }}</td>
                                                            <td><span
                                                                    class="badge bg-label-success">{{ number_format($group->active_members_count) }}</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted py-4">هنوز
                                                                گروهی برای نمایش وجود ندارد.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-xl-7">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">
                                                {{ !empty($isFullSalesScope) ? 'آخرین کاربران' : 'آخرین کاربران اختصاصی' }}
                                            </h6>
                                            <a href="{{ route('users.all') }}" class="btn btn-sm btn-label-primary">همه
                                                کاربران</a>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>کاربر</th>
                                                        <th>گروه‌ها</th>
                                                        <th>وضعیت</th>
                                                        <th>عملیات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($recentUsers as $user)
                                                        <tr>
                                                            <td>
                                                                <div class="fw-semibold">
                                                                    {{ $user->nam ?: $user->name ?: 'کاربر #' . $user->id }}
                                                                </div>
                                                                <small
                                                                    class="text-muted">{{ $user->mobile ?: $user->email }}</small>
                                                            </td>
                                                            <td>
                                                                @foreach ($user->userGroups as $group)
                                                                    <span
                                                                        class="badge bg-label-primary mb-1">{{ $group->name }}</span>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @if ($user->has_active_vip)
                                                                    <span class="badge bg-label-success">مشتری
                                                                        فعال</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-label-secondary">غیرفعال</span>
                                                                @endif
                                                            </td>
                                                            <td><a href="{{ route('users.detail', $user->id) }}"
                                                                    class="btn btn-sm btn-info">مشاهده</a></td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-4">
                                                                کاربری برای نمایش وجود ندارد.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('dashboard.sections.footer')
                        <div class="content-backdrop fade"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <script>
        $('.dashboard').addClass('active').removeClass('open');
    </script>
</body>

</html>
