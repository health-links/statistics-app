<div class="col-lg-12 col-md-12 col-12">
    <div class="card earnings-card">
           <div class="card-header">
                        <h6 class="heading-card">Categories</h6>
                    </div>
        <div class="card-body">
            <div class="row">

                <div class="col-12">

                    <div id="chunks-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        var colors = @json($colors);
        var $negativeChunks = `rgb${colors.negative}`;
        var $positiveChunks = `rgb${colors.positive}`;
        var $neutralChunks = `rgb${colors.neutral}`;

        var flatPicker = $('.flat-picker'),
            isRtl = $('html').attr('data-textdirection') === 'rtl',
            chartColors = {
                column: {
                    series1: '#826af9',
                    series2: '#d2b0ff',
                    bg: '#fafafa'
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
        var columnChartEl = document.querySelector('#chunks-chart'),
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
                colors: [$positiveChunks, $negativeChunks, $neutralChunks],
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
                    @foreach ($categoryChartData as $key => $category)
                        {
                            name: "{{ $key }}",
                            data: [...@json($category['data'])]
                        },
                    @endforeach

                ],
                xaxis: {
                    categories: [
                        @foreach (!empty($chunksChartData) ? $chunksChartData['positive']['name'] : [] as $key => $value)
                            "{{ $value }}",
                        @endforeach
                    ]
                },
                fill: {
                    opacity: 1
                },

                yaxis: {
                    opposite: isRtl,
                    labels: {

                        formatter: function(val) {

                        return  new Intl.NumberFormat().format(val);


                        }
                    }
                }
            };
        if (typeof columnChartEl !== undefined && columnChartEl !== null) {
            var columnChart = new ApexCharts(columnChartEl, columnChartConfig);
            columnChart.render();
        }
    </script>
@endpush
