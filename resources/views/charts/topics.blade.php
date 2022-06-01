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
@push('js')
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
                // topics
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
