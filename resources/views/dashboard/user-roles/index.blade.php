<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>نقش‌های کاربری - روحی بات</title>
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
                                <h4 class="mb-1"><span class="text-muted fw-light">مدیریت کاربران /</span> نقش‌های
                                    کاربری</h4>
                                <p class="text-muted mb-0">نقش‌های داخلی فروش، پشتیبانی، سرپرستی و بازاریابی را تعریف
                                    کنید.</p>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">لطفا خطاهای فرم را بررسی کنید.</div>
                        @endif

                        <div class="row g-4">
                            <div class="col-12 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="mb-3">ایجاد نقش جدید</h6>
                                        <form method="POST" action="{{ route('user-roles.store') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">عنوان نقش</label>
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name') }}" placeholder="مثلا مدیر فروش" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">شناسه انگلیسی</label>
                                                <input type="text" name="slug"
                                                    class="form-control @error('slug') is-invalid @enderror"
                                                    value="{{ old('slug') }}" placeholder="sales-manager">
                                                @error('slug')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">توضیحات</label>
                                                <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">دسترسی‌های پایه</label>
                                                @foreach ($permissions as $permissionKey => $permissionLabel)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="permissions[]" value="{{ $permissionKey }}"
                                                            id="permission_create_{{ $loop->index }}"
                                                            {{ in_array($permissionKey, old('permissions', []), true) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="permission_create_{{ $loop->index }}">
                                                            {{ $permissionLabel }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="form-check form-switch mb-3">
                                                <input type="hidden" name="is_active" value="0">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                    value="1" id="role_is_active" checked>
                                                <label class="form-check-label" for="role_is_active">نقش فعال
                                                    باشد</label>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">ثبت نقش</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-xl-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="mb-3">لیست نقش‌ها</h6>
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>نقش</th>
                                                        <th>دسترسی‌ها</th>
                                                        <th>کاربران</th>
                                                        <th>وضعیت</th>
                                                        <th>عملیات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($roles as $role)
                                                        <tr>
                                                            <td>
                                                                <div class="fw-semibold">{{ $role->name }}</div>
                                                                <small class="text-muted">{{ $role->slug }}</small>
                                                                @if ($role->description)
                                                                    <div><small
                                                                            class="text-muted">{{ $role->description }}</small>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td style="min-width: 260px;">
                                                                @forelse (($role->permissions ?? []) as $permission)
                                                                    <span
                                                                        class="badge bg-label-primary mb-1">{{ $permissions[$permission] ?? $permission }}</span>
                                                                @empty
                                                                    <span class="text-muted">بدون دسترسی</span>
                                                                @endforelse
                                                            </td>
                                                            <td>{{ number_format((int) $role->users_count) }}</td>
                                                            <td>
                                                                @if ($role->is_active)
                                                                    <span class="badge bg-label-success">فعال</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-label-secondary">غیرفعال</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-sm btn-info" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#role-edit-{{ $role->id }}">ویرایش</button>
                                                            </td>
                                                        </tr>
                                                        <tr class="collapse" id="role-edit-{{ $role->id }}">
                                                            <td colspan="5">
                                                                <form method="POST"
                                                                    action="{{ route('user-roles.update', $role) }}"
                                                                    class="border rounded p-3">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="row g-3">
                                                                        <div class="col-12 col-md-4">
                                                                            <label class="form-label">عنوان</label>
                                                                            <input type="text" name="name"
                                                                                class="form-control"
                                                                                value="{{ $role->name }}" required>
                                                                        </div>
                                                                        <div class="col-12 col-md-4">
                                                                            <label class="form-label">شناسه</label>
                                                                            <input type="text" name="slug"
                                                                                class="form-control"
                                                                                value="{{ $role->slug }}" required>
                                                                        </div>
                                                                        <div class="col-12 col-md-4">
                                                                            <label class="form-label">توضیحات</label>
                                                                            <input type="text" name="description"
                                                                                class="form-control"
                                                                                value="{{ $role->description }}">
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="form-label">دسترسی‌ها</label>
                                                                            <div class="row g-2">
                                                                                @foreach ($permissions as $permissionKey => $permissionLabel)
                                                                                    <div class="col-12 col-md-6">
                                                                                        <div class="form-check">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="checkbox"
                                                                                                name="permissions[]"
                                                                                                value="{{ $permissionKey }}"
                                                                                                id="permission_{{ $role->id }}_{{ $loop->index }}"
                                                                                                {{ in_array($permissionKey, $role->permissions ?? [], true) ? 'checked' : '' }}>
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="permission_{{ $role->id }}_{{ $loop->index }}">
                                                                                                {{ $permissionLabel }}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-check form-switch">
                                                                                <input type="hidden" name="is_active"
                                                                                    value="0">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" name="is_active"
                                                                                    value="1"
                                                                                    id="role_active_{{ $role->id }}"
                                                                                    {{ $role->is_active ? 'checked' : '' }}>
                                                                                <label class="form-check-label"
                                                                                    for="role_active_{{ $role->id }}">فعال</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="btn btn-primary mt-3">ذخیره</button>
                                                                </form>
                                                                <form method="POST"
                                                                    action="{{ route('user-roles.destroy', $role) }}"
                                                                    class="mt-2"
                                                                    onsubmit="return confirm('این نقش حذف شود؟')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-label-danger">حذف</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                هنوز نقشی ثبت نشده است.</td>
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
        $('.user-roles').addClass('active').removeClass('open');
    </script>
</body>

</html>
