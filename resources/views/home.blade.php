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
                                                    <div id="earnings-charts"></div>
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
        console.log($negativeChunks);
        $(document).ready(function() {
            var $earningsChart = document.querySelector('#earnings-charts');
            earningsChartOptions = {
                chart: {
                    type: 'donut',
                    height: 150,
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{{ $negativeChunks->count ?? 0 }}, {{ $positiveChunks->count ?? 0 }},
                    {{ $neutralChunks->count ?? 0 }},
                    {{ $mixedChunks->count ?? 0 }}
                ],
                legend: {
                    show: true
                },

                labels: ['negative', 'positiove', 'neutral', 'mixed'],
                stroke: {
                    width: 0
                },
                colors: [$negativeChunks, $positiveChunks, $neutralChunks, $mixedChunks],
                grid: {
                    padding: {
                        right: -20,
                        bottom: -8,
                        left: -20
                    }
                },
                plotOptions: {
                    pie: {
                        startAngle: -10,
                        donut: {
                            labels: {
                                show: true,
                                name: {
                                    offsetY: 15
                                },
                                value: {
                                    offsetY: -15,
                                    formatter: function(val) {
                                        return parseInt(val) + '%';
                                    }
                                },
                                total: {
                                    show: true,
                                    offsetY: 15,
                                    label: 'Opreational',
                                    formatter: function(w) {
                                        return {{ $chunks->count() }};
                                    }
                                }
                            }
                        }
                    }
                },
                responsive: [{
                        breakpoint: 1325,
                        options: {
                            chart: {
                                height: 100
                            }
                        }
                    },
                    {
                        breakpoint: 1200,
                        options: {
                            chart: {
                                height: 120
                            }
                        }
                    },
                    {
                        breakpoint: 1045,
                        options: {
                            chart: {
                                height: 100
                            }
                        }
                    },
                    {
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 120
                            }
                        }
                    }
                ]
            };
            earningsChart = new ApexCharts($earningsChart, earningsChartOptions);
            earningsChart.render();
        });
    </script>


@endpush
