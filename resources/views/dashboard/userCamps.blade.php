<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>کاربران کمپین 4900 - روحی بات</title>
    <meta content="" name="description" />
    <link href="{{ asset('/dashboard_theme') }}/assets/img/favicon/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/fontawesome.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/fonts/tabler-icons.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/css/demo.css" rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css"
        rel="stylesheet" />
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css"
        rel="stylesheet" />
    <link href="{{ asset('/assets/vendor/jalalidatepicker/jalalidatepicker.min.css') }}" rel="stylesheet" />
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
                        <h4 class="py-3 mb-4">
                            <span class="text-muted fw-light">کاربران</span>
                            <span> / </span>
                            <span>کاربران کمپین 4900</span>
                        </h4>

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">لیست ثبت نامی های کمپین 4900</h5>
                                <small class="text-muted">{{ number_format($registrations->count()) }} نفر</small>
                            </div>
                            <div class="card-body border-top border-bottom">
                                <div class="row g-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label" for="filter_name">فیلتر نام کاربر</label>
                                        <input type="text" id="filter_name" class="form-control"
                                            placeholder="جستجو بر اساس نام">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label" for="filter_mobile">فیلتر موبایل</label>
                                        <input type="text" id="filter_mobile" class="form-control"
                                            placeholder="مثال: 0912...">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label" for="filter_paid_from">از تاریخ پرداخت</label>
                                        <input type="text" id="filter_paid_from" class="form-control" data-jdp
                                            data-jdp-only-date data-jdp-auto-read-only="true" placeholder="1405/01/01">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label" for="filter_paid_to">تا تاریخ پرداخت</label>
                                        <input type="text" id="filter_paid_to" class="form-control" data-jdp
                                            data-jdp-only-date data-jdp-auto-read-only="true" placeholder="1405/01/31">
                                    </div>
                                    <div class="col-12 col-md-2 d-flex align-items-end">
                                        <button type="button" id="reset_filters"
                                            class="btn btn-label-secondary w-100">حذف فیلترها</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-datatable table-responsive pt-0">
                                <table class="table datatables-direct-basic">
                                    <thead>
                                        <tr>
                                            <th>ردیف</th>
                                            <th>نام کاربر</th>
                                            <th>موبایل</th>
                                            <th>تاریخ پرداخت</th>
                                            <th>مبلغ پرداخت</th>
                                            <th>کد پیگیری زرین پال</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($registrations as $index => $registration)
                                            @php($paidJalaliDate = $registration->paid_at ? verta($registration->paid_at)->format('Y/m/d') : '')
                                            <tr data-paid-jalali="{{ $paidJalaliDate }}">
                                                <td><bdi>{{ $index + 1 }}</bdi></td>
                                                <td>{{ $registration->full_name }}</td>
                                                <td><bdi>{{ $registration->mobile }}</bdi></td>
                                                <td
                                                    data-order="{{ optional($registration->paid_at)->format('Y-m-d H:i:s') }}">
                                                    @if ($registration->paid_at)
                                                        <small>{{ verta($registration->paid_at)->format('Y/m/d H:i') }}</small>
                                                    @else
                                                        <small>-</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-label-success">
                                                        <bdi>{{ number_format((int) $registration->amount) }}</bdi>
                                                        تومان
                                                    </span>
                                                </td>
                                                <td>
                                                    @if (!empty($registration->ref_id))
                                                        <span
                                                            class="badge bg-label-info"><bdi>{{ $registration->ref_id }}</bdi></span>
                                                    @else
                                                        <small>-</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    هنوز ثبت نام پرداخت شده ای برای کمپین 4900 وجود ندارد.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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

        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/jquery/jquery.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/popper/popper.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/bootstrap.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/datatables-bs5/i18n/fa.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
        <script src="{{ asset('/assets/vendor/jalalidatepicker/jalalidatepicker.min.js') }}"></script>
        <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>

        <script>
            $('.users').addClass('active open');
            $('.user-camps').addClass('active');

            $(function() {
                jalaliDatepicker.startWatch();

                var table = $('.datatables-direct-basic');
                if (table.length) {
                    var dt = table.DataTable({
                        searching: true,
                        lengthChange: true,
                        ordering: true,
                        pageLength: 100,
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'excelHtml5',
                            text: 'خروجی اکسل',
                            className: 'btn btn-label-success btn-sm',
                            title: 'کاربران کمپین 4900',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5]
                            }
                        }],
                        order: [
                            [3, 'desc']
                        ]
                    });

                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        if (settings.nTable !== table.get(0)) {
                            return true;
                        }

                        var nameFilter = ($('#filter_name').val() || '').toLowerCase().trim();
                        var mobileFilter = ($('#filter_mobile').val() || '').replace(/\D/g, '');
                        var paidFromFilter = ($('#filter_paid_from').val() || '').trim();
                        var paidToFilter = ($('#filter_paid_to').val() || '').trim();
                        var nameValue = (data[1] || '').toLowerCase();
                        var mobileValue = (data[2] || '').replace(/\D/g, '');
                        var rowNode = settings.aoData[dataIndex].nTr;
                        var paidJalaliValue = rowNode ? (rowNode.getAttribute('data-paid-jalali') || '') : '';

                        if (nameFilter && nameValue.indexOf(nameFilter) === -1) {
                            return false;
                        }

                        if (mobileFilter && mobileValue.indexOf(mobileFilter) === -1) {
                            return false;
                        }

                        if (paidFromFilter && (!paidJalaliValue || paidJalaliValue < paidFromFilter)) {
                            return false;
                        }

                        if (paidToFilter && (!paidJalaliValue || paidJalaliValue > paidToFilter)) {
                            return false;
                        }

                        return true;
                    });

                    $('#filter_name, #filter_mobile, #filter_paid_from, #filter_paid_to').on('input change',
                        function() {
                            dt.draw();
                        });

                    $('#reset_filters').on('click', function() {
                        $('#filter_name').val('');
                        $('#filter_mobile').val('');
                        $('#filter_paid_from').val('');
                        $('#filter_paid_to').val('');
                        dt.search('');
                        dt.draw();
                    });
                }
            });
        </script>
    </div>
</body>

</html>
