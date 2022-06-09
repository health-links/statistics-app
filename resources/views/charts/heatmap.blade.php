<div class="col-lg-12 col-md-6 col-12">
    <div class="card earnings-card">
        <div class="card-header">
            <h6>Topics Heatmap</h6>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-12">

                    <div id="heatmap-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        $(function() {
            'use strict';
            var heatmapEl = document.querySelector('#heatmap-chart'),
                heatmapChartConfig = {
                    chart: {
                        height: 500,
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
                                        color:"#36bbc173"

                                    },
                                    {
                                        from: 11,
                                        to: 20,
                                        name: '10-20',
                                        color: '#2facb1'
                                    },
                                    {
                                        from: 21,
                                        to: 30,
                                        name: '20-30',
                                        color: '#2ba6ab'
                                    },
                                    {
                                        from: 31,
                                        to: 40,
                                        name: '30-40',
                                        color: '#218589'
                                    },
                                    {
                                        from: 41,
                                        to: 50,
                                        name: '40-50',
                                        color: '#196e71'
                                    },
                                    {
                                        from: 51,
                                        to: 60,
                                        name: '50-60',
                                        color: '#36bbc1'
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
                    // categories name
                    series: [

                        @foreach ($heatmapData as $key => $category)
                            {
                                name: '{{ $key }}',
                                data: [
                                    @foreach ($category as $key => $count)
                                        {
                                            x: '{{ $key }}',
                                            y: {{ $count }}
                                        },
                                    @endforeach
                                ]
                            },
                        @endforeach


                    ],
                    xaxis: {
                        labels: {
                            show: true
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
@endpush
