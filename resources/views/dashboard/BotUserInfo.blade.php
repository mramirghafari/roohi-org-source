<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>کاربران ربات - روحی بات</title>
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
                        <h4>{{ $user->nam }}</h4>
                        <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                          <li class="list-inline-item d-flex gap-1"> <i class="ti ti-color-swatch"></i>{{ $user->name . ' ' . $user->last_name }}</li>
                          <li class="list-inline-item d-flex gap-1">
                            <i class="ti ti-email"></i> 
                            <a href="https://t.me/{{ $user->username }}" target="_blank">@ {{ $user->username }}</a>
                          </li>
                          <li class="list-inline-item d-flex gap-1"> <i class="ti ti-calendar"></i>عضویت در ربات: {{ verta($user->register_date)->format('Y/m/d') }}</li>
                          @if ($user->lbank_uid != null)
                            <li class="list-inline-item d-flex gap-1"> <i class="ti ti-key"></i>یو آیدی ال بانک: {{ $user->lbank_uid }}</li>
                            <li class="list-inline-item d-flex gap-1"> <i class="ti ti-wallet"></i>موجودی ولت ال بانک: {{ $user->latestBalance?->balance }}</li>
                          @endif
                          
                        </ul>
                      </div>
                      @if ($user->status == 1)
                        <a href="javascript:void(0)" class="btn btn-primary waves-effect waves-light"> <i class="ti ti-check me-1"></i>کاربر فعال </a>
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
            <div class="col-xl-4 col-lg-5 col-md-5">
              <!-- About User -->
              <div class="card mb-4">
                <div class="card-body"> <small class="card-text text-uppercase">درباره</small>
                  <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3"> <i class="ti ti-user text-heading"></i><span class="fw-medium mx-2 text-heading">نام کاربر:</span> <span>{{ $user->nam }}</span>                                                </li>
                    <li class="d-flex align-items-center mb-3"> <i class="ti ti-check text-heading"></i><span class="fw-medium mx-2 text-heading">شماره موبایل: </span> <span>{{ $user->mobile }}</span> </li>
                    <li class="d-flex align-items-center mb-3"> <i class="ti ti-check text-heading"></i><span class="fw-medium mx-2 text-heading">نام تلگرامی:</span> <span>{{ $user->name . ' ' . $user->last_name }}</span> </li>
                    <li class="d-flex align-items-center mb-3"> <i class="ti ti-crown text-heading"></i><span class="fw-medium mx-2 text-heading">یوزرنیم تلگرام:</span>
                      <a href="https://t.me/{{ $user->username }}" target="_blank">
                        {{ $user->username }}
                      </a>                                                
                    </li>
                    <li class="d-flex align-items-center mb-3"> <i class="ti ti-flag text-heading"></i><span class="fw-medium mx-2 text-heading">وضعیت کاربر:</span> 
                      @if ($user->status == 1)
                        <span>کاربر فعال</span>
                      @else
                        @if ($user->step == 1)
                          <span>ورود شماره</span>
                        @elseif($user->step == 2)
                          <span>ورود نام</span>
                        @elseif($user->step == 3)
                          <span>در انتظار تایید</span>
                      
                        @endif
                      @endif
                                         
                    </li>
                    
                  </ul> <small class="card-text text-uppercase">مدیریت کاربر:</small>
                  
                  <div class="col-12 mt-3">
                          <form method="POST" action="{{ route('bot1.UpdateUserInfo', $user->id) }}">
                            @csrf
                                <ul class="list-unstyled mb-4 mt-3">
                                  @if ($user->vip != null)
                                    <li class="d-flex align-items-center mb-3"> 
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M15 19l2 2l4 -4" /></svg>

                                      <span class="fw-medium mx-2 text-heading"> تاریخ شروع اشتراک:</span> 
                                      <span class="badge bg-success"><bdi>{{ verta($user->start_vip)->format('Y/m/d H:i:s') }}</bdi></span>                                                
                                    </li>
                                    <li class="d-flex align-items-center mb-3"> 
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-cancel"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M16 19a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M17 21l4 -4" /></svg>

                                      <span class="fw-medium mx-2 text-heading">  تاریخ پایان:</span> 
                                      <span class="badge bg-danger"><bdi>{{ verta($user->exp_vip)->format('Y/m/d H:i:s') }}</bdi></span>                                                
                                    </li>
                                    @endif
                                </ul>        
                              <div class="row">
                                
                                  <div class="col-12">
                                      <div class="row">
                                          <div class="col-12 mb-3">
                                              <select class="form-select mb-3" name="vip">
                                                  <option>عملیات اشتراک</option>
                                                  <option value="5" {{ $user->vip == 5 ? 'selected' : '' }} >اشتراک 5 روزه</option>
                                                  <option value="10" {{ $user->vip == 10 ? 'selected' : '' }} >اشتراک 10 روزه</option>
                                                  <option value="30" {{ $user->vip == 30 ? 'selected' : '' }} >اشتراک 30 روزه</option>
                                                  <option value="60" {{ $user->vip == 60 ? 'selected' : '' }} >اشتراک 60 روزه</option>
                                                  <option value="90" {{ $user->vip == 90 ? 'selected' : '' }} >اشتراک 90 روزه</option>
                                                  <option value="000" {{ $user->vip == 0 ? 'selected' : '' }} >لغو اشتراک (مرجوعی)</option>
                                              </select>
                                              <input type="number" name="addday" class="form-control mb-3" placeholder="افزودن روز به اشتراک (اختیاری)" />
                                          </div>
                                          <div class="col-12 mb-3">
                                              <button type="submit" class="btn btn-success d-block w-100">تایید</button>
                                          </div>
                                        
                                      </div>
                                  </div>
                              </div>
                          </form>
                    </div>


                    <div class="col-12 mt-3">
                        <small class="card-text text-uppercase">ساخت سشن برای مرورگر و اتصال به ال بانک</small>
                        @if (isset($SiteUser))
                        <form method="POST" action="{{ route('setRemoteBrowserToken', $SiteUser->id) }}">
                            @csrf
                                <ul class="list-unstyled mb-4 mt-3">

                                    <li class="d-flex align-items-center mb-3"> 
                                      <input type="text" name="remote_username" class="form-control mb-3" placeholder="نام کاربری" value="{{ $user->mobile }}" />
                                    </li>

                                    <li class="d-flex align-items-center mb-3"> 
                                      <input type="text" name="remote_password" class="form-control mb-3" placeholder="رمز عبور ریموت" value="{{ $user->mobile }}" />
                                    </li>

                                    <li class="d-flex align-items-center mb-3"> 
                                      <input type="number" min="30" max="43200" name="remote_ttl_minutes" class="form-control mb-3" placeholder="مدت اعتبار (دقیقه)" value="43200" />
                                    </li>

                                    <li class="d-flex align-items-center mb-3"> 
                                        <select name="remote_profile" class="form-select mb-3">
                                            <option value="lightweight">سبک (حداقل منابع)</option>
                                            <option value="balanced" selected>متعادل (پیشنهادی)</option>
                                            <option value="performance">قدرتی (برای فشار کاری بیشتر)</option>
                                        </select>
                                    </li>

                                    <li class="d-flex align-items-center mb-3"> 
                                        <select name="remote_status" class="form-select mb-3">
                                            <option>انتخاب کنید... (ست نشده)</option>
                                            <option value="1" {{ $SiteUser->lbankApi?->is_connected == 1 ? 'selected' : '' }}>فعال</option>
                                            <option value="0" {{ $SiteUser->lbankApi?->is_connected == 0 ? 'selected' : '' }}>غیرفعال</option>
                                        </select>
                                    </li>

                            
                                    <li class="d-flex align-items-center mb-3"> 
                                        <button type="submit" class="btn btn-primary d-block w-100">تنظیم سشن مرورگر</button>
                                    </li>

                                    <li class="d-flex align-items-center mb-3"> 
                                        <p class="alert alert-info col-12">در نظر داشته باشید رمز کاربر همان شماره موبایل میباشد.</p>
                                    </li>
                                </ul>
    
                        </form>
                        @else
                            <p class="alert alert-danger col-12">این کاربر هنوز در سایت عضو نشده. (در ربات بوده)</p>
                        @endif

                        @if (session('ok'))
                            <div class="col-12 mt-2">
                                <a href="{{ session('issued_urls') }}" target="_blank" class="alert alert-success col-12 d-block text-center">سشن مرورگر ساخته شد. برای مشاهده کلیک کنید.</a>
                            </div>
                        @endif
                    </div>
                  
                  
                </div>
                
              </div>
              <!--/ About User -->
              
            </div>
            <div class="col-xl-8 col-lg-7 col-md-7">
            
              <div class="row">
                
              
              </div>
              @if ($Bot == 'Bot2')
              <!-- Projects table -->
              <div class="card">
                        <h5 class="card-header">لاگ موجودی های کاربر</h5>
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
                                @php
                                    $x = 1;
                                @endphp

                                @foreach ($user->balanceLogs as $index => $log)

                                    @php
                                        $current = $log->balance;
                                        $prev = $user->balanceLogs[$index + 1]->balance ?? null;

                                        $trend = null; // up | down | equal

                                        if ($prev !== null) {
                                            $cmp = bccomp($current, $prev, 8);

                                            if ($cmp === 1)      $trend = 'up';
                                            elseif ($cmp === -1) $trend = 'down';
                                            else                 $trend = 'equal';
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $x++ }}</td>

                                        <td>
                                            ${{ $current }}

                                            @if ($trend === 'up')
                                                <span style="color:#28C76F">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-arrow-big-up-lines"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.586 3l-6.586 6.586a2 2 0 0 0 -.434 2.18l.068 .145a2 2 0 0 0 1.78 1.089h2.586v2a1 1 0 0 0 1 1h6l.117 -.007a1 1 0 0 0 .883 -.993l-.001 -2h2.587a2 2 0 0 0 1.414 -3.414l-6.586 -6.586a2 2 0 0 0 -2.828 0z" /><path d="M15 20a1 1 0 0 1 .117 1.993l-.117 .007h-6a1 1 0 0 1 -.117 -1.993l.117 -.007h6z" /><path d="M15 17a1 1 0 0 1 .117 1.993l-.117 .007h-6a1 1 0 0 1 -.117 -1.993l.117 -.007h6z" /></svg>
                                                </span>
                                            @elseif ($trend === 'down')
                                                <span style="color:rgb(250, 23, 23)">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-arrow-big-down-lines"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 8l-.117 .007a1 1 0 0 0 -.883 .993v1.999l-2.586 .001a2 2 0 0 0 -1.414 3.414l6.586 6.586a2 2 0 0 0 2.828 0l6.586 -6.586a2 2 0 0 0 .434 -2.18l-.068 -.145a2 2 0 0 0 -1.78 -1.089l-2.586 -.001v-1.999a1 1 0 0 0 -1 -1h-6z" /><path d="M15 2a1 1 0 0 1 .117 1.993l-.117 .007h-6a1 1 0 0 1 -.117 -1.993l.117 -.007h6z" /><path d="M15 5a1 1 0 0 1 .117 1.993l-.117 .007h-6a1 1 0 0 1 -.117 -1.993l.117 -.007h6z" /></svg>
                                                </span>
                                            @elseif ($trend === 'equal')
                                                <span style="color:#999">➖</span>
                                            @endif
                                        </td>

                                        <td>{{ verta($log->time)->format('Y/m/d H:i:s') }}</td>
                                    </tr> @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
              <!--/ Projects table -->
              @endif
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
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <script>
        $('.bots').addClass('active');
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
    </body>

</html>
