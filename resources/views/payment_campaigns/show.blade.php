<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>جزئیات کمپین پرداخت - روحی بات</title>
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
                                <h4 class="mb-1"><span class="text-muted fw-light">کمپین‌های پرداخت /</span>
                                    {{ $campaign->title }}</h4>
                                <p class="text-muted mb-0">{{ $campaign->description ?: 'بدون توضیحات' }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('payment-campaigns.registrations.export', $campaign) }}"
                                    class="btn btn-label-success">
                                    <i class="ti ti-file-spreadsheet me-1"></i> خروجی اکسل
                                </a>
                                <a href="{{ route('payment-campaigns.register.form', $campaign) }}"
                                    class="btn btn-primary" target="_blank">
                                    <i class="ti ti-external-link me-1"></i> صفحه پرداخت
                                </a>
                                <a href="{{ route('payment-campaigns.index') }}" class="btn btn-label-dark">بازگشت</a>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="row g-4 mb-4">
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">ثبت‌نام موفق</span>
                                        <h3 class="mb-0 mt-2">{{ number_format((int) $stats['success']) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">در انتظار پرداخت</span>
                                        <h3 class="mb-0 mt-2">{{ number_format((int) $stats['pending']) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">ظرفیت باقی‌مانده</span>
                                        <h3 class="mb-0 mt-2">
                                            {{ $campaign->capacity ? number_format((int) max(0, $campaign->capacity - $stats['success'])) : 'نامحدود' }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="text-muted">مبلغ پرداخت</span>
                                        <h3 class="mb-0 mt-2">{{ number_format((int) $campaign->current_price) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-12 col-xl-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="mb-3">اطلاعات کمپین</h6>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">عنوان</small>
                                            <div class="fw-semibold">{{ $campaign->title }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">قیمت اصلی</small>
                                            <div>{{ number_format((int) $campaign->original_price) }} تومان</div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">قیمت فعلی</small>
                                            <div>{{ number_format((int) $campaign->current_price) }} تومان</div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">ظرفیت</small>
                                            <div>
                                                {{ $campaign->capacity ? number_format((int) $campaign->capacity) : 'نامحدود' }}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">وضعیت</small>
                                            @if ($campaign->is_active)
                                                <span class="badge bg-label-success">فعال</span>
                                            @else
                                                <span class="badge bg-label-secondary">غیرفعال</span>
                                            @endif
                                            @if ($campaign->is_full)
                                                <span class="badge bg-label-danger">تکمیل ظرفیت</span>
                                            @endif
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">لینک پرداخت</small>
                                            <input class="form-control" dir="ltr" readonly
                                                value="{{ route('payment-campaigns.register.form', $campaign) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-xl-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                            <h6 class="mb-0">لیست ثبت‌نامی‌های کمپین</h6>
                                            <a href="{{ route('payment-campaigns.registrations.export', $campaign) }}"
                                                class="btn btn-sm btn-label-success">
                                                <i class="ti ti-download me-1"></i> خروجی کامل
                                            </a>
                                        </div>
                                        <div class="table-responsive text-nowrap">
                                            <table class="table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>ثبت‌نام‌کننده</th>
                                                        <th>وضعیت پرداخت</th>
                                                        <th>تاریخ</th>
                                                        <th>نمایش</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($campaign->registrations as $index => $registration)
                                                        @php
                                                            $statusLabel = match ($registration->status) {
                                                                \App\Models\PaymentRegistration::STATUS_SUCCESS
                                                                    => 'موفق',
                                                                \App\Models\PaymentRegistration::STATUS_PENDING
                                                                    => 'در انتظار',
                                                                \App\Models\PaymentRegistration::STATUS_CANCEL
                                                                    => 'لغو شده',
                                                                \App\Models\PaymentRegistration::STATUS_EXPIRED
                                                                    => 'منقضی',
                                                                default => 'ناموفق',
                                                            };
                                                            $statusClass = match ($registration->status) {
                                                                \App\Models\PaymentRegistration::STATUS_SUCCESS
                                                                    => 'bg-label-success',
                                                                \App\Models\PaymentRegistration::STATUS_PENDING
                                                                    => 'bg-label-warning',
                                                                \App\Models\PaymentRegistration::STATUS_CANCEL,
                                                                \App\Models\PaymentRegistration::STATUS_EXPIRED
                                                                    => 'bg-label-secondary',
                                                                default => 'bg-label-danger',
                                                            };
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <div class="fw-semibold">{{ $registration->full_name }}
                                                                </div>
                                                                <small
                                                                    class="text-muted"><bdi>{{ $registration->mobile }}</bdi></small>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                                            </td>
                                                            <td>
                                                                <small>{{ verta($registration->created_at)->format('Y/m/d H:i') }}</small>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-label-primary payment-details-button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#paymentDetailsModal"
                                                                    data-full-name="{{ $registration->full_name }}"
                                                                    data-mobile="{{ $registration->mobile }}"
                                                                    data-amount="{{ number_format((int) $registration->amount) }} تومان"
                                                                    data-currency="{{ $registration->currency }}"
                                                                    data-status="{{ $statusLabel }}"
                                                                    data-status-class="{{ $statusClass }}"
                                                                    data-tracking-code="{{ $registration->tracking_code }}"
                                                                    data-authority="{{ $registration->authority ?: '-' }}"
                                                                    data-ref-id="{{ $registration->ref_id ?: '-' }}"
                                                                    data-card-pan="{{ $registration->card_pan ?: '-' }}"
                                                                    data-gateway-status="{{ $registration->gateway_status ?: '-' }}"
                                                                    data-gateway-code="{{ $registration->gateway_code ?? '-' }}"
                                                                    data-message="{{ $registration->message ?: '-' }}"
                                                                    data-paid-at="{{ $registration->paid_at ? verta($registration->paid_at)->format('Y/m/d H:i') : '-' }}"
                                                                    data-expires-at="{{ $registration->expires_at ? verta($registration->expires_at)->format('Y/m/d H:i') : '-' }}"
                                                                    data-created-at="{{ $registration->created_at ? verta($registration->created_at)->format('Y/m/d H:i') : '-' }}"
                                                                    data-updated-at="{{ $registration->updated_at ? verta($registration->updated_at)->format('Y/m/d H:i') : '-' }}">
                                                                    جزئیات
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                هنوز ثبت‌نامی برای این کمپین وجود ندارد.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">جزئیات واریزی</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-4">
                                            <div>
                                                <small class="text-muted d-block">ثبت‌نام‌کننده</small>
                                                <div class="fw-semibold" id="paymentDetailFullName">-</div>
                                                <small class="text-muted"><bdi
                                                        id="paymentDetailMobile">-</bdi></small>
                                            </div>
                                            <div class="text-md-end">
                                                <small class="text-muted d-block">وضعیت پرداخت</small>
                                                <span class="badge" id="paymentDetailStatus">-</span>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12 col-md-4">
                                                <small class="text-muted d-block">مبلغ</small>
                                                <div class="fw-semibold" id="paymentDetailAmount">-</div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <small class="text-muted d-block">واحد پول</small>
                                                <div id="paymentDetailCurrency">-</div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <small class="text-muted d-block">کد پیگیری</small>
                                                <bdi id="paymentDetailTrackingCode">-</bdi>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <small class="text-muted d-block">Authority</small>
                                                <bdi id="paymentDetailAuthority">-</bdi>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <small class="text-muted d-block">Ref ID</small>
                                                <bdi id="paymentDetailRefId">-</bdi>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <small class="text-muted d-block">شماره کارت</small>
                                                <bdi id="paymentDetailCardPan">-</bdi>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <small class="text-muted d-block">وضعیت درگاه</small>
                                                <div id="paymentDetailGatewayStatus">-</div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <small class="text-muted d-block">کد درگاه</small>
                                                <div id="paymentDetailGatewayCode">-</div>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block">پیام</small>
                                                <div id="paymentDetailMessage">-</div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <small class="text-muted d-block">زمان پرداخت</small>
                                                <div id="paymentDetailPaidAt">-</div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <small class="text-muted d-block">زمان انقضا</small>
                                                <div id="paymentDetailExpiresAt">-</div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <small class="text-muted d-block">زمان ثبت</small>
                                                <div id="paymentDetailCreatedAt">-</div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <small class="text-muted d-block">آخرین بروزرسانی</small>
                                                <div id="paymentDetailUpdatedAt">-</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-dark"
                                            data-bs-dismiss="modal">بستن</button>
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

        document.querySelectorAll('.payment-details-button').forEach(function(button) {
            button.addEventListener('click', function() {
                const fields = {
                    paymentDetailFullName: 'fullName',
                    paymentDetailMobile: 'mobile',
                    paymentDetailAmount: 'amount',
                    paymentDetailCurrency: 'currency',
                    paymentDetailTrackingCode: 'trackingCode',
                    paymentDetailAuthority: 'authority',
                    paymentDetailRefId: 'refId',
                    paymentDetailCardPan: 'cardPan',
                    paymentDetailGatewayStatus: 'gatewayStatus',
                    paymentDetailGatewayCode: 'gatewayCode',
                    paymentDetailMessage: 'message',
                    paymentDetailPaidAt: 'paidAt',
                    paymentDetailExpiresAt: 'expiresAt',
                    paymentDetailCreatedAt: 'createdAt',
                    paymentDetailUpdatedAt: 'updatedAt',
                };

                Object.entries(fields).forEach(function([elementId, dataKey]) {
                    document.getElementById(elementId).textContent = button.dataset[dataKey] || '-';
                });

                const status = document.getElementById('paymentDetailStatus');
                status.textContent = button.dataset.status || '-';
                status.className = 'badge ' + (button.dataset.statusClass || 'bg-label-secondary');
            });
        });
    </script>
</body>

</html>
