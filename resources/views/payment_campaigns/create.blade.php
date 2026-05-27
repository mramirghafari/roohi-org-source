<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>ایجاد کمپین پرداخت - روحی بات</title>
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
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><span class="text-muted fw-light">کمپین‌های پرداخت /</span> ایجاد کمپین
                            </h4>
                            <a href="{{ route('payment-campaigns.index') }}" class="btn btn-label-dark">بازگشت</a>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                لطفا خطاهای فرم را بررسی کنید.
                            </div>
                        @endif

                        <div class="row justify-content-center">
                            <div class="col-12 col-xl-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="mb-3">مشخصات کمپین</h6>
                                        <form method="POST" action="{{ route('payment-campaigns.store') }}">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">عنوان کمپین</label>
                                                    <input type="text"
                                                        class="form-control @error('title') is-invalid @enderror"
                                                        name="title" value="{{ old('title') }}" required>
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">توضیحات نمایش در صفحه پرداخت</label>
                                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label">ظرفیت واقعی</label>
                                                    <input type="number" min="0"
                                                        class="form-control @error('capacity') is-invalid @enderror"
                                                        name="capacity" value="{{ old('capacity') }}" required>
                                                    <small class="text-muted">برای محدودیت واقعی پرداخت. عدد 0 یعنی بدون
                                                        محدودیت.</small>
                                                    @error('capacity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label">ظرفیت نمایشی</label>
                                                    <input type="text" maxlength="80"
                                                        class="form-control @error('display_capacity') is-invalid @enderror"
                                                        name="display_capacity" value="{{ old('display_capacity') }}"
                                                        placeholder="مثلا: ظرفیت محدود ۳۰ نفر">
                                                    <small class="text-muted">فقط متن نمایشی صفحه پرداخت است.</small>
                                                    @error('display_capacity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label">قیمت اصلی (تومان)</label>
                                                    <input type="number" min="0"
                                                        class="form-control @error('original_price') is-invalid @enderror"
                                                        name="original_price" value="{{ old('original_price') }}"
                                                        required>
                                                    @error('original_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label">قیمت فعلی پرداخت (تومان)</label>
                                                    <input type="number" min="1000"
                                                        class="form-control @error('current_price') is-invalid @enderror"
                                                        name="current_price" value="{{ old('current_price') }}"
                                                        required>
                                                    @error('current_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label">متن دکمه پرداخت</label>
                                                    <input type="text" class="form-control" name="button_text"
                                                        value="{{ old('button_text', 'پرداخت و ثبت نام') }}">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label">پیام موفقیت</label>
                                                    <input type="text" class="form-control" name="success_message"
                                                        value="{{ old('success_message', 'ثبت نام شما با موفقیت انجام شد.') }}">
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="is_active" value="1" id="is_active"
                                                            {{ old('is_active', '1') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_active">کمپین فعال
                                                            باشد</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100 mt-4">ثبت
                                                کمپین</button>
                                        </form>
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
        $('.users').addClass('active open');
        $('.user-camps').addClass('active').removeClass('open');
    </script>
</body>

</html>
