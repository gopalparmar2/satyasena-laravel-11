@extends('admin.layouts.app')
@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif
@section('styles')
    @parent

@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                @can('user-list')
                    <div class="col-md-3">
                        <a href="{{ route('admin.user.index') }}">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Total Users</p>
                                            <h4 class="mb-0">{{ $total_users }}</h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bxs-group font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan

                @can('caste-list')
                    <div class="col-md-3">
                        <a href="{{ route('admin.caste.index') }}">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Total Castes</p>
                                            <h4 class="mb-0">{{ $total_castes }}</h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bxs-group font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan

                @can('user-list')
                    <div class="col-md-3">
                        <a href="{{ route('admin.user.index') }}">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Total Surnames</p>
                                            <h4 class="mb-0">{{ $total_surnames }}</h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bxs-group font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan

                @can('category-list')
                    <div class="col-md-3">
                        <a href="{{ route('admin.category.index') }}">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Total Categories</p>
                                            <h4 class="mb-0">{{ $total_categories }}</h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bx-customize font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Caste Chart</h4>

                    <div id="caste_chart"
                        data-colors='["--bs-primary", "--bs-secondary", "--bs-success", "--bs-danger", "--bs-warning", "--bs-info", "--bs-dark"]'
                        class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Surname Chart</h4>

                    <div id="surname_chart"
                        data-colors='["--bs-primary", "--bs-secondary", "--bs-success", "--bs-danger", "--bs-warning", "--bs-info", "--bs-dark"]'
                        class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Color Chart</h4>

                    <div id="color_chart" data-colors='["--bs-success", "--bs-danger", "--bs-info"]' class="apex-charts"
                        dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modals')

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <script>
        let casteOptions;
        let casteChartColors = getChartColorsArray("caste_chart");
        let casteChartElement = document.getElementById('caste_chart');

        $.ajax({
            type: "POST",
            url: "{{ route('admin.get.caste.chart.data') }}",
            data: {
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                casteOptions = barChartOptions(response.series, response.labels, casteChartColors);

                new ApexCharts(casteChartElement, casteOptions).render();
            }
        });

        let surnameOptions;
        let surnameChartColors = getChartColorsArray("surname_chart");
        let surnameChartElement = document.getElementById('surname_chart');

        $.ajax({
            type: "POST",
            url: "{{ route('admin.get.surname.chart.data') }}",
            data: {
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                surnameOptions = barChartOptions(response.series, response.labels, surnameChartColors);

                new ApexCharts(surnameChartElement, surnameOptions).render();
            }
        });

        let colorOptions;
        let colorChartColors = getChartColorsArray("color_chart");
        let colorChartElement = document.getElementById('color_chart');

        $.ajax({
            type: "POST",
            url: "{{ route('admin.get.color.chart.data') }}",
            data: {
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                colorOptions = {
                    chart: {
                        height: 320,
                        type: "donut"
                    },
                    series: response.series,
                    labels: ["Green", "Red", "White"],
                    colors: colorChartColors,
                    legend: {
                        show: !0,
                        position: "bottom",
                        horizontalAlign: "center",
                        verticalAlign: "middle",
                        floating: !1,
                        fontSize: "14px",
                        offsetX: 0,
                    },
                    responsive: [{
                        breakpoint: 600,
                        options: {
                            chart: {
                                height: 240
                            },
                            legend: {
                                show: !1
                            }
                        },
                    }, ],
                };

                new ApexCharts(colorChartElement, colorOptions).render();
            }
        });

        function getChartColorsArray(e) {
            if (null !== document.getElementById(e)) {
                var t = document.getElementById(e).getAttribute("data-colors");

                if (t) {
                    return (t = JSON.parse(t)).map(function(e) {
                        var t = e.replace(" ", "");

                        if (-1 === t.indexOf(",")) {
                            var r = getComputedStyle(document.documentElement).getPropertyValue(t);
                            return r || t;
                        }

                        var o = e.split(",");
                        return 2 != o.length ? t : "rgba(" + getComputedStyle(document.documentElement)
                            .getPropertyValue(o[0]) + "," + o[1] + ")";
                    });
                }
            }
        }

        function barChartOptions(series, labels, colors) {
            return {
                series: [{
                    name: 'Total Users',
                    data: series
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                },
                colors: colors,
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                xaxis: {
                    categories: labels,
                    labels: {
                        style: {
                            colors: colors,
                            fontSize: '12px'
                        }
                    }
                }
            }
        }
    </script>
@endsection
