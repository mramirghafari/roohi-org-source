<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ env('DASHBOARD_THEME_PATH') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>کیف پول من - روحی بات</title>

    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#000c63">
    <link rel="apple-touch-icon" href="/icons/pwa-192.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Roohi AI">

    <!-- Favicon -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <!-- Icons -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/toastr/toastr.css" rel="stylesheet" />
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/animate-css/animate.css" rel="stylesheet" />
    <!-- Vendors CSS -->
    <style>
        .card-body.verify {
            margin-top: 50px;
        }

        @media(max-width: 768px) {
            .card-body.verify {
                padding-bottom: 170px;
                background-size: 170px !important;
                margin-top: 20px;
            }

        }
    </style>

    <!-- Helpers -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ env('DASHBOARD_THEME_PATH') }}/assets/css/rtl.css" rel="stylesheet" />
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
                        @if (session('success'))
                            <div class="alert alert-success mb-3">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">


                            <div class="col-md-4 col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="bg-label-primary rounded-3 text-center mb-3">
                                            <img class="img-fluid rounded"
                                                src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/wallet-banner.jpg">
                                        </div>
                                        <h4 class="mb-2 pb-1">کیف پول من</h4>
                                        <p class="small">موجودی کیف پول های من</p>
                                        <div class="row mb-3 g-3">
                                            <div class="col-12">
                                                <div class="d-flex">
                                                    <div class="avatar flex-shrink-0 me-2">
                                                        <span class="avatar-initial rounded bg-label-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-moneybag">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M9.5 3h5a1.5 1.5 0 0 1 1.5 1.5a3.5 3.5 0 0 1 -3.5 3.5h-1a3.5 3.5 0 0 1 -3.5 -3.5a1.5 1.5 0 0 1 1.5 -1.5" />
                                                                <path
                                                                    d="M4 17v-1a8 8 0 1 1 16 0v1a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-nowrap">
                                                            {{ number_format($wallet->toman_balance) }} تومان
                                                        </h6>
                                                        <small>موجودی کیف پول تومان</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex">
                                                    <div class="avatar flex-shrink-0 me-2">
                                                        <span class="avatar-initial rounded bg-label-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-brand-tether">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M14.08 20.188c-1.15 1.083 -3.02 1.083 -4.17 0l-6.93 -6.548c-.96 -.906 -1.27 -2.624 -.69 -3.831l2.4 -5.018c.47 -.991 1.72 -1.791 2.78 -1.791h9.06c1.06 0 2.31 .802 2.78 1.79l2.4 5.019c.58 1.207 .26 2.925 -.69 3.83c-3.453 3.293 -3.466 3.279 -6.94 6.549" />
                                                                <path d="M12 15v-7" />
                                                                <path d="M8 8h8" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-nowrap">
                                                            {{ number_format($wallet->usdt_balance) }} USDT
                                                        </h6>
                                                        <small>موجودی کیف پول تتر</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex">
                                                    <div class="avatar flex-shrink-0 me-2">
                                                        <span class="avatar-initial rounded bg-label-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="currentColor"
                                                                class="icon icon-tabler icons-tabler-filled icon-tabler-carambola">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path
                                                                    d="M17.108 22.085c-1.266 -.068 -2.924 -.859 -5.071 -2.355l-.04 -.027l-.037 .027c-2.147 1.497 -3.804 2.288 -5.072 2.356l-.178 .005c-2.747 0 -3.097 -2.64 -1.718 -7.244l.054 -.178l-.1 -.075c-6.056 -4.638 -5.046 -7.848 2.554 -8.066l.202 -.005l.115 -.326c1.184 -3.33 2.426 -5.085 4.027 -5.192l.156 -.005c1.674 0 2.957 1.76 4.182 5.197l.114 .326l.204 .005c7.6 .218 8.61 3.428 2.553 8.065l-.102 .075l.055 .178c1.35 4.512 1.04 7.137 -1.556 7.24l-.163 .003z" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-nowrap">
                                                            {{ number_format($wallet->stars_balance) }} STARS</h6>
                                                        <small>موجودی STARS</small>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="bg-label-primary rounded-3 text-center mb-3">
                                            <img class="img-fluid rounded"
                                                src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/swap-banner.jpg">
                                        </div>
                                        <h4 class="mb-2 pb-1">تبدیل STARS</h4>
                                        <p class="small">تبدیل امتیازات من به تومان یا تتر</p>
                                        <p class="small">در حال حاضر هر استارز 30 هزار تومان و هر 5 استارز یک تتر
                                            میباشد.</p>
                                        <form method="POST" action="{{ route('wallet.swap') }}">
                                            @csrf
                                            <div class="row mb-3 g-3">
                                                <div class="col-12">
                                                    <label for="stars_amount" class="form-label">تعداد STARS برای
                                                        تبدیل</label>
                                                    <input type="number" class="form-control" id="stars_amount"
                                                        name="stars_amount" min="1"
                                                        value="{{ old('stars_amount') }}"
                                                        placeholder="تعداد STARS را وارد کنید">
                                                </div>
                                                <div class="col-12">
                                                    <label for="convert_to" class="form-label">تبدیل به</label>
                                                    <select class="form-select" id="convert_to" name="convert_to">
                                                        <option value="toman"
                                                            {{ old('convert_to') === 'toman' ? 'selected' : '' }}>تومان
                                                        </option>
                                                        <option value="usdt"
                                                            {{ old('convert_to') === 'usdt' ? 'selected' : '' }}>تتر
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit"
                                                        class="btn btn-primary w-100">تبدیل</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="bg-label-primary rounded-3 text-center mb-3">
                                            <img class="img-fluid rounded"
                                                src="{{ env('DASHBOARD_THEME_PATH') }}/assets/img/deposit-withdraw-banner.jpg">
                                        </div>
                                        <h4 class="mb-2 pb-1">واریز و برداشت و انتقال</h4>
                                        <p class="small">عملیات واریز و برداشت و انتقال موجودی</p>

                                        <form method="POST" action="{{ route('wallet.operation') }}">
                                            @csrf
                                            <div class="row mb-3 g-3">
                                                <div class="col-12">
                                                    <label for="action_type" class="form-label">نوع عملیات</label>
                                                    <select class="form-select" id="operation_type"
                                                        name="operation_type">
                                                        <option value="deposit"
                                                            {{ old('operation_type') === 'deposit' ? 'selected' : '' }}>
                                                            واریز</option>
                                                        <option value="withdraw"
                                                            {{ old('operation_type') === 'withdraw' ? 'selected' : '' }}>
                                                            برداشت</option>
                                                        <option value="transfer"
                                                            {{ old('operation_type') === 'transfer' ? 'selected' : '' }}>
                                                            انتقال</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label for="operation_asset" class="form-label">نوع کیف
                                                        پول</label>
                                                    <select class="form-select" id="operation_asset" name="asset">
                                                        <option value="toman"
                                                            {{ old('asset') === 'toman' ? 'selected' : '' }}>تومان
                                                        </option>
                                                        <option value="usdt"
                                                            {{ old('asset') === 'usdt' ? 'selected' : '' }}>تتر
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label for="operation_amount" class="form-label">مبلغ</label>
                                                    <input type="number" step="0.00000001" min="0"
                                                        class="form-control" id="operation_amount" name="amount"
                                                        value="{{ old('amount') }}" placeholder="مبلغ را وارد کنید">
                                                </div>
                                                <div class="col-12 d-none" id="sheba_field">
                                                    <label for="sheba" class="form-label">شماره شبا</label>
                                                    <input type="text" class="form-control" id="sheba"
                                                        name="sheba" value="{{ old('sheba') }}"
                                                        placeholder="IRxxxxxxxxxxxxxxxxxxxxxxxx">
                                                </div>
                                                <div class="col-12 d-none" id="receiver_mobile_field">
                                                    <label for="receiver_mobile" class="form-label">شماره موبایل
                                                        مقصد</label>
                                                    <input type="text" class="form-control" id="receiver_mobile"
                                                        name="receiver_mobile" value="{{ old('receiver_mobile') }}"
                                                        placeholder="مثال: 09xxxxxxxxx">
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary w-100">ثبت
                                                        عملیات</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">لیست تراکنش‌های کیف پول</h5>
                                        <div class="table-responsive text-nowrap">
                                            <table class="table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>ردیف</th>
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
                                                        $typeLabels = [
                                                            'deposit' => 'واریز',
                                                            'withdraw' => 'برداشت',
                                                            'transfer_in' => 'انتقال ورودی',
                                                            'transfer_out' => 'انتقال خروجی',
                                                            'swap' => 'تبدیل',
                                                            'reward' => 'جایزه',
                                                            'adjustment' => 'اصلاحی',
                                                        ];

                                                        $assetLabels = [
                                                            'toman' => 'تومان',
                                                            'usdt' => 'تتر',
                                                            'stars' => 'STARS',
                                                        ];
                                                    @endphp

                                                    @forelse ($transactions as $index => $transaction)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $typeLabels[$transaction->type] ?? $transaction->type }}
                                                            </td>
                                                            <td>{{ $assetLabels[$transaction->asset] ?? $transaction->asset }}
                                                            </td>
                                                            <td>{{ $transaction->amount }}</td>
                                                            <td>{{ $transaction->balance_before }}</td>
                                                            <td>{{ $transaction->balance_after }}</td>
                                                            <td>{{ $transaction->description ?? '-' }}</td>
                                                            <td><bdi>{{ verta($transaction->created_at)->format('Y/m/d H:i:s') }}</bdi>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                تراکنشی ثبت نشده است</td>
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

    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/toastr/toastr.js"></script>
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/vendor/libs/toastr/toastr.js"></script>
    <!-- Main JS -->
    <script src="{{ env('DASHBOARD_THEME_PATH') }}/assets/js/main.js"></script>
    <!-- Page JS -->

    <script>
        (function() {
            const operationType = document.getElementById('operation_type');
            const shebaField = document.getElementById('sheba_field');
            const receiverMobileField = document.getElementById('receiver_mobile_field');

            if (!operationType || !shebaField || !receiverMobileField) {
                return;
            }

            const toggleFields = () => {
                const type = operationType.value;

                shebaField.classList.toggle('d-none', type !== 'withdraw');
                receiverMobileField.classList.toggle('d-none', type !== 'transfer');
            };

            operationType.addEventListener('change', toggleFields);
            toggleFields();
        })();
    </script>


    @include('dashboard.sections.installApp')
</body>

</html>
