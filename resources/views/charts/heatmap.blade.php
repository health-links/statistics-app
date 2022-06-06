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
        // Heat map chart
        // --------------------------------------------------------------------

        $(function() {
            'use strict';

            function generateDataHeat(count, yrange) {
                var i = 0;
                var series = [];
                while (i < count) {
                    var x = 'Topic ' + (i + 1).toString();
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
                            name: 'Access',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'Admission',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'Arrival',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'Doctor',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'Lab',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'Meals',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        },
                        {
                            name: 'Nurse',
                            data: generateDataHeat(24, {
                                min: 0,
                                max: 60
                            })
                        }
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
