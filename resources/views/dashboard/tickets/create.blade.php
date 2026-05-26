<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact"
    data-assets-path="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ثبت تیکت</title>
    <link rel="shortcut icon" href="{{ asset('/') }}/assets/images/favicon.ico" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/demo.css" rel="stylesheet" />
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/config.js"></script>
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/rtl.css" rel="stylesheet" />
</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            @include('dashboard.sections.navbar')
            <div class="layout-page">
                <div class="content-wrapper">
                    @include('dashboard.sections.aside')
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h4 class="mb-4">ثبت تیکت جدید</h4>
                            </div>
                            <div class="col-12 col-md-6 text-end"><a href="{{ route('tickets') }}"
                                    class="btn btn-label-secondary">بازگشت</a></div>
                        </div>



                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ route('tickets.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">موضوع</label>
                                        <input type="text" name="subject" class="form-control"
                                            value="{{ old('subject') }}" required>
                                        @error('subject')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-md-6 mb-3">
                                            <label class="form-label">بخش</label>
                                            <select name="department" class="form-select" required>
                                                <option value="">انتخاب کنید</option>
                                                @foreach ($departments as $key => $label)
                                                    <option value="{{ $key }}"
                                                        {{ old('department') == $key ? 'selected' : '' }}>
                                                        {{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('department')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 mb-3">
                                            <label class="form-label">اولویت</label>
                                            <select name="priority" class="form-select" required>
                                                <option value="">انتخاب کنید</option>
                                                @foreach ($priorities as $key => $label)
                                                    <option value="{{ $key }}"
                                                        {{ old('priority') == $key ? 'selected' : '' }}>
                                                        {{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('priority')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label">متن تیکت</label>
                                        <textarea name="message" rows="6" class="form-control" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">فایل ضمیمه (حداکثر 3 فایل - عکس، PDF، ZIP)</label>
                                        <input type="file" name="attachments[]" class="form-control"
                                            accept="image/*,.pdf,.zip" multiple>
                                        @error('attachments')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                        @error('attachments.*')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">ثبت تیکت</button>

                                </form>
                            </div>
                        </div>

                        @include('dashboard.sections.footer')
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/libs/jquery/jquery.js"></script>
        <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/bootstrap.js"></script>
        <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/main.js"></script>
        <script>
            $('.tickets').addClass('active');
        </script>
    </div>
</body>

</html>
