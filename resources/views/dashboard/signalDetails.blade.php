<!DOCTYPE html>
<html class="light-style layout-menu-fixed layout-compact" data-assets-path="{{ asset('/dashboard_theme') }}/assets/"
    data-template="horizontal-menu-template-no-customizer" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <title>جزئیات سیگنال - روحی بات</title>
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
    <link href="{{ asset('/dashboard_theme') }}/assets/vendor/libs/select2/select2.css" rel="stylesheet" />
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/helpers.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/config.js"></script>
    <!-- Better experience of RTL -->
    <link href="{{ asset('/dashboard_theme') }}/assets/css/rtl.css" rel="stylesheet" />
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
                    <div class="container-xxl psm-0 pt-0 flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-12">
                                <form method="POST" action="{{ route('updateSignalProcess', $signal->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card">
                                        <div
                                            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                                            <h5 class="card-title mb-sm-0 me-2">جزئیات سیگنال شماره <span
                                                    class="text-muted"
                                                    style="direction: ltr">#{{ $signal->tracking_code }}</span></h5>
                                            <div class="action-btns">
                                                <a class="btn btn-dark" href="{{ route('signalsHistory') }}">تاریخچه
                                                    سیگنال ها</a>
                                                <button class="btn btn-primary">بروزرسانی وضعیت سیگنال</button>

                                                <label class="text-dark mt-2 d-block">وضعیت سیگنال</label>
                                                <select name="tp_level" class="form-select">
                                                    <option value="0">-- انتخاب --</option>
                                                    <option value="1" @selected($signal->tp_level == 1 && $signal->final_result == 0)>TP 1 تاچ شد
                                                    </option>
                                                    <option value="2" @selected($signal->tp_level == 2 && $signal->final_result == 0)>TP 2 تاچ شد
                                                    </option>
                                                    <option value="3" @selected($signal->tp_level == 3 && $signal->final_result == 0)>TP 3 تاچ شد
                                                    </option>
                                                    <option value="4" @selected($signal->tp_level == 4 && $signal->final_result == 0)>TP 4 تاچ شد
                                                    </option>
                                                    <option value="5" @selected($signal->tp_level == 5 && $signal->final_result == 0)>TP 5 تاچ شد
                                                    </option>
                                                    <option value="6" @selected($signal->final_result == 1)>FULL TP</option>
                                                    <option value="7" @selected($signal->final_result == 2)>Stop Loss
                                                    </option>
                                                    <option value="8" @selected($signal->final_result == 3)>کنسل شده
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if (session('update'))
                                                <p class="alert alert-success">{{ session('update') }}</p>
                                            @endif
                                            <div class="row">
                                                <div class="col-lg-8 mx-auto">
                                                    <!-- 2. Delivery Type -->
                                                    <h5 class="my-4">نوع معامله</h5>
                                                    <div class="row gy-3">
                                                        <div class="col-md">
                                                            <style>
                                                                .custom-option-icon .custom-option-body svg {
                                                                    width: 195px !important;
                                                                    height: auto;
                                                                    !important;
                                                                }

                                                                .custom-option-icon .custom-option-body.long svg {
                                                                    width: 90px !important;
                                                                    height: auto;
                                                                    !important;
                                                                }

                                                                @media(max-width: 768px) {
                                                                    .action-btns .btn {
                                                                        display: block;
                                                                        width: 100%;
                                                                        margin: 7px 0px 5px 0px !important;
                                                                    }

                                                                    .psm-0 {
                                                                        padding-top: 0px !important;
                                                                    }
                                                                }
                                                            </style>
                                                            <div class="form-check custom-option custom-option-icon">
                                                                <label class="form-check-label custom-option-content"
                                                                    for="customRadioIcon1">
                                                                    <span class="custom-option-body">
                                                                        <svg width="195" height="112"
                                                                            viewBox="0 0 195 112" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                            <rect width="195" height="112"
                                                                                fill="url(#pattern0_4_3)" />
                                                                            <defs>
                                                                                <pattern id="pattern0_4_3"
                                                                                    patternContentUnits="objectBoundingBox"
                                                                                    width="1" height="1">
                                                                                    <use xlink:href="#image0_4_3"
                                                                                        transform="scale(0.00512821 0.00892857)" />
                                                                                </pattern>
                                                                                <image id="image0_4_3" width="195"
                                                                                    height="112"
                                                                                    preserveAspectRatio="none"
                                                                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMMAAABwCAYAAACjIcRxAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDkuMS1jMDAyIDc5LmExY2QxMmY0MSwgMjAyNC8xMS8wOC0xNjowOToyMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDI2LjIgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjBBODZGNzQ4Q0ZBNzExRjA5ODhDOUQwRkQ3NDkzMTY3IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjBBODZGNzQ5Q0ZBNzExRjA5ODhDOUQwRkQ3NDkzMTY3Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MEE4NkY3NDZDRkE3MTFGMDk4OEM5RDBGRDc0OTMxNjciIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MEE4NkY3NDdDRkE3MTFGMDk4OEM5RDBGRDc0OTMxNjciLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz5yMtKyAAAYlklEQVR42uxdfXBUVZa/r787CQmBJITwZRRWxRB0RAwoo0EHIiqWMzqKsBZLZgTHmrHKcavWWXWccVZ3LPhjptYVdEHGVZytcbXG709cxeFj3WEwrpaKisuXQEIgIenvfm/v76Rf5qXzut97ne7Oe+09VV0Nne773rvn97vnnnPPPVdSFIUJESKEMZfoAiFCBBmECBFkECJEkEGIEEEGIUIEGYQIEWQQIkSQQYiQbwIZEl980YSXUJvQUSHEY/cbDP3xj8eib71VKx86xJKSRJ+5FYW5GxtZYOnSHYFvf3u+gODoSuTdd7eHn3lmXrqOXJMmMf9ll3WWXXNNnROeQ7JrOkb41Ve/DP/+941KJMLtFzdgssxcdQN9Knd10f8Zv3fv3Lly5U9/6haQHB3pffBBOb5nj8RAAo+HucaNG9DRsWODepPKy1nw+uv3BdvaThdksCh9GzbEolu3epnbzVxVVczf1jZsdIHFCD/9dC3+7W9tjVesXu0T0CyunHr44Xhs2zaaXQSXLdPVUfTVV2vlnh7GkknmX7jQ1nqyHRm0HexftChasWpVINN3Y7t3P3Jq7do16OiqBx6Y5TnjjP8VEC2eb9Dzs599iAFrzJ13rvd961u3ZhzcNm2KRF9/3Y9/+xYsSIy57TavcKANBOCObd1KRCj/0Y92ZCMCdSxXgO+iixIw0ZE339wtIFpEPwH9zfvde/75cjYiQKBH6JN0zPULPQsyGDnLW7asYX4/WQSzjrF/3rx/g++QPHjQIyBaPKH+5v0eaG191Mz3oU/oFfolPQsyGHTwvn3kdBlZhCEPUFX1MN6VcFgSEC2eqP2t9r8ZIb1y/ZKeBRmyWAXubDGvl3nPOceSE8Ods9sENEdPrPY/6ZfrmfQtyJDBX9i2jSJDnpkzuyz9bs+ev8Pc1TVxoiygWUTgoL95v8fef7/dEhnOO+8rrb4FGfRGmBMn6N3qAk38/ff9KWdaRJKKKGp/x/fssRQZorUGPlVS9S3IkCYI0yn9/cxVXW3pd1iYQ6dK3CnjjtxsAdHiCfob/Y41BOjBEuiqqhj0bbe0DXuQYf/+pxCZcNXUWPIXQk880UiK+e53OwU8R4EQ6HdZZqHNmxutAJv0zPVNereRFCUcCWcpvnt3TfLgQQmjP583hsqXLy9X/548fHg6zfvr65Nm2zzxk58oMLfuhgbmlNyXUhP0O5/7K8kjR9iptWs/rH7kEVMRPdLz3r0e0rtG+p96qj/+l7+UwdpzvSreOXO6iqnbgpOh5+c/lxOffkqdlEwkmLuvDzHqsugbbyjBG2+kfBXemQGMFO7Jkw0nkjDJoSefJIsAMz127VoRUh1FQf93r1ypyKdOseMrVihlK1YY5iCRnhWllvTOJfL22x+Efve7ZspDw5pRMsmUvj4psXdvLR9E5apf/KIoM5iCXgQWIfHJJxJAW7Zy5b66Z56Rym+7bQfvDKbEYiz0+OONGA2U3l4CtLu+fmemtpAZCWsAk8xAqvp6Nm7jRkEEG8i4zZsl6AN6gX5O3HqrAoBnJENKz9A79N+/fn0z8ABcVNxxxwuEkzVrOqRAgAE/xQrDFjQ3qbu9XYGjhKX49BXlwUQ7j4dJ/IVRofLee9u9M2du0jrW0Z07d8Xee69M7u5mmEqhgwLXXtsppkb2E+g08txzteoILyHJ8pJLhkyJKQL18ceren/5y43QpcIJxOJxFrzppmE6xQDIiTJPqqhg4x59VHI0GY7fdJOS7UFUQiDFVwmHWdX998/C5yAA5o58OkVpwZLbTR0bWLLE9mnAQgamspGXX25UenqYwqc8sBieGTPIB/A1NS2k6fM993woBYMUVdLLeB0cUG+5ReFTJjZ+yxbnkwFhtGyOFfLhYQoBemRA4sHhGEup/0NOudzM09pKPpaAmjNEDofL5Z072Rg5FROBHwArgP0NfIDE//HynHmmUnnXXRmn65hyIXxbDDIU1IEG8/EgMHcZE+98PrBRUjsMv4F0dXaxcEJmSZCVd6C84TF8XC5g5hxx8WnQcUT8+PQ26HGxmtqaQWKQQLcD+s/oJ9I6Unlx1F5QywDnKPLCC2UY9QNXXDFk7kgRhKefblZCoSFW4KvDR5nMb8klXOPSsxYpvZ7WMGGItaAAy4oVHdqFU8LOSy+VYSAMXH31ML/DcWSguSFCq5gGeQdW7T2NjUry6FFJ6e0dGD3q6rAiqSQPHJBAiC8PHRVEKHFCnD5pwuAUKfn11xJtEQUYKyuZi7+wbjHgaceZ56yzlJIIrULwIAingv14Jb74gohA+2K541T9299Kvosu+gqhNSHfHEHEKbB48QboHzigIArWoA4fZipWgJtiEaHgPsPgg4dC9Uo0SlEFPZPnmTr1V9wcbsS/vdwsJMWZESUrXtXsc19C3SGHSBJ/DZtaAzfFvLeCkwExZVoow66opUt15358FPhvqqQgxGFzHpnJWFPA3D8WH6pTH58WSy5yEuBID9NvBn2n8NEfef75MuCGD5SrtGtPjvYZsESPTsNe2WwlXWhNIhgkBzq9wwf/iY7XiVhk61wh+QU/vfX3E/jHXDyfVbdeerR6UsNz6fugMQiePHDw7pM7dp7W8+ZbEr4vBQPMVVZGDjRmCuOffDKjd0iFIf70Jw/0mu17jiGD+kCumhqGuWGm71GlhdQiDJFB7fRQmEYWb309809qYP4pk2XfhAlh+ls47E309nrCez93xY8eY/Gurr+SQxAj/zwIhei97JyZrO6673XULLSWMg8d7/v3pzp63v4v6fTGqZSOg0XWbBVNkH6DGlko+lCMihoFmybh4YnZHNjlK1euz+pTRKNziQDc3Mp9/USAqtZLlbHzWr6qXbzI9IrzgY2boj3vbfeFP/qYuavHDpICcW5Paq6akBUW569cI1batqLJkW2u87v/StqRtqXeF55vJD5Xel8haQ6WoKLlQjb91tU5l+PB72bcd6+L3XcvLaTBgSa9M5axPeDm1EMPrQGOEm1tTYUuBVQwy9C7bl0y/uc/u7AMbxQRIKfplVfKsN4QXrS4f9JNyypGcu2urW9/cGjDY83xQ4dYTc04VuYZOjs7yU12XzxpGSTjA17mS7M6ubQFEtQGhtfS6ozELJOiwutmY31DB80YH1iOR+KWSTHO7x3SVygRGeavurvvXm9UDsbyjGHbNo+Z9QMKze/dKxlNs/MhBZtPgAgY6ctvvrnZ0Ip89lkQMeWyVat2jJQIEJjw2f+xRZr593d0Bvv7SKlaAXigeCtEmFjmH0YEbVuyMjIiQPC51lpknbYo+kSA4D5xv25JypkIyCtCPdvJzz4r5ZMIFFGaNet92twDvRsI4YfjiPBUYCnIBTDSU+MTJjAzpg2bfvCe7yLCCNlNeOMNSRo/fhghoHiAyYzAImS9Dm/La3LelYkIZv8+CCh+PT0iWLlvrXVJJ0LFypWhQu0VUfWs6t1oegUcaXHlKDIg5Ro+QPDaazuMvovMVdr/nCpYWwhB1qx3/nwZSk4fQY1GdIyuPhMOOXJvrPgII/2emev5UnlBRhZG+3zoo+pf/3p9odMfoG/o3cxeBcIRxxPhyklkgOMc7+yk1Gszm/QjL71UC5MZ/P73Owr5oJhvli9bFtUSIt2X0B2ZTI74Zohl1noYfS8dwCO9f/QDLCf6ZtxvftOe72mRLsChb35N0r+RJQGOOJ6Aq0IWEcg7GSLvvPM/bn7jZoqBIVkPCqC9CkWobkE1PzWEiMnGzmpCNu+EFiunysp1rNw/iFCsBS6qroEqGchqzrIrbnCA4HgCrrDXxTFkiO/ZQ3WM1GJRWX2Lxx5rxu61wJVXFq26hZYQCRMhVrMRmf6EcUQJKelmxMz3zFzPzP27FJmFeFuV999fNCIMEgJ65/onHBiRQS0+tmtXmWPIoGYgGu1IQ8iM5uSTJxe9ugUR4rrr4l3HT5r6PkKeWX0kbmHMABjADBmAGH83Q0Bcz8iyGd035uHJEyfZpAf+qehEUAMc0L8WDxmnVSk8qfiyPRmwBE/1j+rqDJ1mqpjBlTFa1S1waEbVZa2KurKaTRD7P5mWe6MlwrFwzPTUpTsaz0gIfI6/m50q4bqZCIH7NVqzABFOu/++faNBBFVI//wZgAcjZ5pwxfFFOLM9Gfbu/WeYPffEiUo2woS3bCGnuXz16g42ioIVUaR6MBO+AxbWvg5FCWQALV4YeY+ErKeed0XiQ9rCO9rC51YF18dv1XtCW2jbaCEQpV3qV/8gZmWFv1BCOMACH8dFNqATrji+CGd2J4N85MjYVNgskek7p9at24hNPDiLzQ4lIbE4RwmAsrlpDkAG0GIEx8ibi9OM32jbwvtI2sJvcT9qW4bTLP6sZc2z2JT2VX5mAwEOgAfggvCR6VlTuFJxZmsyJA8fpgMs3NOmHdT7Ozb/owoGEvfsdCjhlDvvOIr8m9GIGhW9LaRd82ed+a//Yqv9hMADcAF8ACe6lgG4QpEx4MzuZFD6+6mDabNOmiB8RqdCulxszO23z7KTIiZcfVU9EtHM+A9OF/gJ0+75R1ueFkKZzRwfwIleuFXFlYoze0+TsK8ZUyAdhwyb/zHfC15/facdDyI8a+1Dkrpqhjd5BPmLcoGy4kfcLgIWS9oUO/gJGSNMK1bsA04IL2lCuOL4klP7521NBhaLMck7PB8mVUKSSs7buRJe3YplcSSs1Zf56IWcHSvJbvi++nu8m02/MCIA2klv1ywxcP/4TV3QxybJcQoa2NlyIYQKnAAverlIhK8C7ZfP7zQJRaJ0wEMlP/hcr2zlyhfsrIipq2/xIcs1mMpHQiIcsj+NQA3AAWz4PlIb8Fu8I+kOBMl1RMfvagJeaie9XRDDDDlx//gNnokDzRGnGxFOBlI1hi+w8ecgnNneMiAi4x7qF9NBFvxz95QpzH/hhUvtrgjuyO1LpJlhgC+bhdDb56AKCFLpyy1WgN9lyp/C9bKloYPA6VmtdgpaZBPgBHgBboYdhAJ8ybIDyIBKaWlkQM1NesDLL9/nBEXATPvOPntYyneVz6M7wmP0NUqaM0q1zmRtjH4HouhZLdwn7newLf4s/iuvjDrJ0VfxouJnCBmSSXuTAdmESZ2blA8dosrZTioYjO2G6dYh2whtFtxWxDOCDFd8pL0vPIuV44TtMigBN8DP8DE3WZDs1byRAftZ3aiWrbEMZOI8HuaZNctRJ3Eihdk/e/Yw65COO4zAZkHrsbig4LWQOp6NeHgGJCYyBwrhBluBNVMl4As4S+2ftikZIpEL6GY1RWLjH300jR5q+vTjTlNE+apV7enWIX2aBLyGTGaPWt3bHDfpdevlJml/60SrMEiGFG5UHGnxpeLNnmSIxycNM2eff07te2fM+AenKQIxbW9j46B1QM6P3mBtJlsVgLUaUQJ5zOy30Lu+SlKkqTvVKmhxo+LICG/2IUMoROyVgsFBtaM2DmohjWZW5IisQ3v7CxhZAcpMiW8YhTNltKpiJatVK8cNEveQoJcpD6k7HGWeykrHWgV1QKIDTTTrCiq+VLzZkgxyb+9EutmxYzU2OoH6+07VBYX4Iqc1smP9kcwdKA1ktOrtHQCJDmb5rZEA6MhA1bMQRmVlkHzYv2hxnDldgB/NuoKKLxVv9iRDKpPQXV8f0ZgyiiQ5Wfg0o8NMEh+Aub8vQuAFUEGCXNK79QiBdtR28Y7rZPVBUuTBIqLTuUDnvsX/ymkVX4XIXM0fGbq7KbDtbmj4nJWQoAZTYMYMUws9amq2CtR8ZaRq28W7UbuwCqhIyEpQVHzJfX1u25IhuX8/qchVVbV9kNXII4k731JXf+eyfr2ix3YVJRxhjX+7vLkk0I/ZhSbfTcWXnlNtGzJQeRg+txtSZgR5JAVaLSymDFb5kx2wXIKNO+c2MztmBudEbOBHs25C+OI4I7zZkQzIPUcZDzrFUTvfQyQAp3eWgFRcMGfATGNba+o1YpOf57Yom5X7NxNuvKGDlYjQmX/BoVUogTPgzUyJmaKTIfHZZ2fj3dPcPGTodNXUKMlYjMV2737E8b7Dkis6kAKNLFD1Nbk8kFOatprlqm0L/8+FFLg+7gNt1Pu9bGp9LbNaLt6uAtwk+fQUONJ+ruJMxZ2tyBD/6CNvyoQNMc3uxsYYls4TBw58rxQcab3PraZpZypirBYLtkIuXDe9NmuwtTXBSkSAG+AHOBrSVymcqbizFRkomQrHVKVt8Pede+7jSKqK795dUwrKwWquW2eRC9mlZnOJAPhsYrbwsF5WKxYIg21t55UKGYAb4Ac40n5OOON60EviG1Uy0G4k7u3jiNL0v8HZAXhwwmcpKCdwySVzEhm2HI524WH0s2/69JJxnIncHDf0XDq1XwlvHHf5rMw9YjJE33mHdiP5L730Q72/e5qaFITHhm3ScKAAaACcnnUoduHh9JRykNTf1tZZKkQgvOAcaOBHb8BI4U3F36iTAQ4OnelcWZmxcHDwmms2wNSFn322sRSU5Lv44owlNIp5mLteQWE77y+3TAaOF+AG+NG10ihczHEH/OUrQDMiMkRee201ned7/vkZV9Zg4rwNDSzR3c2iu3Y973i/Yfnycr2pUq4Zprl8D6TTXg+WKtDSUjIrzsAJ8ALcZCuPT7jj+CMcjiYZwMb4Bx9I2IKHuqVZ59pLluxDVCDy4otXlYKytKndKhHMnOtmtvCwmb0MvbHkkClS4KqrXiwVMgAnwAtwk+17hDsEaDgO82EdciZDaMuWNWAllQU0cvawha+iAnFhqVBFY4s6VWppGbJH4LiFGqkoA5nJiuBz/N3MdAvfQdLe4BzaAcUWTEWQOD6AE+DFzFZhwh/HIeFxNMgA5yZ5cKCCpNmKC4GlS6kWf9/69RtLJaqEfQzITrV6qqa2WDAIkGsRY1x3f2c36//OYpmViBA+cGYH8GJCVPwBjyMN0uREhtDmzeQMB2+4wXT0As4dikOhvr6Zc7zsHlU6UlnN+qK5JSFqiwWDACgYnGvhYUwTJl++8NFSIAJwAXxYLTan4jD0+OONRSXDyTvvVLBJO5dDRsp/+MP1FGb9wx9qna646kWXx/ORyQoC5ByFwnSrYgwrxhlsRYkgARccH4QTCzJ46InXO4DPYpABh1knDx+mf+dyyAhFlubPl6FEnBLvZMVNXHDxGqRKj6bQvoWWuSURRSI8cFx458xRciG3ikfgEzgtKBn6Nm2K4FR3Fo2yih//OOcykVR6vKqKyT09rHfdOsfmdw/szw2Mblo3nyJNW3rVBqcTATgAHoCLyrvuyjmoQ7jk+AROgdeCkAENR197zU9H1N58c+dIIxf8ptsBovj27a5CHmVaaMFuMu1USS0QjJfVosUZR39lIA9JLWqsLWjsGuP8KRIdlcxxADwQLkYgwCXwCZwCr1YJISkGkRAcHEHrCXwuhwvla5UTzhJ3lD5XT4t3onS+9vqX//erBxtdFeWUgq1X0AsRJ6wJ5OIXgAiot6pXZpLWNk6fPlBK3+ESeffd7fKJE9Pzia3wE0/Uwofwzp6tmLU2hl9yT50aJqfm9tt35HO5H205mQgQOudAkTMSgeayHMhmkvh0p5QZiEDTtFOn2NQFF5VELhJwkG9sAa/ALeHXpBgeB4T0A39LS1MpZUPmlRBnn8mC3cdZNucHadmoaGHVOhgVHnZicbZiEswzaZIl3JoasgQRMkv16Y2mojlei0wwSuPGDkKnFmcrlljFrUt02cgEJ9cnTByrlM/Cw8iLKrvgAtH5eRZBhhGK2VL7CYvFVo0yV30LFnSK3hdksJ91aGjQ3fCjCiI/cYtkyJbvBEvka2paKHpekMF+c1OdLa9aQVZrLqFVbVbqkOtVVgo/TpDBnhK7sOXDfUe7dC0CAG01q1VrHdIJAQuElAXR64IMthSUkVFS6dxIxVYLBCMjNVciaAmBdtXCwyBduGnWMdHrBbDwogvyK9rq2PncEw1SJJMKA+nGzzz7ctHTwjLYVlDftOBJezhCuHqs8BcEGewtlRfOjRWjUrevoUF0tiCDvSXY2PgJK0LFcf+0qcJ5FmRwhhNd0FkStzyBKZPjorcFGWwvvimTC+s3oKgWLJAQQQbbO9GzmpSC+g2Si42dNnW56GlBBttLQacwsDguSUSSBBmEE03KcvjJqYIM3zAnGvuS5b7+/PkOaIe/cDzVmHktIpJUQDHcAy3Euhza8nRf138+Vx4/epS5ystTw47LMgnI/5AVVnHB+eyMH6xqF5t5BBkcK11b3/6g6+VXmvt27hr4wO3OOtVRwa9Eoyx4zkxW2TI3XgoHmwsyCBkiqBLd+cmna0Kf7WXxY8eYHI6wZChExwW7KyuxmMbK/mYG89fXd5TKAYWCDEKECAdaiBBBBiFCBBmECBFkECJEkEGIEEEGIUIEGYQI+abI/wswAPIWgyzJOjhcAAAAAElFTkSuQmCC" />
                                                                            </defs>
                                                                        </svg>

                                                                        <span class="custom-option-title">شـــورت</span>
                                                                    </span>
                                                                    <input class="form-check-input"
                                                                        id="customRadioIcon1" name="type"
                                                                        type="radio" value="1"
                                                                        @if ($signal->type == 1) checked @endif />
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md">
                                                            <div class="form-check custom-option custom-option-icon">
                                                                <label class="form-check-label custom-option-content"
                                                                    for="customRadioIcon2">
                                                                    <span class="custom-option-body long">
                                                                        <svg width="170" height="217"
                                                                            viewBox="0 0 170 217" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                            <rect width="170" height="217"
                                                                                fill="url(#pattern0_4_4)" />
                                                                            <defs>
                                                                                <pattern id="pattern0_4_4"
                                                                                    patternContentUnits="objectBoundingBox"
                                                                                    width="1" height="1">
                                                                                    <use xlink:href="#image0_4_4"
                                                                                        transform="scale(0.00588235 0.00460829)" />
                                                                                </pattern>
                                                                                <image id="image0_4_4" width="170"
                                                                                    height="217"
                                                                                    preserveAspectRatio="none"
                                                                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKoAAADZCAYAAACwwKvtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAxRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDkuMS1jMDAyIDc5LmExY2QxMmY0MSwgMjAyNC8xMS8wOC0xNjowOToyMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RkMwMjEwMjBDRkE3MTFGMDg0RUNCNzc4RTI0MTAxNzYiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RkMwMjEwMUZDRkE3MTFGMDg0RUNCNzc4RTI0MTAxNzYiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIwMjUgV2luZG93cyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSI4RkY2NjMxNjgwRUI0MzU0OURGMzFFRDlCQjdDMjc4MSIgc3RSZWY6ZG9jdW1lbnRJRD0iOEZGNjYzMTY4MEVCNDM1NDlERjMxRUQ5QkI3QzI3ODEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6ewfTeAABi2klEQVR42ux9CZxcVZX+eVvtvW/p7CvEQCIQ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" />
                                                                            </defs>
                                                                        </svg>

                                                                        <span class="custom-option-title">
                                                                            لانـــــگ</span>
                                                                    </span>
                                                                    <input class="form-check-input"
                                                                        id="customRadioIcon2" name="type"
                                                                        type="radio" value="2"
                                                                        @if ($signal->type == 2) checked @endif />
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <h5 class="my-4">تصویر سیگنال</h5>
                                                    <div class="row g-3">
                                                        <div class="mb-3">
                                                            @if ($signal->photo)
                                                                <div class="mb-2">
                                                                    <img src="{{ asset('/signals') }}/{{ $signal->photo }}"
                                                                        alt="signal-{{ $signal->tracking_code }}"
                                                                        style="max-width: 240px; max-height: 180px; border-radius: 12px; background: #000; object-fit: contain;">
                                                                </div>
                                                            @endif
                                                            <label class="form-label" for="signalPhoto">ویرایش
                                                                تصویر</label>
                                                            <input class="form-control" id="signalPhoto" type="file"
                                                                name="signal_photo"
                                                                accept=".png,.jpg,.jpeg,.webp,.heic,.heif,image/png,image/jpeg,image/webp,image/heic,image/heif" />
                                                            @error('signal_photo')
                                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <h5 class="my-4">نماد ارز</h5>
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <input class="form-control" name="symbol"
                                                                placeholder="BTC..." type="text"
                                                                value="{{ $signal->symbol }}" />
                                                        </div>
                                                    </div>
                                                    <h5 class="my-4">قیمت ورودی / استاپ لاس</h5>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="entryPrice1">قیمت ورودی
                                                                اول</label>
                                                            <input class="form-control" id="entryPrice1"
                                                                name="entryPrice1" placeholder="" type="text"
                                                                value="{{ $signal->entryPrice1 }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="entryPrice2">قیمت ورودی
                                                                دوم</label>
                                                            <input class="form-control" id="entryPrice2"
                                                                name="entryPrice2" placeholder="" type="text"
                                                                value="{{ $signal->entryPrice2 }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="sl">قیمت استاپ
                                                                لاس</label>
                                                            <input class="form-control phone-mask bdi" id="sl"
                                                                name="sl" placeholder="658 799 8941"
                                                                type="text" value="{{ $signal->sl }}" />
                                                        </div>
                                                    </div>
                                                    <hr />

                                                    <h5 class="my-4">تارگت ها</h5>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="target1">تارگت اول</label>
                                                            <input class="form-control" id="target1" name="target1"
                                                                placeholder="" type="text"
                                                                value="{{ $signal->target1 }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="target2">تارگت دوم</label>
                                                            <input class="form-control" id="target2" name="target2"
                                                                placeholder="" type="text"
                                                                value="{{ $signal->target2 }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="target3">تارگت سوم</label>
                                                            <input class="form-control bdi" id="target3"
                                                                name="target3" type="text"
                                                                value="{{ $signal->target3 }}" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="target4">تارگت
                                                                چهارم</label>
                                                            <input class="form-control bdi" id="target4"
                                                                name="target4" value="{{ $signal->target4 }}"
                                                                type="text" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="target5">تارگت
                                                                پنجم</label>
                                                            <input class="form-control bdi" id="target5"
                                                                name="target5" value="{{ $signal->target5 }}"
                                                                type="text" />
                                                        </div>
                                                    </div>
                                                    <!-- نمایش سود تارگت‌های تاچ‌شده -->
                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <div class="alert alert-info">
                                                                <b>سود تارگت‌های تاچ‌شده:</b>
                                                                <ul class="mb-0">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @if ($signal->tp_level >= $i && isset($targets[$i]))
                                                                            <li>
                                                                                TP{{ $i }} تاچ شد:
                                                                                <span
                                                                                    class="text-success">{{ $targets[$i] }}%</span>
                                                                            </li>
                                                                        @endif
                                                                    @endfor
                                                                    @if ($signal->final_result == 1 && isset($targets[5]))
                                                                        <li>
                                                                            FULL TP:
                                                                            <span
                                                                                class="text-success">{{ $targets[5] }}%</span>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 my-3">
                                                            <label class="form-label" for="laverege">عدد اهرم</label>
                                                            <input class="form-control bdi" id="laverege"
                                                                name="laverege" value="{{ $signal->laverege }}"
                                                                type="text" />
                                                        </div>

                                                        <div class="col-md-12 mt-0 mb-2">
                                                            <label class="form-label" for="profit">مقدار سود /
                                                                ضرر</label>
                                                            <input class="form-control profit" id="profit"
                                                                name="profit" value="{{ $signal->profit }}"
                                                                type="number" />
                                                            <small class="text-muted">فقط عدد وارد شود. اگر سیگنال در
                                                                سود باشد این فیلد به عنوان مقدار سود و اگر ضرر باشد به
                                                                عنوان درصد ضرر محاسبه میشود.</small>
                                                        </div>


                                                        <div class="my-3">
                                                            <div class="form-check form-check-inline">
                                                                <input
                                                                    class="form-check-input form-check-input-payment"
                                                                    id="isVisible1" name="isVisible" type="radio"
                                                                    value="1"
                                                                    @if ($signal->isVisible == 1) checked @endif />
                                                                <label class="form-check-label d-flex gap-1"
                                                                    for="isVisible1">نمایش در کانال</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input
                                                                    class="form-check-input form-check-input-payment"
                                                                    id="isVisible0" name="isVisible" type="radio"
                                                                    value="0"
                                                                    @if ($signal->isVisible == 0) checked @endif />
                                                                <label class="form-check-label d-flex gap-1"
                                                                    for="isVisible0">عدم نمایش در کانال برای کاربران
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /Sticky Actions -->
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
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('/dashboard_theme') }}/assets/vendor/libs/select2/i18n/fa.js"></script>
    <!-- Main JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/main.js"></script>
    <!-- Page JS -->
    <script src="{{ asset('/dashboard_theme') }}/assets/js/form-layouts.js"></script>
    <script></script>
</body>

</html>
