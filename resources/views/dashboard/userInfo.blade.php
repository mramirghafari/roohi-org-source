<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>جزئیات کاربرسایت - روحی بات</title>
    <meta content="" name="description" />
    <!-- Favicon -->
    <link href="{{ asset('/dashboard_theme') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <!-- Icons -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/flag-icons.css" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/css/demo.css" rel="stylesheet" />
    <!-- Vendors CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/node-waves/node-waves.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/typeahead-js/typeahead.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css"
        rel="stylesheet" />
    <link
        href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/libs/flatpickr/flatpickr.css" rel="stylesheet" />
    <!-- Row Group CSS -->
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css"
        rel="stylesheet" />
    <!-- Form Validation -->
    <link
        href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/@form-validation/form-validation.css" rel="stylesheet"/>
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/animate-css/animate.css" rel="stylesheet"/>
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.css" rel="stylesheet"/>
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ asset('/dashboard_theme') }}/assets/css/rtl.css" rel="stylesheet"/>
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
    <div class="layout-container">
        @include('dashboard.sections.navbar')
        <!-- Layout container -->
        <div class="layout-page">
            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Menu -->
                @include('dashboard.sections.aside')
                <!-- / Menu -->
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
          
          <!-- Header -->
          <div class="row">
            <div class="col">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light">مشخصات کاربر/</span> پروفایل</h4>
            </div>
            <div class="col text-end pt-3">
              <a href="{{ session('backlink') }}" class="btn btn-label-dark waves-effect ms-3" type="button">
                  بازگشت
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M15.75 19.5L8.25 12L15.75 4.5" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
              </a>
            </div>
            @if (session('success'))
              <div class="col-12 mt-2">
                <p class="alert alert-success col-12">{{ session('success') }}</p>
              </div>
            @endif

            @if (session('ok'))
              <div class="col-12 mt-2">
                <p class="alert alert-success col-12">{{ session('ok') }}</p>
              </div>
            @endif

            @if (session('error'))
              <div class="col-12 mt-2">
                <p class="alert alert-danger col-12">{{ session('error') }}</p>
              </div>
            @endif


          </div>
          <style>
            .user-profile-header-banner img {
                width: 100%;
                object-fit: cover;
                height: 250px;
            }
          </style>
          <div class="row">
            <div class="col-12">
              <div class="card mb-4">
                <div class="user-profile-header-banner"> <img src="{{ asset('/dashboard_theme') }}/assets/img/pages/profile-banner.png" alt="تصویر بنر" class="rounded-top"> </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                  <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto"> <img src="{{ asset('/dashboard_theme') }}/assets/img/avatars/14.png" alt="تصویر کاربر" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img"> </div>
                  <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                      <div class="user-profile-info">
                        <h4 class="d-inline-block mb-3 align-items-center gap-2">
                            <span>{{ $user->nam }}</span>
                            
                        </h4>
                        @if ((int) $user->isAdmin === 1)
                                <small class="badge bg-label-success">مدیر کل</small>
                            @elseif ((int) $user->is_support === 1)
                                <small class="badge bg-label-warning">ادمین پشتیبانی</small>
                            @else
                                <small class="badge bg-label-info">کاربر معمولی</small>
                            @endif
                        <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                          <li class="list-inline-item d-flex gap-1"> <i class="ti ti-calendar"></i>عضویت در سایت: {{ verta($user->register_date)->format('Y/m/d') }}</li>
                                                    @forelse ($userGroups as $group)
                                                        <li class="list-inline-item d-flex gap-1">
                                                                <i class="ti ti-users-group"></i>
                                                                <span class="badge bg-label-primary">{{ $group->name }}</span>
                                                        </li>
                                                    @empty
                                                        <li class="list-inline-item d-flex gap-1">
                                                                <i class="ti ti-users-group"></i>
                                                                <span class="badge bg-label-secondary">بدون گروه</span>
                                                        </li> @endforelse
                                                    @foreach ($userGroupSupportAccounts as $supportAccount)
                                                        <li class="list-inline-item d-flex gap-1">
                                                                <i class="ti ti-user-check"></i>
                                                                <span class="badge bg-label-info">
                                                                        مسئول گروه: {{ $supportAccount->nam ?: $supportAccount->name ?: 'کاربر #' . $supportAccount->id }}
                                                                </span>
                                                        </li> @endforeach
                          @if ($user->lbank_uid != null) <li class="list-inline-item d-flex gap-1">
                                 <i class="ti ti-key"></i>یو آیدی ال بانک: {{ $user->lbank_uid }}
                                <form method="POST" action="{{ route('users.removeUserUid', $user->id) }}" class="d-inline-block ms-2">
                                    @csrf
                                <button type="submit" class="remove-uid text-danger" data-bs-custom-class="tooltip-danger" data-bs-original-title="حذف UID کاربر" data-bs-placement="top" data-bs-toggle="tooltip" style="background: none;border: 0 none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-square-rounded-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm-1.489 7.14a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" fill="currentColor" stroke-width="0" /></svg>
                                </button>
                                </form>
                                </li>
                            <li class="list-inline-item d-flex gap-1"> <i class="ti ti-wallet"></i>موجودی ولت ال بانک: {{ $user->latestBalanceLog?->balance . '$' }}</li> @endif
                          
                        </ul>
                      </div>
                      @if ($user->lbank_uid != null || ($user->coincall_uid != null && $user->has_active_vip))
<a href="javascript:void(0)"
        class="btn btn-primary waves-effect waves-light"> <i class="ti ti-check me-1"></i>کاربر فعال </a>
@else
    <a href="javascript:void(0)" class="btn btn-secondary waves-effect waves-light"> غیرفعال </a>
    @endif
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <!--/ Header -->
    <!-- User Profile Content -->
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-12">
            <!-- About User -->
            <div class="card mb-2">
                <div class="card-body">
                    <small class="card-text text-uppercase">درباره</small>
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3"> <i class="ti ti-user text-heading"></i><span
                                class="fw-medium mx-2 text-heading">نام کاربر:</span> <span>{{ $user->nam }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-device-mobile">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M6 5a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-14" />
                                <path d="M11 4h2" />
                                <path d="M12 17v.01" />
                            </svg>
                            <span class="fw-medium mx-2 text-heading">شماره موبایل: </span>
                            <span>{{ $user->mobile }}</span>
                        </li>
                        @if ($user->username != null)
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-check text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">عضویت ربات تلگرام:</span>
                                <span>دارد</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-info-square-rounded">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 9h.01" />
                                    <path d="M11 12h1v4h1" />
                                    <path
                                        d="M12 3c7.2 0 9 1.8 9 9c0 7.2 -1.8 9 -9 9c-7.2 0 -9 -1.8 -9 -9c0 -7.2 1.8 -9 9 -9" />
                                </svg>
                                <span class="fw-medium mx-2 text-heading">نام تلگرامی:</span>
                                <span>{{ $user->name . ' ' . $user->last_name }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                    <path d="M15 19l2 2l4 -4" />
                                </svg>
                                <span class="fw-medium mx-2 text-heading">یوزرنیم تلگرام:</span>
                                <a href="https://t.me/{{ $user->username }}" target="_blank">
                                    {{ $user->username }}
                                </a>
                            </li>
                        @endif
                        <li class="d-flex align-items-center mb-3"> <i class="ti ti-flag text-heading"></i><span
                                class="fw-medium mx-2 text-heading">وضعیت کاربر:</span>
                            @if ($user->has_active_vip)
                                <span class="badge bg-label-success me-1">اشتراک VIP</span>
                            @else
                                <span class="badge bg-label-secondary me-1">کاربر سایت</span>
                            @endif

                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <i class="ti ti-users-group text-heading mt-1"></i>
                            <span class="fw-medium mx-2 text-heading">گروه‌ها:</span>
                            <span>
                                @forelse ($userGroups as $group)
                                    <span class="badge bg-label-primary mb-1">{{ $group->name }}</span>
                                @empty
                                    <span class="badge bg-label-secondary">بدون گروه</span>
                                @endforelse
                            </span>
                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <i class="ti ti-user-star text-heading mt-1"></i>
                            <span class="fw-medium mx-2 text-heading">مسئول‌ها:</span>
                            <span>
                                @forelse ($userGroupSupportAccounts as $supportAccount)
                                    <span class="badge bg-label-info mb-1">
                                        {{ $supportAccount->nam ?: $supportAccount->name ?: 'کاربر #' . $supportAccount->id }}
                                        @if ($supportAccount->roles->isNotEmpty())
                                            - {{ $supportAccount->roles->pluck('name')->implode('، ') }}
                                        @endif
                                    </span>
                                @empty
                                    <span class="badge bg-label-secondary">مسئولی ثبت نشده</span>
                                @endforelse
                            </span>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="card my-3">
                <div class="row">
                    <div class="col-12">
                        <div class="card-body">
                            <label class="card-text text-uppercase">یادداشت مدیریت:</label>
                            <form method="POST" action="{{ route('user.updateAdminNote', $user->id) }}">
                                @csrf
                                <textarea name="admin_note" class="form-control mb-3"
                                    placeholder="در صورت نیاز میتوانید یادداشتی برای این کاربر ثبت کنید...">{{ $user->admin_note }}</textarea>
                                <button type="submit" class="btn btn-primary d-block w-100 mt-3">ذخیره
                                    یادداشت</button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="col-12 mt-3">
                        <small class="card-text text-uppercase">ساخت سشن ریموت</small>
                        <form method="POST" action="{{ route('setRemoteBrowserToken', $user->id) }}">
                            @csrf
                            <ul class="list-unstyled mb-4 mt-3">

                                <li class="d-flex align-items-center mb-3">
                                    <input type="text" name="remote_username" class="form-control mb-3"
                                        placeholder="نام کاربری"
                                        value="{{ old('remote_username', $remoteDesktopAccess->username ?? $user->mobile) }}" />
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    @php
                                        $remotePasswordValue = old(
                                            'remote_password',
                                            $remoteDesktopAccess?->passwordForForm($user->mobile) ?? $user->mobile,
                                        );
                                    @endphp
                                    <input type="text" name="remote_password" class="form-control mb-3"
                                        placeholder="رمز عبور ریموت" value="{{ $remotePasswordValue }}" />
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <input type="number" min="30" max="43200" name="remote_ttl_minutes"
                                        class="form-control mb-3" placeholder="مدت اعتبار (دقیقه)"
                                        value="{{ old('remote_ttl_minutes', data_get($remoteDesktopAccess, 'meta.ttl_minutes', 43200)) }}" />
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <select name="remote_profile" class="form-select mb-3">
                                        @php
                                            $activeProfile = old(
                                                'remote_profile',
                                                data_get(
                                                    $remoteDesktopAccess,
                                                    'meta.desktop_profile',
                                                    config('remote_desktop.default_profile', 'balanced'),
                                                ),
                                            );
                                        @endphp
                                        <option value="lightweight"
                                            {{ $activeProfile === 'lightweight' ? 'selected' : '' }}>سبک (حداقل منابع)
                                        </option>
                                        <option value="balanced"
                                            {{ $activeProfile === 'balanced' ? 'selected' : '' }}>متعادل (پیشنهادی)
                                        </option>
                                        <option value="performance"
                                            {{ $activeProfile === 'performance' ? 'selected' : '' }}>قدرتی (برای فشار
                                            کاری بیشتر)</option>
                                    </select>
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <select name="remote_status" class="form-select mb-3">
                                        <option value="" disabled>انتخاب کنید... (ست نشده)</option>
                                        <option value="1"
                                            {{ (string) old('remote_status', (int) ($remoteDesktopAccess->is_enabled ?? 0)) === '1' ? 'selected' : '' }}>
                                            فعال</option>
                                        <option value="0"
                                            {{ (string) old('remote_status', (int) ($remoteDesktopAccess->is_enabled ?? 0)) === '0' ? 'selected' : '' }}>
                                            غیرفعال</option>
                                    </select>
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <input type="number" step="0.1" min="0.5" max="4"
                                        name="remote_cpus" class="form-control mb-3"
                                        placeholder="CPU اختصاصی (اختیاری - مثلا 1.2)"
                                        value="{{ old('remote_cpus', data_get($remoteDesktopAccess, 'meta.resource_overrides.cpus')) }}" />
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <input type="number" min="768" max="4096" name="remote_memory_mb"
                                        class="form-control mb-3" placeholder="RAM به MB (اختیاری - مثلا 1536)"
                                        value="{{ old('remote_memory_mb', data_get($remoteDesktopAccess, 'meta.resource_overrides.memory_mb')) }}" />
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <input type="number" min="1024" max="6144" name="remote_memory_swap_mb"
                                        class="form-control mb-3" placeholder="Swap به MB (اختیاری - مثلا 2048)"
                                        value="{{ old('remote_memory_swap_mb', data_get($remoteDesktopAccess, 'meta.resource_overrides.memory_swap_mb')) }}" />
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <input type="number" min="128" max="1024" name="remote_shm_mb"
                                        class="form-control mb-3" placeholder="Shared memory MB (اختیاری - مثلا 256)"
                                        value="{{ old('remote_shm_mb', data_get($remoteDesktopAccess, 'meta.resource_overrides.shm_mb')) }}" />
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <input type="number" min="128" max="1024" name="remote_pids_limit"
                                        class="form-control mb-3" placeholder="PID limit (اختیاری - مثلا 256)"
                                        value="{{ old('remote_pids_limit', data_get($remoteDesktopAccess, 'meta.resource_overrides.pids_limit')) }}" />
                                </li>


                                <li class="d-flex align-items-center mb-3">
                                    <button type="submit" class="btn btn-primary d-block w-100">تنظیم سشن
                                        مرورگر</button>
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <p class="alert alert-info col-12">الان هر کاربر بر اساس پروفایل انتخابی کانتینر
                                        جداگانه می‌گیرد. اگر فیلدهای CPU/RAM/Swap/SHM را پر کنی، همان کاربر override
                                        اختصاصی می‌گیرد. کنترل واقعی منابع فعلاً per-user است؛ per-app واقعی نیاز به
                                        اجرای جداگانه هر برنامه در کانتینر مستقل دارد.
                                    </p>
                                </li>
                            </ul>

                        </form>

                        @if (session('issued_urls'))
                            <a href="{{ session('issued_urls') }}" target="_blank"
                                class="alert alert-success d-block text-center">لینک اختصاصی دسترسی کاربر</a>
                        @endif

                        <div class="card border mt-3 mb-3" id="remote-live-box"
                            data-stats-url="{{ route('admin.remote.stats', $user->id) }}"
                            data-monitor-url="{{ route('admin.remote.stats.monitor', $user->id) }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">مصرف لحظه‌ای کانتینر کاربر</h6>
                                    <a class="btn btn-sm btn-label-primary"
                                        href="{{ route('admin.remote.stats.monitor', $user->id) }}"
                                        target="_blank">نمایش مانیتور realtime</a>
                                </div>

                                <div id="remote-live-state" class="alert alert-secondary py-2 px-3 mb-3">در حال دریافت
                                    داده...</div>

                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <small>CPU</small>
                                        <small id="remote-live-cpu">-</small>
                                    </div>
                                    <div class="progress" style="height:8px;">
                                        <div id="remote-live-cpu-bar" class="progress-bar bg-primary"
                                            role="progressbar" style="width:0%"></div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <small>RAM</small>
                                        <small id="remote-live-mem">-</small>
                                    </div>
                                    <div class="progress" style="height:8px;">
                                        <div id="remote-live-mem-bar" class="progress-bar bg-warning"
                                            role="progressbar" style="width:0%"></div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-1">
                                    <div class="col-6"><small class="text-muted">Network:</small> <small
                                            id="remote-live-net">-</small></div>
                                    <div class="col-6"><small class="text-muted">PIDs:</small> <small
                                            id="remote-live-pids">-</small></div>
                                    <div class="col-12"><small class="text-muted">Container:</small> <small
                                            id="remote-live-container">-</small></div>
                                    <div class="col-12"><small class="text-muted">آخرین بروزرسانی:</small> <small
                                            id="remote-live-updated">-</small></div>
                                </div>
                            </div>
                        </div>

                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-check text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">وضعیت ریموت</span>

                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <span
                                    class="badge bg-label-{{ $remoteDesktopAccess->is_enabled ?? false ? 'success' : 'secondary' }}">
                                    {{ $remoteDesktopAccess->is_enabled ?? false ? 'فعال' : 'غیرفعال' }}
                                </span>
                            </li>
                        </ul>
                    </div>


                </div>

            </div>
            <!--/ About User -->

        </div>
        <div class="col-md-9 col-12">

            <div class="row">


            </div>

            <!-- Projects table -->
            <div class="card">

                <div class="nav-align-top nav-tabs-shadow mb-4">
                    <ul class="nav nav-tabs mx-3" role="tablist">
                        <li class="nav-item">
                            <button aria-controls="navs-top-profile" aria-selected="true" class="nav-link active"
                                data-bs-target="#subscribe-history" data-bs-toggle="tab" role="tab"
                                type="button">
                                مدیریت و تاریخچه اشتراک
                            </button>
                        </li>
                        <li class="nav-item">
                            <button aria-controls="navs-top-home" aria-selected="false" class="nav-link"
                                data-bs-target="#balance-log" data-bs-toggle="tab" role="tab" type="button">
                                لاگ موجودی ها
                            </button>
                        </li>
                        <li class="nav-item">
                            <button aria-controls="navs-top-messages" aria-selected="false" class="nav-link"
                                data-bs-target="#navs-top-messages" data-bs-toggle="tab" role="tab"
                                type="button">
                                زیرمجموعه ها
                            </button>
                        </li>
                        <li class="nav-item">
                            <button aria-controls="user-notifs" aria-selected="false" class="nav-link"
                                data-bs-target="#user-notifs" data-bs-toggle="tab" role="tab" type="button">
                                اعلانات کاربر
                            </button>
                        </li>
                        <li class="nav-item">
                            <button aria-controls="user-tickets" aria-selected="false" class="nav-link"
                                data-bs-target="#user-tickets" data-bs-toggle="tab" role="tab" type="button">
                                تیکت ها
                            </button>
                        </li>
                        <li class="nav-item">
                            <button aria-controls="user-wallet" aria-selected="false" class="nav-link"
                                data-bs-target="#user-wallet" data-bs-toggle="tab" role="tab" type="button">
                                کیف پول و تراکنش ها
                            </button>
                        </li>
                        <li class="nav-item">
                            <button aria-controls="user-groups" aria-selected="false" class="nav-link"
                                data-bs-target="#user-groups" data-bs-toggle="tab" role="tab" type="button">
                                گروه های کاربر
                            </button>
                        </li>
                        <li class="nav-item">
                            <button aria-controls="user-permissions" aria-selected="false" class="nav-link"
                                data-bs-target="#user-permissions" data-bs-toggle="tab" role="tab"
                                type="button">
                                اعطای دسترسی
                            </button>
                        </li>
                        <li class="nav-item">
                            <button aria-controls="user-audit-logs" aria-selected="false" class="nav-link"
                                data-bs-target="#user-audit-logs" data-bs-toggle="tab" role="tab"
                                type="button">
                                لاگ کاربر
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade" id="balance-log" role="tabpanel">
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th width=40>ردیف</th>
                                            <th>موجودی</th>
                                            <th>تاریخ</th>

                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @if ($user->balanceLogs)
                                            <?php $x = 1; ?>
                                            <?php foreach ($user->balanceLogs as $index => $log): ?>
                                            <?php
                                            $current = $log->balance;
                                            $prev = isset($user->balanceLogs[$index + 1]) ? $user->balanceLogs[$index + 1]->balance : null;
                                            $trendHtml = '';
                                            if ($prev !== null) {
                                                $cmp = bccomp($current, $prev, 8);
                                                if ($cmp === 1) {
                                                    $trendHtml = '<span style="color:#28C76F">&#8679;</span>';
                                                } elseif ($cmp === -1) {
                                                    $trendHtml = '<span style="color:rgb(250,23,23)">&#8681;</span>';
                                                } else {
                                                    $trendHtml = '<span style="color:#999">&#8722;</span>';
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $x++; ?></td>
                                                <td>${{ $current }} {!! $trendHtml !!}</td>
                                                <td>{{ verta($log->time)->format('Y/m/d H:i:s') }}</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="subscribe-history" role="tabpanel">
                            <div class="row">
                                @if ($user->has_active_vip)
                                    <div class="col-12 col-md-6 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-check">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6" />
                                            <path d="M16 3v4" />
                                            <path d="M8 3v4" />
                                            <path d="M4 11h16" />
                                            <path d="M15 19l2 2l4 -4" />
                                        </svg>

                                        <span class="fw-medium mx-2 text-heading"> تاریخ شروع اشتراک:</span>
                                        <span
                                            class="badge bg-success"><bdi>{{ verta($user->active_subscribe?->start_vip)->format('Y/m/d H:i:s') }}</bdi></span>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3 text-end">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-cancel">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                                            <path d="M16 3v4" />
                                            <path d="M8 3v4" />
                                            <path d="M4 11h16" />
                                            <path d="M16 19a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                            <path d="M17 21l4 -4" />
                                        </svg>

                                        <span class="fw-medium mx-2 text-heading"> تاریخ پایان:</span>
                                        <span
                                            class="badge bg-danger"><bdi>{{ verta($user->active_subscribe?->exp_vip)->format('Y/m/d H:i:s') }}</bdi></span>
                                    </div>
                                @else
                                    <p class="text-danger">اشتراک فعال وجود ندارد.</p>
                                @endif
                                <div class="col-12 mb-3">
                                    <form method="POST" action="{{ route('user.UpdateUserInfo', $user->id) }}">
                                        @csrf
                                        <div class="row">
                                            <select class="form-select mb-3" name="vip">
                                                <option>عملیات اشتراک</option>
                                                <option value="5"
                                                    {{ $user->active_subscribe?->vip == 5 ? 'selected' : '' }}>اشتراک 5
                                                    روزه
                                                </option>
                                                <option value="10"
                                                    {{ $user->active_subscribe?->vip == 10 ? 'selected' : '' }}>اشتراک
                                                    10 روزه
                                                </option>
                                                <option value="30"
                                                    {{ $user->active_subscribe?->vip == 30 ? 'selected' : '' }}>اشتراک
                                                    30 روزه
                                                </option>
                                                <option value="60"
                                                    {{ $user->active_subscribe?->vip == 60 ? 'selected' : '' }}>اشتراک
                                                    60 روزه
                                                </option>
                                                <option value="90"
                                                    {{ $user->active_subscribe?->vip == 90 ? 'selected' : '' }}>اشتراک
                                                    90 روزه
                                                </option>
                                                <option value="000"
                                                    {{ $user->active_subscribe?->vip == 0 ? 'selected' : '' }}>لغو
                                                    اشتراک
                                                    (مرجوعی)</option>
                                            </select>
                                            <input type="number" name="addday" class="form-control mb-3"
                                                placeholder="افزودن روز به اشتراک (اختیاری)" />
                                            <div class="col-12 mb-3">
                                                <button type="submit" class="btn btn-success d-block w-100">بروزرسانی
                                                    اشتراک</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th width=40>ردیف</th>
                                            <th>مدت اشتراک (روز)</th>
                                            <th>شروع اشتراک</th>
                                            <th>پایان اشتراک</th>
                                            <th>نوع</th>
                                            <th>روش</th>
                                            <th>وضعیت</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @php
                                            $x = 1;
                                        @endphp

                                        @if ($subscribes && $subscribes->count() > 0)
                                            @foreach ($subscribes as $subscribe)
                                                <tr>
                                                    <td>{{ $x++ }}</td>
                                                    <td>
                                                        <span class="badge bg-label-primary">{{ $subscribe->vip }}
                                                            روز</span>
                                                    </td>
                                                    <td>
                                                        @if ($subscribe->start_vip)
                                                            <bdi>{{ verta($subscribe->start_vip)->format('Y/m/d H:i:s') }}</bdi>
                                                        @else
                                                            <span class="badge bg-label-secondary">ندارد</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($subscribe->exp_vip)
                                                            <bdi>{{ verta($subscribe->exp_vip)->format('Y/m/d H:i:s') }}</bdi>
                                                        @else
                                                            <span class="badge bg-label-secondary">ندارد</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @switch($subscribe->type)
                                                            @case(1)
                                                                <span class="badge bg-label-info">تله‌گرام بات</span>
                                                            @break

                                                            @case(2)
                                                                <span class="badge bg-label-warning">اعطای ادمین</span>
                                                            @break

                                                            @case(3)
                                                                <span class="badge bg-label-success">خرید</span>
                                                            @break

                                                            @case(4)
                                                                <span class="badge bg-label-primary">دمو</span>
                                                            @break

                                                            @default
                                                                <span class="badge bg-label-secondary">نامشخص</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @if ($subscribe->method)
                                                            <span
                                                                class="badge bg-label-secondary">{{ $subscribe->method }}</span>
                                                        @else
                                                            <span class="badge bg-label-light">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($subscribe->status == 1)
                                                            <span class="badge bg-label-success">فعال</span>
                                                        @else
                                                            <span class="badge bg-label-danger">غیرفعال</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    هیچ سابسکریپشنی برای این کاربر وجود ندارد
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-top-messages" role="tabpanel">
                            <p> لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک
                                است
                                چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی
                                تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد</p>
                            <p class="mb-0"> لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده
                                از
                                طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است</p>
                        </div>

                        <div class="tab-pane fade" id="user-notifs" role="tabpanel">
                            <form method="POST" action="{{ route('users.sendNotif', $user->id) }}">
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="تیتر اعلان"
                                            name="title" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <textarea class="form-control" placeholder="متن پیام..." name="content"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary d-block w-100 mt-3">ارسال
                                            اعلان</button>
                                    </div>
                                </div>
                            </form>

                            <div class="row mt-3">
                                <h5 class="mt-3">لیست اعلانات دریافتی کاربر</h5>
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
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
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
                                                    <button aria-label="بستن" class="btn-close"
                                                        data-bs-dismiss="modal" type="button"></button>
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

                        <div class="tab-pane fade" id="user-tickets" role="tabpanel">
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th width=40>ردیف</th>
                                            <th>کد پیگیری</th>
                                            <th>موضوع</th>
                                            <th>بخش</th>
                                            <th>اولویت</th>
                                            <th>وضعیت</th>
                                            <th>مسئول</th>
                                            <th>تاریخ ایجاد</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @php
                                            $ticketRow = 1;
                                            $ticketStatusLabels = [
                                                'open' => 'در انتظار',
                                                'answered_by_user' => 'پاسخ کاربر',
                                                'answered_by_support' => 'پاسخ پشتیبانی',
                                                'closed' => 'بسته',
                                            ];
                                            $ticketStatusBadges = [
                                                'open' => 'badge bg-label-warning',
                                                'answered_by_user' => 'badge bg-label-info',
                                                'answered_by_support' => 'badge bg-label-danger',
                                                'closed' => 'badge bg-label-secondary',
                                            ];
                                        @endphp

                                        @if ($tickets && $tickets->count() > 0)
                                            @foreach ($tickets as $ticket)
                                                <tr>
                                                    <td>{{ $ticketRow++ }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-label-primary">{{ $ticket->tracking_code ?? '-' }}</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('support.tickets.show', $ticket->id) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            {{ $ticket->subject }}
                                                        </a>
                                                    </td>
                                                    <td>{{ \App\Models\Ticket::DEPARTMENTS[$ticket->department] ?? $ticket->department }}
                                                    </td>
                                                    <td>{{ \App\Models\Ticket::PRIORITIES[$ticket->priority] ?? $ticket->priority }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="{{ $ticketStatusBadges[$ticket->status] ?? 'badge bg-label-secondary' }}">
                                                            {{ $ticketStatusLabels[$ticket->status] ?? $ticket->status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $ticket->assignee?->nam ?? ($ticket->assignee?->name ?? '-') }}
                                                    </td>
                                                    <td>
                                                        @if ($ticket->created_at)
                                                            <bdi>{{ verta($ticket->created_at)->format('Y/m/d H:i:s') }}</bdi>
                                                        @else
                                                            <span class="badge bg-label-secondary">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    هیچ تیکتی برای این کاربر وجود ندارد
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="user-wallet" role="tabpanel">
                            <div class="row">
                                <div class="col-12 col-lg-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3">موجودی فعلی کیف پول کاربر</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>تومان</span>
                                                <bdi>{{ number_format((float) $userWallet->toman_balance, 0) }}</bdi>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>تتر</span>
                                                <bdi>{{ number_format((float) $userWallet->usdt_balance, 0) }}</bdi>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>STARS</span>
                                                <bdi>{{ number_format((int) $userWallet->stars_balance) }}</bdi>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-8 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3">واریز / کسر کیف پول توسط مدیریت</h6>
                                            <form method="POST"
                                                action="{{ route('user.wallet.update', $user->id) }}">
                                                @csrf
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label">نوع عملیات</label>
                                                        <select class="form-select" name="operation" required>
                                                            <option value="deposit"
                                                                {{ old('operation') === 'deposit' ? 'selected' : '' }}>
                                                                واریز</option>
                                                            <option value="withdraw"
                                                                {{ old('operation') === 'withdraw' ? 'selected' : '' }}>
                                                                کسر</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label">نوع کیف پول</label>
                                                        <select class="form-select" name="asset" required>
                                                            <option value="toman"
                                                                {{ old('asset') === 'toman' ? 'selected' : '' }}>تومان
                                                            </option>
                                                            <option value="usdt"
                                                                {{ old('asset') === 'usdt' ? 'selected' : '' }}>تتر
                                                            </option>
                                                            <option value="stars"
                                                                {{ old('asset') === 'stars' ? 'selected' : '' }}>STARS
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label">مقدار</label>
                                                        <input type="text" id="wallet-amount-display"
                                                            class="form-control"
                                                            value="{{ old('amount') ? number_format((float) old('amount'), 0, '.', ',') : '' }}"
                                                            required>
                                                        <input type="hidden" id="wallet-amount" name="amount"
                                                            value="{{ old('amount') }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">توضیحات (اختیاری)</label>
                                                        <textarea class="form-control" rows="2" name="description" placeholder="مثال: شارژ هدیه مدیریت">{{ old('description') }}</textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-primary">ثبت تراکنش و
                                                            ارسال نوتیف به
                                                            کاربر</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3">تاریخچه تراکنش‌های کیف پول کاربر</h6>
                                            <div class="table-responsive text-nowrap">
                                                <table class="table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width=40>ردیف</th>
                                                            <th>نوع</th>
                                                            <th>دارایی</th>
                                                            <th>مقدار</th>
                                                            <th>قبل از عملیات</th>
                                                            <th>بعد از عملیات</th>
                                                            <th>توضیحات</th>
                                                            <th>تاریخ</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $walletTypeLabels = [
                                                                'deposit' => 'واریز',
                                                                'withdraw' => 'برداشت',
                                                                'transfer_in' => 'انتقال ورودی',
                                                                'transfer_out' => 'انتقال خروجی',
                                                                'swap' => 'تبدیل',
                                                                'reward' => 'جایزه',
                                                                'adjustment' => 'اصلاحی',
                                                            ];
                                                            $walletAssetLabels = [
                                                                'toman' => 'تومان',
                                                                'usdt' => 'تتر',
                                                                'stars' => 'STARS',
                                                            ];
                                                        @endphp

                                                        @forelse ($walletTransactions as $index => $transaction)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $walletTypeLabels[$transaction->type] ?? $transaction->type }}
                                                                </td>
                                                                <td>{{ $walletAssetLabels[$transaction->asset] ?? $transaction->asset }}
                                                                </td>
                                                                <td><bdi>{{ number_format((float) $transaction->amount, 0) }}</bdi>
                                                                </td>
                                                                <td><bdi>{{ number_format((float) $transaction->balance_before, 0) }}</bdi>
                                                                </td>
                                                                <td><bdi>{{ number_format((float) $transaction->balance_after, 0) }}</bdi>
                                                                </td>
                                                                <td>{{ $transaction->description ?? '-' }}</td>
                                                                <td>
                                                                    @if ($transaction->created_at)
                                                                        <bdi>{{ verta($transaction->created_at)->format('Y/m/d H:i:s') }}</bdi>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="8"
                                                                    class="text-center text-muted py-4">تراکنشی برای
                                                                    این
                                                                    کاربر ثبت نشده است</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="user-audit-logs" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">لاگ‌های این کاربر</h6>
                                <span class="badge bg-label-primary">{{ number_format($userAuditLogs->count()) }}
                                    رکورد آخر</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>زمان</th>
                                            <th>نوع</th>
                                            <th>مسیر / عملیات</th>
                                            <th>ثبت‌کننده</th>
                                            <th>نقش همان لحظه</th>
                                            <th>جزئیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($userAuditLogs as $auditLog)
                                            <tr>
                                                <td class="text-nowrap">
                                                    @if ($auditLog->occurred_at)
                                                        <div>
                                                            <bdi>{{ verta($auditLog->occurred_at)->format('Y/m/d') }}</bdi>
                                                        </div>
                                                        <small
                                                            class="text-muted"><bdi>{{ verta($auditLog->occurred_at)->format('H:i:s') }}</bdi></small>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-label-{{ $auditLog->event === 'page_view' ? 'info' : ($auditLog->event === 'role_assignment' ? 'warning' : 'primary') }}">
                                                        {{ $auditLog->event }}
                                                    </span>
                                                    @if ($auditLog->area)
                                                        <div><small class="text-muted">{{ $auditLog->area }}</small>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td style="min-width: 260px;">
                                                    <div class="fw-semibold">{{ $auditLog->action ?: '-' }}</div>
                                                    <small class="text-muted">{{ $auditLog->method }}
                                                        {{ $auditLog->path }}</small>
                                                    @if ($auditLog->route_name)
                                                        <div><small class="text-muted">route:
                                                                {{ $auditLog->route_name }}</small></div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($auditLog->user)
                                                        <div class="fw-semibold">
                                                            {{ $auditLog->user->nam ?: $auditLog->user->name ?: 'کاربر #' . $auditLog->user->id }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $auditLog->user->mobile ?: $auditLog->user->email }}</small>
                                                    @else
                                                        <span class="text-muted">سیستمی / حذف‌شده</span>
                                                    @endif
                                                </td>
                                                <td style="min-width: 180px;">
                                                    @forelse (($auditLog->actor_roles ?? []) as $role)
                                                        <span
                                                            class="badge bg-label-primary mb-1">{{ $role['name'] ?? ($role['slug'] ?? '-') }}</span>
                                                    @empty
                                                        <span class="badge bg-label-secondary">کاربر معمولی / بدون
                                                            نقش</span>
                                                    @endforelse
                                                    @if (!empty($auditLog->actor_permissions))
                                                        <div class="mt-1">
                                                            @foreach ($auditLog->actor_permissions as $permission)
                                                                <span
                                                                    class="badge bg-label-secondary mb-1">{{ $permission }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-label-info" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#user-audit-log-{{ $auditLog->id }}">مشاهده</button>
                                                </td>
                                            </tr>
                                            <tr class="collapse" id="user-audit-log-{{ $auditLog->id }}">
                                                <td colspan="6">
                                                    <div class="row g-3">
                                                        <div class="col-12 col-lg-6">
                                                            <div class="border rounded p-3 h-100">
                                                                <div class="fw-semibold mb-2">تغییرات</div>
                                                                <pre class="mb-0 small text-wrap" dir="ltr">{{ json_encode($auditLog->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-lg-6">
                                                            <div class="border rounded p-3 h-100">
                                                                <div class="fw-semibold mb-2">متادیتا</div>
                                                                <pre class="mb-0 small text-wrap" dir="ltr">{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">هنوز لاگی برای
                                                    این کاربر ثبت نشده است.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="user-permissions" role="tabpanel">
                            <div class="row">
                                <div class="col-12 col-lg-8">
                                    <form method="POST" action="{{ route('user.updatePermission', $user->id) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">سطح دسترسی کاربر</label>
                                            <select class="form-select" name="access_role" required>
                                                @php
                                                    $currentRole =
                                                        (int) $user->isAdmin === 1
                                                            ? 'super_admin'
                                                            : ((int) $user->is_support === 1
                                                                ? 'support_admin'
                                                                : 'normal_user');
                                                @endphp
                                                <option value="super_admin"
                                                    {{ old('access_role', $currentRole) === 'super_admin' ? 'selected' : '' }}>
                                                    مدیر کل
                                                </option>
                                                <option value="support_admin"
                                                    {{ old('access_role', $currentRole) === 'support_admin' ? 'selected' : '' }}>
                                                    ادمین
                                                    پشتیبانی</option>
                                                <option value="normal_user"
                                                    {{ old('access_role', $currentRole) === 'normal_user' ? 'selected' : '' }}>
                                                    کاربر
                                                    معمولی
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">نقش‌های کاربری</label>
                                            @php
                                                $selectedRoleIds = array_map(
                                                    'intval',
                                                    old('role_ids', $user->roles->pluck('id')->all()),
                                                );
                                            @endphp
                                            <div class="row g-2">
                                                @forelse ($allRoles as $role)
                                                    <div class="col-12 col-md-6">
                                                        <label class="border rounded p-3 d-block h-100"
                                                            for="user_role_{{ $role->id }}">
                                                            <div class="form-check mb-1">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="role_ids[]"
                                                                    id="user_role_{{ $role->id }}"
                                                                    value="{{ $role->id }}"
                                                                    {{ in_array((int) $role->id, $selectedRoleIds, true) ? 'checked' : '' }}>
                                                                <span
                                                                    class="form-check-label fw-semibold">{{ $role->name }}</span>
                                                            </div>
                                                            <small
                                                                class="text-muted d-block">{{ $role->slug }}</small>
                                                            @if ($role->description)
                                                                <small
                                                                    class="text-muted d-block mt-1">{{ $role->description }}</small>
                                                            @endif
                                                            @if (!empty($role->permissions))
                                                                <div class="mt-2">
                                                                    @foreach ($role->permissions as $permission)
                                                                        <span
                                                                            class="badge bg-label-primary mb-1">{{ $permission }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @empty
                                                    <div class="col-12">
                                                        <div class="alert alert-warning mb-0">هنوز نقش فعالی تعریف نشده
                                                            است.</div>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">ذخیره سطح دسترسی و
                                            نقش‌ها</button>
                                    </form>

                                    @error('access_role')
                                        <div class="alert alert-danger mt-3 mb-0">{{ $message }}</div>
                                    @enderror
                                    @error('role_ids')
                                        <div class="alert alert-danger mt-3 mb-0">{{ $message }}</div>
                                    @enderror
                                    @error('role_ids.*')
                                        <div class="alert alert-danger mt-3 mb-0">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-4 mt-3 mt-lg-0">
                                    <div class="alert alert-info mb-0">
                                        <div class="fw-bold mb-1">سطح فعلی کاربر</div>
                                        @if ((int) $user->isAdmin === 1)
                                            <span class="badge bg-label-danger">مدیر کل</span>
                                        @elseif ((int) $user->is_support === 1)
                                            <span class="badge bg-label-warning">ادمین پشتیبانی</span>
                                        @else
                                            <span class="badge bg-label-secondary">کاربر معمولی</span>
                                        @endif

                                        <div class="fw-bold mt-3 mb-1">نقش‌های فعلی</div>
                                        @forelse ($user->roles as $role)
                                            <span class="badge bg-label-primary mb-1">{{ $role->name }}</span>
                                        @empty
                                            <span class="badge bg-label-secondary">بدون نقش اختصاصی</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="user-groups" role="tabpanel">
                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3">افزودن کاربر به گروه</h6>
                                            <form method="POST" action="{{ route('user.groups.add', $user->id) }}">
                                                @csrf
                                                <div class="row g-2">
                                                    <div class="col-12 col-md-9">
                                                        <select class="form-select" name="group_id" required>
                                                            <option value="">انتخاب گروه</option>
                                                            @foreach ($allGroups as $group)
                                                                <option value="{{ $group->id }}">
                                                                    {{ $group->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-3">
                                                        <button type="submit"
                                                            class="btn btn-success w-100">افزودن</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <div class="alert alert-info mb-0">
                                        مدیریت کامل گروه‌ها و اساین پشتیبان‌ها از صفحه
                                        <a href="{{ route('user-groups.index') }}">گروه‌های کاربری</a>
                                        انجام می‌شود.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3">گروه‌های فعلی این کاربر</h6>
                                            <div class="table-responsive text-nowrap">
                                                <table class="table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>نام گروه</th>
                                                            <th>عملیات</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($userGroups as $index => $group)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $group->name }}</td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="{{ route('user-groups.show', $group->id) }}"
                                                                            class="btn btn-sm btn-label-info">جزئیات
                                                                            گروه</a>
                                                                        <form method="POST"
                                                                            action="{{ route('user.groups.remove', [$user->id, $group->id]) }}">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-label-danger">حذف
                                                                                از گروه</button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3"
                                                                    class="text-center text-muted py-4">این کاربر در
                                                                    هیچ
                                                                    گروهی عضو نیست</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>

            </div>
            <!--/ Projects table -->


        </div>
    </div>
    <!--/ User Profile Content -->
    </div>
    <!-- / Content -->
    <!-- Footer -->
    @include('dashboard.sections.footer')
    <!-- / Footer -->
    <div class="content-backdrop
        fade">
    </div>
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
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/i18n/fa.js"></script>
    <!-- Flat Picker -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/libs/jdate/jdate.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/libs/flatpickr/flatpickr-jalali.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/libs/flatpickr/l10n/fa.js"></script>
    <!-- Form Validation -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/@form-validation/auto-focus.js"></script>
    <!-- Main JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <script>
        $(function() {
            var dt_without_ajax_table = $('.datatables-direct-basic');

            // DataTable Direct
            // --------------------------------------------------------------------
            if (dt_without_ajax_table.length) {
                dt_without_ajax = dt_without_ajax_table.DataTable({
                    searching: true,
                    lengthChange: true,
                    ordering: true,
                    pageLength: 100,
                });

            }

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.remove-uid').forEach(function(el) {

                el.addEventListener('click', function(e) {

                    e.preventDefault(); // جلو سابمیت پیش‌فرض

                    let form = this.closest('form'); // ✅ گرفتن فرم والد

                    Swal.fire({
                        title: 'حذف UID کاربر؟',
                        text: 'در صورت تایید شما UID کاربر حذف میشود و مجدد باید اقدام به ثبت کند.',
                        icon: 'warning',

                        showCancelButton: false,

                        confirmButtonText: 'بله، انجام بده',
                        confirmButtonColor: '#d33',
                    }).then((result) => {

                        if (result.isConfirmed) {
                            form.submit(); // ✅ ارسال فرم
                        }

                    });

                });

            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountDisplayInput = document.getElementById('wallet-amount-display');
            const amountRawInput = document.getElementById('wallet-amount');

            if (!amountDisplayInput || !amountRawInput) {
                return;
            }

            const formatThousands = (value) => {
                if (!value) {
                    return '';
                }

                return value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            };

            const syncAmount = () => {
                const digits = amountDisplayInput.value.replace(/[^\d]/g, '');
                amountRawInput.value = digits;
                amountDisplayInput.value = formatThousands(digits);
            };

            amountDisplayInput.addEventListener('input', syncAmount);
            amountDisplayInput.form?.addEventListener('submit', syncAmount);

            syncAmount();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const box = document.getElementById('remote-live-box');
            if (!box) {
                return;
            }

            const statsUrl = box.dataset.statsUrl;
            if (!statsUrl) {
                return;
            }

            const stateEl = document.getElementById('remote-live-state');
            const cpuEl = document.getElementById('remote-live-cpu');
            const memEl = document.getElementById('remote-live-mem');
            const cpuBar = document.getElementById('remote-live-cpu-bar');
            const memBar = document.getElementById('remote-live-mem-bar');
            const netEl = document.getElementById('remote-live-net');
            const pidsEl = document.getElementById('remote-live-pids');
            const containerEl = document.getElementById('remote-live-container');
            const updatedEl = document.getElementById('remote-live-updated');

            const setState = (text, level) => {
                stateEl.textContent = text;
                stateEl.className = 'alert py-2 px-3 mb-3';
                if (level === 'ok') {
                    stateEl.classList.add('alert-success');
                } else if (level === 'warn') {
                    stateEl.classList.add('alert-warning');
                } else if (level === 'err') {
                    stateEl.classList.add('alert-danger');
                } else {
                    stateEl.classList.add('alert-secondary');
                }
            };

            const clamp = (num) => Math.max(0, Math.min(100, Number(num || 0)));

            const applyData = (data) => {
                if (!data || data.ok !== true) {
                    setState('خطا در دریافت داده', 'err');
                    return;
                }

                if (!data.running) {
                    setState('کانتینر فعال برای این کاربر پیدا نشد', 'warn');
                    cpuEl.textContent = '-';
                    memEl.textContent = '-';
                    cpuBar.style.width = '0%';
                    memBar.style.width = '0%';
                    netEl.textContent = '-';
                    pidsEl.textContent = '-';
                    containerEl.textContent = data.container_name || '-';
                    updatedEl.textContent = new Date().toLocaleTimeString('fa-IR');
                    return;
                }

                const cpu = clamp(data.cpu_percent);
                const mem = clamp(data.memory_percent);

                setState('کانتینر فعال است', 'ok');
                cpuEl.textContent = cpu.toFixed(2) + '%';
                memEl.textContent = (data.memory_usage || '-') + ' (' + mem.toFixed(2) + '%)';
                cpuBar.style.width = cpu + '%';
                memBar.style.width = mem + '%';
                netEl.textContent = data.net_io || '-';
                pidsEl.textContent = String(data.pids ?? '-');
                containerEl.textContent = data.container_name || '-';
                updatedEl.textContent = new Date().toLocaleTimeString('fa-IR');
            };

            let inflight = false;
            const poll = async () => {
                if (inflight || document.hidden) {
                    return;
                }

                inflight = true;
                try {
                    const resp = await fetch(statsUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        },
                        cache: 'no-store',
                    });

                    if (!resp.ok) {
                        throw new Error('HTTP ' + resp.status);
                    }

                    const json = await resp.json();
                    applyData(json);
                } catch (e) {
                    setState('دریافت realtime قطع شده: ' + (e?.message || 'unknown'), 'err');
                } finally {
                    inflight = false;
                }
            };

            poll();
            setInterval(poll, 3000);
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    poll();
                }
            });
        });
    </script>



    </body>

</html>
