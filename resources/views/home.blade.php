@extends('./welcome')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <!-- Statistics Card -->
                        <div class="col-xl-12 col-md-6 col-12">
                            <div class="card card-statistics">
                                <div class="card-header">
                                    <h4 class="card-title">Overall Statistics</h4>
                                    <div class="d-flex align-items-center">
                                        <p class="card-text font-small-2 me-25 mb-0">Updated 1 month ago</p>
                                    </div>
                                </div>
                                <div class="card-body statistics-body">
                                    <div class="row">

                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-primary me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="trending-up" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0">{{ $negative->count ?? 0 }}</h4>
                                                    <p class="card-text font-small-3 mb-0">Negative</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-info me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="user" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0">{{ $positive->count ?? 0 }}</h4>
                                                    <p class="card-text font-small-3 mb-0">Positive</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-danger me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="box" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0">{{ $neutral->count ?? 0 }}</h4>
                                                    <p class="card-text font-small-3 mb-0">Neutral</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-success me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0">{{ $mixed->count ?? 0 }}</h4>
                                                    <p class="card-text font-small-3 mb-0">Mixed</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div>
                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <div class="row match-height">

                                <!-- Earnings Card -->
                                <div class="col-lg-12 col-md-6 col-12">
                                    <div class="card earnings-card">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-12">
                                                    <div>
                                                        <h2>Chunks Pie Chart</h2>
                                                    </div>
                                                    <div id="donut-chart"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Earnings Card -->
                            </div>
                        </div>
                    </div>
                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <div class="row match-height">

                                <!-- Earnings Card -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="card earnings-card">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-12">
                                                    <div>
                                                        <h2>Chunks Pie Chart</h2>
                                                    </div>
                                                    <div id="column-chart2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Earnings Card -->
                            </div>
                        </div>
                    </div>
                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <div class="row match-height">

                                <!-- Earnings Card -->
                                <div class="col-lg-12 col-md-6 col-12">
                                    <div class="card earnings-card">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-12">
                                                    <div>
                                                        <h2>Categories</h2>
                                                    </div>
                                                    <div id="heatmap-chart"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Earnings Card -->
                            </div>
                        </div>
                    </div>

                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <div class="row match-height">

                                <!-- Earnings Card -->
                                <div class="col-lg-12 col-md-6 col-12">
                                    <div class="card earnings-card">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-12">
                                                    <div>
                                                        <h2>Categories</h2>
                                                    </div>
                                                    <div id="revenue-report-chart2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Earnings Card -->
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script>
        var colors = @json($colors);
        var $negativeChunks = `rgb${colors.negative}`;
        var $positiveChunks = `rgb${colors.positiove}`;
        var $neutralChunks = `rgb${colors.neutral}`;
        var $mixedChunks = `rgb${colors.mixed}`;
        var flatPicker = $('.flat-picker'),
            isRtl = $('html').attr('data-textdirection') === 'rtl';

        var donutChartEl = document.querySelector('#donut-chart'),
            donutChartConfig = {
                chart: {
                    height: 350,
                    type: 'donut'
                },
                legend: {
                    show: true,
                    position: 'bottom'
                },
                labels: ['negative', 'positiove', 'neutral', 'mixed'],
                series: [{{ $negativeChunks ?? 0 }}, {{ $positiveChunks ?? 0 }},
                    {{ $neutralChunks ?? 0 }},
                    {{ $mixedChunks ?? 0 }}
                ],
                colors: [$negativeChunks, $positiveChunks, $neutralChunks, $mixedChunks],
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opt) {
                        return parseInt(val) + '%';
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                name: {
                                    fontSize: '2rem',
                                    fontFamily: 'Montserrat'
                                },
                                value: {
                                    fontSize: '1rem',
                                    fontFamily: 'Montserrat',
                                    formatter: function(val) {
                                        return parseInt((parseInt(val) / {{ $chunks->count() }}) * 100) + '%';
                                    }
                                },
                                total: {
                                    show: true,
                                    fontSize: '1.5rem',
                                    label: 'negative',
                                    formatter: function(w) {
                                        return parseInt({{ ($negativeChunks / $chunks->count()) * 100 }}) + '%';
                                    }
                                }
                            }
                        }
                    }
                },
                responsive: [{
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 380
                            }
                        }
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: {
                                height: 320
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        labels: {
                                            show: true,
                                            name: {
                                                fontSize: '1.5rem'
                                            },
                                            value: {
                                                fontSize: '1rem'
                                            },
                                            total: {
                                                fontSize: '1.5rem'
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                ]
            };
        if (typeof donutChartEl !== undefined && donutChartEl !== null) {
            var donutChart = new ApexCharts(donutChartEl, donutChartConfig);
            donutChart.render();
        }
    </script>

    <script>
        // Heat map chart
        // --------------------------------------------------------------------

        $(function() {
            'use strict';

            function generateDataHeat(count, yrange) {
                var i = 0;
                var series = [];
                while (i < count) {
                    var x = 'w' + (i + 1).toString();
                    var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

                    series.push({
                        x: x,
                        y: y
                    });
                    i++;
                }
                return series;
            }
            var heatmapEl = document.querySelector('#heatmap-chart'),
                heatmapChartConfig = {
                    chart: {
                        height: 350,
                        type: 'heatmap',
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        heatmap: {
                            enableShades: false,

                            colorScale: {
                                ranges: [{
                                        from: 0,
                                        to: 10,
                                        name: '0-10',
                                        color: '#b9b3f8'
                                    },
                                    {
                                        from: 11,
                                        to: 20,
                                        name: '10-20',
                                        color: '#aba4f6'
                                    },
                                    {
                                        from: 21,
                                        to: 30,
                                        name: '20-30',
                                        color: '#9d95f5'
                                    },
                                    {
                                        from: 31,
                                        to: 40,
                                        name: '30-40',
                                        color: '#8f85f3'
                                    },
                                    {
                                        from: 41,
                                        to: 50,
                                        name: '40-50',
                                        color: '#8176f2'
                                    },
                                    {
                                        from: 51,
                                        to: 60,
                                        name: '50-60',
                                        color: '#7367f0'
                                    }
                                ]
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: true,
                        position: 'bottom'
                    },
                    grid: {
                        padding: {
                            top: -25
                        }
                    },
                    series: [{
                            name: 'SUN',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'MON',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'TUE',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'WED',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'THU',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'FRI',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'SAT',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        }
                    ],
                    xaxis: {
                        labels: {
                            show: false
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    }
                };
            if (typeof heatmapEl !== undefined && heatmapEl !== null) {
                var heatmapChart = new ApexCharts(heatmapEl, heatmapChartConfig);
                heatmapChart.render();
            }
        });
    </script>

    <script>
        var flatPicker = $('.flat-picker'),
            isRtl = $('html').attr('data-textdirection') === 'rtl',
            chartColors = {
                column: {
                    series1: '#826af9',
                    series2: '#d2b0ff',
                    bg: '#f8d3ff'
                },
                success: {
                    shade_100: '#7eefc7',
                    shade_200: '#06774f'
                },
                donut: {
                    series1: '#ffe700',
                    series2: '#00d4bd',
                    series3: '#826bf8',
                    series4: '#2b9bf4',
                    series5: '#FFA1A1'
                },
                area: {
                    series3: '#a4f8cd',
                    series2: '#60f2ca',
                    series1: '#2bdac7'
                }
            };
        // Column Chart
        // --------------------------------------------------------------------
        var columnChartEl = document.querySelector('#column-chart2'),
            columnChartConfig = {
                chart: {
                    height: 400,
                    type: 'bar',
                    stacked: true,
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        columnWidth: '15%',
                        colors: {
                            backgroundBarColors: [
                                chartColors.column.bg,
                                chartColors.column.bg,
                                chartColors.column.bg,
                                chartColors.column.bg,
                                chartColors.column.bg
                            ],
                            backgroundBarRadius: 10
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'start'
                },
                colors: [chartColors.column.series1, chartColors.column.series2],
                stroke: {
                    show: true,
                    colors: ['transparent']
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                series: [
                    @foreach ($rrrrrr as $key => $category)
                        {
                            name: "{{ $key }}",
                            data: [...@json($category['data'])]

                        },
                    @endforeach

                ],
                xaxis: {
                    categories: [

                            @foreach ($rrrrrr['positive']['name'] as $key => $value)
                                "{{ $value }}",
                            @endforeach

                    ]
                },
                fill: {
                    opacity: 1
                },
                yaxis: {
                    opposite: isRtl
                }
            };
        if (typeof columnChartEl !== undefined && columnChartEl !== null) {
            var columnChart = new ApexCharts(columnChartEl, columnChartConfig);
            columnChart.render();
        }
    </script>


    <script>
        var colors = @json($colors);
        var $negativeChunks = `rgb${colors.negative}`;
        var $positiveChunks = `rgb${colors.positiove}`;
        var $neutralChunks = `rgb${colors.neutral}`;
        var $mixedChunks = `rgb${colors.mixed}`;
        var revenueReportChartOptions;
        var $revenueReportChart = document.querySelector('#revenue-report-chart2');
        // Revenue Report Chart
        // ----------------------------------
        revenueReportChartOptions = {
            chart: {
                height: 230,
                stacked: true,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '17%',
                    endingShape: 'rounded'
                },
                distributed: true
            },
            colors: [$negativeChunks, $positiveChunks],
            series: [{
                    name: 'Earning',
                    data: [95, 177, 284, 256, 105, 63, 168, 218, 72]
                },
                {
                    name: 'Expense',
                    data: [-145, -80, -60, -180, -100, -60, -85, -75, -100]
                }
            ],
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            grid: {
                padding: {
                    top: -20,
                    bottom: -10
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                labels: {
                    style: {
                        colors: $positiveChunks,
                        fontSize: '0.86rem'
                    }
                },
                axisTicks: {
                    show: false
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: $positiveChunks,
                        fontSize: '0.86rem'
                    }
                }
            }
        };
        revenueReportChart = new ApexCharts($revenueReportChart, revenueReportChartOptions);
        revenueReportChart.render();
    </script>
@endpush
