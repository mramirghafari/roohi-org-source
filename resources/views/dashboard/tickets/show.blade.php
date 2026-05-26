<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact"
    data-assets-path="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>نمایش تیکت #{{ $ticket->id }}</title>
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/core.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/css/rtl/theme-default.css" rel="stylesheet" />
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/demo.css" rel="stylesheet" />
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/config.js"></script>
    <link href="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/css/rtl.css" rel="stylesheet" />
    <style>
        .ticket-message-row {
            display: flex;
            margin-bottom: 12px;
            width: 100%;
            direction: ltr;
        }

        .ticket-message-row.mine {
            justify-content: flex-end;
        }

        .ticket-message-row.other {
            justify-content: flex-start;
        }

        .ticket-bubble {
            position: relative;
            max-width: 80%;
            padding: 12px;
            border-radius: 14px;
            border: 1px solid #e7e7f1;
            direction: rtl;
            text-align: right;
        }

        .ticket-bubble.mine {
            background: #eef1ff;
            border-top-right-radius: 4px;
        }

        .ticket-bubble.mine::after {
            content: '';
            position: absolute;
            top: 10px;
            right: -8px;
            border-width: 8px 0 8px 8px;
            border-style: solid;
            border-color: transparent transparent transparent #eef1ff;
        }

        .ticket-bubble.other {
            background: #f8f8fb;
            border-top-left-radius: 4px;
        }

        .ticket-bubble.other::after {
            content: '';
            position: absolute;
            top: 10px;
            left: -8px;
            border-width: 8px 8px 8px 0;
            border-style: solid;
            border-color: transparent #f8f8fb transparent transparent;
        }

        .ticket-message-text {
            white-space: pre-wrap;
            word-break: break-word;
            line-height: 1.9;
        }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            @include('dashboard.sections.navbar')
            <div class="layout-page">
                <div class="content-wrapper">
                    @include('dashboard.sections.aside')
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @php
                            $statusLabels = [
                                'open' => 'در انتظار',
                                'answered_by_user' => 'پاسخ کاربر',
                                'answered_by_support' => 'پاسخ پشتیبانی',
                                'closed' => 'بسته',
                            ];
                            $statusBadgeClasses = [
                                'open' => 'badge bg-label-warning',
                                'answered_by_user' => 'badge bg-label-info',
                                'answered_by_support' => 'badge bg-label-danger',
                                'closed' => 'badge bg-label-secondary',
                            ];
                            $priorityLabels = \App\Models\Ticket::PRIORITIES;
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="m-0">تیکت #{{ $ticket->id }} - {{ $ticket->subject }}</h4>
                            <a href="{{ route('tickets') }}" class="btn btn-label-secondary">بازگشت</a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-3"><strong>بخش:</strong> {{ $ticket->department_label }}</div>
                                    <div class="col-md-3"><strong>اولویت:</strong>
                                        {{ $priorityLabels[$ticket->priority] ?? $ticket->priority }}</div>
                                    <div class="col-md-3"><strong>وضعیت:</strong>
                                        <span
                                            class="{{ $statusBadgeClasses[$ticket->status] ?? 'badge bg-label-secondary' }}">
                                            {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                        </span>
                                    </div>
                                    <div class="col-md-3"><strong>مسئول:</strong>
                                        {{ $ticket->assigned_to?->name ?? 'بدون مسئول' }}</div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    @foreach ($ticket->messages as $message)
                                        @php
                                            $isMine = (int) $message->user_id === (int) auth()->id();
                                            $author = $message->user?->nam ?? ($message->user?->name ?? 'کاربر');
                                        @endphp
                                        <div class="ticket-message-row {{ $isMine ? 'mine' : 'other' }}">
                                            <div class="ticket-bubble {{ $isMine ? 'mine' : 'other' }}">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <strong>{{ $author }}</strong>
                                                    <small>{{ $message->created_at?->format('Y-m-d H:i') }}</small>
                                                </div>
                                                <div class="ticket-message-text">{{ $message->message }}</div>
                                                @if ($message->attachments->count())
                                                    <div class="mt-2">
                                                        <small class="d-block mb-1">ضمیمه‌ها:</small>
                                                        @foreach ($message->attachments as $attachment)
                                                            <a href="{{ route('tickets.attachments.download', $attachment) }}"
                                                                class="btn btn-sm btn-outline-primary me-1 mb-1">
                                                                {{ $attachment->original_name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($ticket->status !== \App\Models\Ticket::STATUS_CLOSED)
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3 text-end">
                                            <form method="POST"
                                                action="{{ route('tickets.close', $ticket->tracking_code) }}"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-label-danger">بستن تیکت</button>
                                            </form>
                                        </div>
                                        <form method="POST"
                                            action="{{ route('tickets.reply', $ticket->tracking_code) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">پاسخ شما</label>
                                                <textarea name="message" rows="5" class="form-control" required></textarea>
                                                @error('message')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">فایل ضمیمه (حداکثر 3 فایل - عکس، PDF،
                                                    ZIP)</label>
                                                <input type="file" name="attachments[]" class="form-control"
                                                    accept="image/*,.pdf,.zip" multiple>
                                                @error('attachments')
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                                @error('attachments.*')
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary">ارسال پاسخ</button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            @include('dashboard.sections.footer')
                        </div>
                    </div>
                </div>
            </div>
            <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/libs/jquery/jquery.js"></script>
            <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/vendor/js/bootstrap.js"></script>
            <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
            <script src="{{ asset(env('DASHBOARD_THEME_PATH')) }}/assets/js/main.js"></script>
            <script>
                $('.tickets').addClass('active');
            </script>
        </div>
</body>

</html>
