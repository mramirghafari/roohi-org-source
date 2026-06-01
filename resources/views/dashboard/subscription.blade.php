<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    @php
        $rawCardNumber = (string) ($cardAccount['card_number'] ?? '');
        $cardDigits = preg_replace('/\D+/', '', $rawCardNumber);
        $formattedCardNumber = $cardDigits !== '' ? trim(chunk_split($cardDigits, 4, '-'), '-') : $rawCardNumber;
    @endphp
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" <button
        type="button" class="subscription-card-copy" data-copy-card="{{ $cardDigits ?: $rawCardNumber }}">
    <span class="d-flex justify-content-between align-items-center gap-2 mb-1">
        <span>شماره کارت مقصد</span>
        <span class="subscription-copy-hint">کپی</span>
    </span>
    <span class="subscription-card-number">{{ $formattedCardNumber ?: '-' }}</span>
    </button>
    <!-- Favicon -->
    <div class="mt-2">بانک:
        <!-- Icons -->
        <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
        <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
        <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.css" rel="stylesheet" />
        <link href="{{ asset('/assets/vendor/jalalidatepicker/jalalidatepicker.min.css') }}" rel="stylesheet" />
        <!-- Core CSS -->
        <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
        <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
        <link href="{{ asset('/dashboard_theme') }}/assets/css/demo.css" rel="stylesheet" />

        <!-- Helpers -->
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/helpers.js"></script>

        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="{{ asset('/dashboard_theme') }}/assets/js/config.js"></script>
        <!-- Better experience of RTL -->
        <link href="{{ asset('/dashboard_theme') }}/assets/css/rtl.css" rel="stylesheet" />
        <style>
            .subscription-card-copy {
                background: #eefbff;
                border: 1px solid rgba(0, 207, 232, .35);
                border-radius: .5rem;
                color: #098ea0;
                cursor: pointer;
                display: block;
                padding: .85rem 1rem;
                text-align: right;
                transition: .18s ease;
                width: 100%;
            }

            .subscription-card-copy:hover,
            .subscription-card-copy:focus {
                background: #d9f8ff;
                border-color: rgba(0, 207, 232, .65);
                box-shadow: 0 .25rem .75rem rgba(0, 207, 232, .12);
                outline: 0;
            }

            .subscription-card-number {
                direction: ltr;
                display: inline-block;
                font-size: 1rem;
                font-weight: 700;
                letter-spacing: .04em;
            }

            .subscription-copy-hint {
                color: #00a3b8;
                font-size: .78rem;
                font-weight: 600;
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
                        @if (session('payment_message'))
                            <div class="alert @if (session('payment_state') === 'success') alert-success @elseif(session('payment_state') === 'cancel') alert-warning @elseif(session('payment_state') === 'expired') alert-secondary @else alert-danger @endif alert-dismissible"
                                role="alert">
                                {{ session('payment_message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="card">
                            <!-- Pricing Plans -->
                            <div class="pb-sm-5 pb-2 rounded-top">
                                <div class="container py-5">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    @if (is_null($sub) || $status == 'none')
                                                        <div class="mb-2">

                                                            <h4 class="mb-1">شما تا به حال اشتراک نداشته اید.</h4>
                                                            <p>همین حالا اولین اشتراک خود را تهیه کنید.</p>
                                                        </div>
                                                    @endif
                                                    @if ($sub)
                                                        <div class="mb-2 pt-1">
                                                            <h4 class="mb-1">شروع اشتراک از
                                                                {{ verta($sub?->start_vip)->formatWord('l dS F') }}
                                                            </h4>
                                                            <p>پس از انقضای اشتراک برای شما اعلان ارسال می کنیم</p>
                                                        </div>
                                                        @if ($remainingPercent <= 20)
                                                            <div class="alert alert-warning" role="alert">

                                                                <h5 class="alert-heading mb-2">توجه!</h5>
                                                                <span> شما نیاز به تمدید اشتراک دارید</span>

                                                            </div>
                                                        @endif
                                                        @if ($sub)
                                                            <div class="plan-statistics">
                                                                <div class="d-flex justify-content-between">
                                                                    <h6 class="mb-1">روزهای باقی مانده</h6>
                                                                    <h6 class="mb-1">{{ intval($remainingDays) }} از
                                                                        {{ intval($totalDays) }} روز</h6>
                                                                </div>
                                                                <div class="progress mb-1" style="height: 10px">
                                                                    <div aria-valuemax="100" aria-valuemin="0"
                                                                        aria-valuenow="{{ $remainingPercent }}"
                                                                        class="progress-bar @if ($remainingPercent <= 20) bg-danger @elseif($remainingPercent <= 50) bg-warning @else bg-success @endif"
                                                                        style="width: {{ 100 - $remainingPercent . '%' }}"
                                                                        role="progressbar"></div>
                                                                </div>
                                                                <p>{{ $remainingDays }} روز باقی مانده تا اتمام اشتراک
                                                                </p>
                                                            </div>
                                                        @endif
                                                        <div class="mb-3 pt-1">
                                                            <h6 class="mb-1">
                                                                <span class="me-2">تاریخ اتمام اشتراک:
                                                                    {{ verta($sub?->end_vip)->formatWord('l dS F') }}</span>
                                                                {{-- <span class="badge bg-label-primary">تخفیف دار</span> --}}
                                                            </h6>
                                                            {{-- <p>طرح استاندارد برای مشاغل کوچک تا متوسط</p> --}}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="card rounded">
                                                <div
                                                    class="card-body @if ($hasDemoSub || $sub) bg-label-success @else bg-label-warning @endif rounded">
                                                    <h4>🎉 یک ماه اشتراک رایگان هدیه تیم روحی ترید!</h4>
                                                    @if ($hasDemoSub)
                                                        <div class="mb-2">

                                                            <h6 class="mb-1">شما اشتراک هدیه خود را دریافت کرده اید!
                                                            </h6>
                                                            <p>می توانید با خرید اشتراک جدید، اشتراک فعلی خود را تمدید
                                                                کنید.</p>
                                                        </div>
                                                    @else
                                                        <div class="mb-2 pt-1">
                                                            @if ($sub || $hasDemoSub)
                                                                <h6 class="mb-1 text-danger">هدیه اشتراک VIP شما فعال
                                                                    است
                                                                </h6>
                                                            @else
                                                                <h6 class="mb-1 text-danger">شما تا به حال اشتراکی
                                                                    نداشته
                                                                    اید.</h6>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    <div class="mb-2 pt-1">
                                                        <p>در صورت تمایل میتوانید از طرف تیم روحی ترید یک ماه اشتراک
                                                            رایگان VIP دریافت کنید. برای استفاده از این هدیه باید از
                                                            طریق لینک زیر در صرافی ال بانک ثبت نام کنید و با حداقل
                                                            100 دلار دیپازیت از بونوس ویژه ی تیم روحی ترید بهره مند
                                                            شوید و هم اشتراک VIP ربات هوش مصنوعی را دریافت کنید:
                                                        </p>
                                                        <a href="https://lbank.com/ref/ROOHI" target="_blank"
                                                            class="btn btn-primary d-grid w-100 mt-3">ثبت نام در ال
                                                            بانک</a>
                                                        <form action="{{ route('users.getDemoVIP') }}" method="POST"
                                                            class="mt-3">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="lbank_uid" class="form-label">شناسه ال
                                                                    بانک خود را وارد کنید:</label>
                                                                <input type="text"
                                                                    class="form-control @if ($errors->has('lbank_uid') || $errors->has('error')) is-invalid @endif"
                                                                    id="lbank_uid" name="lbank_uid"
                                                                    placeholder="شناسه ال بانک"
                                                                    value="{{ $hasDemoSub ? auth()->user()->lbank_uid : old('lbank_uid') }}"
                                                                    required {{ $hasDemoSub ? 'readonly' : '' }}>
                                                                @if ($errors->has('lbank_uid'))
                                                                    <span class="error text-danger d-inline-block mt-2">
                                                                        {{ $errors->first('lbank_uid') }}
                                                                    </span>
                                                                @elseif ($errors->has('error'))
                                                                    <span class="error text-danger d-inline-block mt-2">
                                                                        {{ $errors->first('error') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <button type="submit" class="btn btn-success d-grid w-100"
                                                                {{ $hasDemoSub ? 'disabled' : '' }}>{{ $hasDemoSub ? 'اشتراک VIP فعال شده است' : 'ثبت شناسه' }}</button>


                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <h2 class="text-center mb-2 mt-3 mt-md-4">پلن‌های خرید اشتراک</h2>
                                        <p class="text-center"> برای خرید پلن های VIP یک اشتراک مناسب با بودجه و خواسته
                                            های
                                            خود انتخاب کنید تا خدمات به شما ارائه شود.</p>

                                        @php
                                            $planImages = [
                                                asset('/dashboard_theme') .
                                                '/assets/img/illustrations/page-pricing-basic.png',
                                                asset('/dashboard_theme') .
                                                '/assets/img/illustrations/page-pricing-standard.png',
                                                asset('/dashboard_theme') .
                                                '/assets/img/illustrations/page-pricing-enterprise.png',
                                            ];
                                        @endphp
                                        <div class="row mx-0 gy-3 px-lg-5">
                                            @forelse ($subscriptionPlans as $index => $subscriptionPlan)
                                                @php
                                                    $subscriptionPlanModalId =
                                                        'subscription-plan-modal-' . $subscriptionPlan->id;
                                                    $cardAccounts = $subscriptionPlan->card_accounts ?: [];
                                                    if (empty($cardAccounts) && $subscriptionPlan->card_number) {
                                                        $cardAccounts = [
                                                            [
                                                                'card_number' => $subscriptionPlan->card_number,
                                                                'bank_name' => '',
                                                                'card_owner' => $subscriptionPlan->card_owner,
                                                            ],
                                                        ];
                                                    }
                                                    $displayPrice = $subscriptionPlan->gateway_enabled
                                                        ? $subscriptionPlan->gateway_price
                                                        : $subscriptionPlan->card_to_card_price;
                                                @endphp
                                                <div class="col-lg mb-md-0 mb-3 mt-0">
                                                    <div
                                                        class="card {{ $planType == $subscriptionPlan->duration_months ? 'border-primary' : '' }} border rounded shadow-none h-100">
                                                        <div class="card-body position-relative">
                                                            @if ($planType == $subscriptionPlan->duration_months)
                                                                <div class="position-absolute end-0 me-4 top-0 mt-4">
                                                                    <span class="badge bg-label-primary">فعال</span>
                                                                </div>
                                                            @endif
                                                            <div class="mb-3 pt-2 text-center">
                                                                @php
                                                                    $planIcon = $subscriptionPlan->icon_path
                                                                        ? asset(
                                                                            'storage/' . $subscriptionPlan->icon_path,
                                                                        )
                                                                        : $planImages[$index % count($planImages)];
                                                                @endphp
                                                                <img alt="{{ $subscriptionPlan->title }}"
                                                                    height="140" src="{{ $planIcon }}"
                                                                    style="object-fit: contain;" />
                                                            </div>
                                                            <h3 class="card-title text-center text-capitalize mb-1">
                                                                {{ $subscriptionPlan->title }}</h3>
                                                            <p class="text-center text-muted">
                                                                {{ $subscriptionPlan->description ?: 'اشتراک VIP ' . number_format((int) $subscriptionPlan->duration_months) . ' ماهه' }}
                                                            </p>
                                                            @if (!empty($subscriptionPlan->features))
                                                                <ul class="list-unstyled text-start mt-3 mb-4">
                                                                    @foreach ($subscriptionPlan->features as $feature)
                                                                        <li
                                                                            class="mb-2 d-flex align-items-center gap-2">
                                                                            <i class="ti ti-check text-success"></i>
                                                                            <span>{{ $feature }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif

                                                            @if ($displayPrice)
                                                                <div class="text-center mb-3">
                                                                    <div
                                                                        class="d-flex justify-content-center align-items-start">
                                                                        <h1 class="display-6 mb-0 text-primary">
                                                                            {{ number_format((int) $displayPrice) }}
                                                                        </h1>
                                                                        <sup
                                                                            class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">تومان</sup>
                                                                    </div>
                                                                    <small class="text-muted">قیمت اشتراک</small>
                                                                </div>
                                                            @endif

                                                            <button class="btn btn-primary d-grid w-100 mt-3"
                                                                type="button" data-bs-toggle="modal"
                                                                data-bs-target="#{{ $subscriptionPlanModalId }}">
                                                                {{ $planType == $subscriptionPlan->duration_months ? 'تمدید اشتراک' : 'ثبت اشتراک' }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="{{ $subscriptionPlanModalId }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div
                                                            class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <div>
                                                                        <h5 class="modal-title mb-1">
                                                                            {{ $subscriptionPlan->title }}</h5>
                                                                        <small class="text-muted">روش پرداخت اشتراک را
                                                                            انتخاب کنید.</small>
                                                                    </div>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row g-4 align-items-stretch">
                                                                        <div class="col-12 col-lg-7 order-lg-1">
                                                                            @if ($subscriptionPlan->gateway_enabled)
                                                                                <div
                                                                                    class="card border shadow-none mb-3">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                                                                            <div>
                                                                                                <h6 class="mb-1">خرید
                                                                                                    اشتراک از درگاه</h6>
                                                                                                <small
                                                                                                    class="text-muted">فعال‌سازی
                                                                                                    بعد از پرداخت موفق
                                                                                                    زرین‌پال انجام
                                                                                                    می‌شود.</small>
                                                                                            </div>
                                                                                            <span
                                                                                                class="badge bg-label-primary">درگاه</span>
                                                                                        </div>
                                                                                        <div
                                                                                            class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                                                                            <span
                                                                                                class="text-muted">مبلغ
                                                                                                پرداخت</span>
                                                                                            <strong>{{ number_format((int) $subscriptionPlan->gateway_price) }}
                                                                                                تومان</strong>
                                                                                        </div>
                                                                                        <form
                                                                                            action="{{ route('subscription.payment.request') }}"
                                                                                            method="POST">
                                                                                            @csrf
                                                                                            <input type="hidden"
                                                                                                name="plan"
                                                                                                value="{{ $subscriptionPlan->id }}">
                                                                                            <input type="hidden"
                                                                                                name="payment_method"
                                                                                                value="gateway">
                                                                                            <button
                                                                                                class="btn btn-primary d-grid w-100"
                                                                                                type="submit">پرداخت
                                                                                                با درگاه</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if ($subscriptionPlan->card_to_card_enabled)
                                                                                <div class="card border shadow-none">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                                                                            <div>
                                                                                                <h6 class="mb-1">کارت
                                                                                                    به کارت</h6>
                                                                                                <small
                                                                                                    class="text-muted">بعد
                                                                                                    از ثبت رسید، درخواست
                                                                                                    در صف بررسی مدیریت
                                                                                                    می‌ماند.</small>
                                                                                            </div>
                                                                                            <span
                                                                                                class="badge bg-label-info">بررسی
                                                                                                دستی</span>
                                                                                        </div>
                                                                                        <div
                                                                                            class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                                                                            <span
                                                                                                class="text-muted">مبلغ
                                                                                                پرداخت</span>
                                                                                            <strong>{{ number_format((int) $subscriptionPlan->card_to_card_price) }}
                                                                                                تومان</strong>
                                                                                        </div>
                                                                                        <div
                                                                                            class="alert alert-info py-2 mb-3">
                                                                                            @foreach ($cardAccounts as $cardAccount)
                                                                                                <div class="mb-2">
                                                                                                    <div>شماره کارت
                                                                                                        مقصد:
                                                                                                        {{ $cardAccount['card_number'] ?? '-' }}
                                                                                                    </div>
                                                                                                    @if (!empty($cardAccount['bank_name']))
                                                                                                        <div>بانک:
                                                                                                            {{ $cardAccount['bank_name'] }}
                                                                                                        </div>
                                                                                                    @endif
                                                                                                    @if (!empty($cardAccount['card_owner']))
                                                                                                        <div>به نام:
                                                                                                            {{ $cardAccount['card_owner'] }}
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </div>
                                                                                            @endforeach
                                                                                        </div>
                                                                                        <form
                                                                                            action="{{ route('subscription.payment.request') }}"
                                                                                            method="POST"
                                                                                            enctype="multipart/form-data">
                                                                                            @csrf
                                                                                            <input type="hidden"
                                                                                                name="plan"
                                                                                                value="{{ $subscriptionPlan->id }}">
                                                                                            <input type="hidden"
                                                                                                name="payment_method"
                                                                                                value="card_to_card">
                                                                                            <div class="mb-3">
                                                                                                <label
                                                                                                    class="form-label">تصویر
                                                                                                    رسید</label>
                                                                                                <input type="file"
                                                                                                    name="receipt"
                                                                                                    class="form-control @error('receipt') is-invalid @enderror"
                                                                                                    accept="image/*,application/pdf"
                                                                                                    {{ $subscriptionPlan->receipt_required ? 'required' : '' }}>
                                                                                                @error('receipt')
                                                                                                    <div
                                                                                                        class="invalid-feedback">
                                                                                                        {{ $message }}
                                                                                                    </div>
                                                                                                @enderror
                                                                                            </div>
                                                                                            <div class="mb-3">
                                                                                                <label
                                                                                                    class="form-label">شماره
                                                                                                    کارت
                                                                                                    واریزکننده</label>
                                                                                                <input type="text"
                                                                                                    name="payer_card_number"
                                                                                                    class="form-control @error('payer_card_number') is-invalid @enderror"
                                                                                                    value="{{ old('payer_card_number') }}"
                                                                                                    {{ $subscriptionPlan->payer_card_required ? 'required' : '' }}>
                                                                                                @error('payer_card_number')
                                                                                                    <div
                                                                                                        class="invalid-feedback">
                                                                                                        {{ $message }}
                                                                                                    </div>
                                                                                                @enderror
                                                                                            </div>
                                                                                            <div class="mb-3">
                                                                                                <label
                                                                                                    class="form-label">تاریخ
                                                                                                    و ساعت
                                                                                                    پرداخت</label>
                                                                                                <input type="text"
                                                                                                    name="manual_paid_at"
                                                                                                    data-jdp
                                                                                                    class="form-control @error('manual_paid_at') is-invalid @enderror"
                                                                                                    value="{{ old('manual_paid_at') }}"
                                                                                                    autocomplete="off"
                                                                                                    placeholder="مثلا ۱۴۰۵/۰۳/۰۹ ۱۴:۳۰"
                                                                                                    {{ $subscriptionPlan->paid_at_required ? 'required' : '' }}>
                                                                                                @error('manual_paid_at')
                                                                                                    <div
                                                                                                        class="invalid-feedback">
                                                                                                        {{ $message }}
                                                                                                    </div>
                                                                                                @enderror
                                                                                            </div>
                                                                                            <button
                                                                                                class="btn btn-label-primary d-grid w-100"
                                                                                                type="submit">ثبت
                                                                                                درخواست کارت به
                                                                                                کارت</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-12 col-lg-5 order-lg-2">
                                                                            <div
                                                                                class="h-100 rounded border bg-label-primary p-4 d-flex flex-column justify-content-center align-items-center text-center">
                                                                                <svg width="260" height="220"
                                                                                    viewBox="0 0 260 220"
                                                                                    fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    role="img"
                                                                                    aria-label="payment illustration">
                                                                                    <rect x="42" y="52" width="176"
                                                                                        height="116" rx="22"
                                                                                        fill="#fff" />
                                                                                    <rect x="58" y="76" width="144"
                                                                                        height="24" rx="12"
                                                                                        fill="#7367f0"
                                                                                        opacity="0.18" />
                                                                                    <rect x="58" y="116" width="62"
                                                                                        height="14" rx="7"
                                                                                        fill="#7367f0"
                                                                                        opacity="0.35" />
                                                                                    <rect x="132" y="116"
                                                                                        width="70" height="14"
                                                                                        rx="7" fill="#7367f0"
                                                                                        opacity="0.18" />
                                                                                    <circle cx="82"
                                                                                        cy="151" r="18"
                                                                                        fill="#28c76f"
                                                                                        opacity="0.18" />
                                                                                    <path d="M73 151.5l6.2 6.2L92 144"
                                                                                        stroke="#28c76f"
                                                                                        stroke-width="6"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                    <circle cx="201"
                                                                                        cy="45" r="24"
                                                                                        fill="#ff9f43"
                                                                                        opacity="0.22" />
                                                                                    <path d="M201 34v22M192 45h18"
                                                                                        stroke="#ff9f43"
                                                                                        stroke-width="6"
                                                                                        stroke-linecap="round" />
                                                                                    <circle cx="48"
                                                                                        cy="170" r="18"
                                                                                        fill="#00cfe8"
                                                                                        opacity="0.2" />
                                                                                    <path d="M42 169h12M48 163v12"
                                                                                        stroke="#00cfe8"
                                                                                        stroke-width="5"
                                                                                        stroke-linecap="round" />
                                                                                    <path
                                                                                        d="M75 52c9-20 27-31 55-31s46 11 55 31"
                                                                                        stroke="#7367f0"
                                                                                        stroke-width="10"
                                                                                        stroke-linecap="round"
                                                                                        opacity="0.16" />
                                                                                    <path
                                                                                        d="M95 168c7 16 20 25 35 25s28-9 35-25"
                                                                                        stroke="#7367f0"
                                                                                        stroke-width="10"
                                                                                        stroke-linecap="round"
                                                                                        opacity="0.16" />
                                                                                </svg>
                                                                                <h5 class="mt-3 mb-2">پرداخت امن اشتراک
                                                                                </h5>
                                                                                <p class="text-muted mb-0">پرداخت درگاه
                                                                                    بلافاصله فعال می‌شود؛ کارت به کارت
                                                                                    بعد از بررسی مدیریت.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="alert alert-warning text-center mb-0">فعلا هیچ اشتراک
                                                        فعالی برای خرید تعریف نشده است.</div>
                                                </div>
                                            @endforelse
                                        </div>
                                        <div class="card mb-4 mt-5">
                                            <h5 class="card-header">تاریخچه خرید اشتراک</h5>
                                            <div class="table-responsive">
                                                <table class="table border-top">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-truncate">نوع عملیات</th>
                                                            <th class="text-truncate">روش پرداخت</th>
                                                            <th class="text-truncate">وضعیت</th>
                                                            <th class="text-truncate">قیمت (تومان)</th>
                                                            <th class="text-truncate">کد پیگیری</th>
                                                            <th class="text-truncate">تاریخ خرید</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-border-bottom-0">
                                                        @forelse ($subscriptionTransactions as $tx)
                                                            <tr>
                                                                <td class="text-truncate">
                                                                    @if (!empty($tx->subscriptionPlan?->title))
                                                                        <i
                                                                            class="ti ti-crown text-info me-2 ti-sm"></i>
                                                                        <span
                                                                            class="fw-medium">{{ $tx->subscriptionPlan->title }}</span>
                                                                    @elseif ($tx->plan_months == 1)
                                                                        <i
                                                                            class="ti ti-circle-number-1 text-info me-2 ti-sm"></i>
                                                                        <span class="fw-medium">اشتراک یک ماهه</span>
                                                                    @elseif($tx->plan_months == 2)
                                                                        <i
                                                                            class="ti ti-circle-number-2 text-info me-2 ti-sm"></i>
                                                                        <span class="fw-medium">اشتراک دو ماهه</span>
                                                                    @elseif($tx->plan_months == 3)
                                                                        <i
                                                                            class="ti ti-circle-number-3 text-info me-2 ti-sm"></i>
                                                                        <span class="fw-medium">اشتراک سه ماهه</span>
                                                                    @else
                                                                        <span
                                                                            class="fw-medium">{{ $tx->message ?? 'ثبت دستی اشتراک' }}</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-truncate">
                                                                    @if (($tx->payment_method ?? 'gateway') === 'card_to_card')
                                                                        <span class="btn btn-label-primary">کارت به
                                                                            کارت</span>
                                                                    @else
                                                                        <span class="btn btn-label-info">درگاه</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-truncate">
                                                                    @if ($tx->status === 'success')
                                                                        <span class="btn btn-label-success">موفق</span>
                                                                    @elseif($tx->status === 'cancel')
                                                                        <span class="btn btn-label-warning">کنسل</span>
                                                                    @elseif($tx->status === 'expired')
                                                                        <span
                                                                            class="btn btn-label-secondary">منقضی</span>
                                                                    @elseif($tx->status === 'pending')
                                                                        <span class="btn btn-label-info">در
                                                                            انتظار</span>
                                                                    @else
                                                                        <span class="btn btn-label-danger">خطا</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-truncate">
                                                                    {{ number_format((int) $tx->amount) }}
                                                                </td>
                                                                <td class="text-truncate">{{ $tx->ref_id ?? '-' }}
                                                                </td>
                                                                <td class="text-truncate">
                                                                    {{ verta($tx->created_at)->format('H:i - d F Y') }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6"
                                                                    class="text-center text-muted py-4">هنوز تراکنشی
                                                                    ثبت نشده است.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Pricing Plans -->
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
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/jquery/jquery.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/popper/popper.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/bootstrap.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
        <!-- endbuild -->
        <!-- Vendors JS -->
        <!-- Main JS -->
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
        <script src="{{ asset('/assets/vendor/jalalidatepicker/jalalidatepicker.min.js') }}"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
        <!-- Page JS -->
        <script src="{{ asset('/dashboard_theme') }}/assets//js/pages-pricing.js"></script>
        <script>
            $('.subscription').addClass('active');

            if (window.jalaliDatepicker) {
                jalaliDatepicker.startWatch({
                    selector: 'input[data-jdp]',
                    time: true,
                    hasSecond: false,
                    persianDigits: true,
                    autoHide: true,
                    zIndex: 2000
                });
            }

            document.addEventListener('click', function(event) {
                const copyButton = event.target.closest('[data-copy-card]');

                if (!copyButton) {
                    return;
                }

                const cardNumber = copyButton.getAttribute('data-copy-card');
                const hint = copyButton.querySelector('.subscription-copy-hint');

                if (!cardNumber) {
                    return;
                }

                navigator.clipboard.writeText(cardNumber).then(function() {
                    if (!hint) {
                        return;
                    }

                    const previousText = hint.textContent;
                    hint.textContent = 'کپی شد';

                    setTimeout(function() {
                        hint.textContent = previousText;
                    }, 1400);
                });
            });
        </script>
        @if (session('demo_vip_success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'تبریک!',
                        text: 'اشتراک VIP یک ماهه برای شما فعال شد!',
                        confirmButtonText: 'باشه',
                        showCancelButton: false,
                        showDenyButton: false,
                        showCloseButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: (popup) => {
                            popup.querySelector('.swal2-cancel')?.remove();
                            popup.querySelector('.swal2-deny')?.remove();
                            popup.querySelector('.swal2-close')?.remove();
                        }
                    });
                });
            </script>
        @endif

</body>

</html>
