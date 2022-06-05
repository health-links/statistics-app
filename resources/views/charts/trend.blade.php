<div class="col-lg-12 col-md-12 col-12">
    <div class="card earnings-card">
        <div class="card-body">
            <div
                class="
            card-header
            d-flex
            flex-md-row flex-column
            justify-content-md-between justify-content-start
            align-items-md-center align-items-start
          ">
                <h4 class="card-title">Trend</h4>
                <div class="btn-group mt-md-0 mt-1" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="radio_options" id="monthly" autocomplete="off"
                        checked="">
                    <label class="btn btn-outline-primary waves-effect" for="monthly">Monthly</label>

                    <input type="radio" class="btn-check" name="radio_options" id="quarterly" autocomplete="off">
                    <label class="btn btn-outline-primary waves-effect" for="quarterly">Quarterly</label>

                    <input type="radio" class="btn-check" name="radio_options" id="yearly" autocomplete="off">
                    <label class="btn btn-outline-primary waves-effect" for="yearly">Yearly</label>
                </div>
            </div>
            <div class="row">

                <div class="col-12">

                    <div id="trend-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        var colors = @json($colors);
        var $negativeColor = `rgb${colors.negative}`;
        var $positiveColor = `rgb${colors.positive}`;
        var $neutralColor = `rgb${colors.neutral}`;

        var flatPicker = $('.flat-picker'),
            isRtl = $('html').attr('data-textdirection') === 'rtl';
        var areaChartEl = document.querySelector('#trend-chart'),
            areaChartConfig = {
                chart: {
                    height: 400,
                    type: 'area',
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: false,
                    curve: 'straight'
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'start'
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                colors: [ @foreach ($trendChartData as $key => $value)

                        `{{ $trendChartData[$key]['color'] }}`,
                    @endforeach
                ],

                series: [
                    @foreach ($trendChartData as $key => $value)
                        {
                            name: '{{ $key }}',
                            data: [...@json($value['data'])]
                        },
                    @endforeach

                ],
                xaxis: {
                    categories: [
                        @foreach ($trendChartData as $key => $value)
                            @foreach ($value['categories'] as $item)
                                `{{ $item }}`,
                            @endforeach
                        @endforeach
                    ]
                },
                fill: {
                    opacity: 1,
                    type: 'solid'
                },
                tooltip: {
                    shared: false
                },
                yaxis: {
                    opposite: isRtl,
                    labels: {
                        formatter: function (val) {
                            return new Intl.NumberFormat().format(val);
                        }
                    }
                }
            };


        if (typeof areaChartEl !== undefined && areaChartEl !== null) {
            var areaChart = new ApexCharts(areaChartEl, areaChartConfig);
            areaChart.render();

            $('#monthly').on('click', function() {
                getData('monthly');
            });
            $('#quarterly').on('click', function() {
                getData('quarterly');
            });
            $('#yearly').on('click', function() {
                getData('yearly');
            });
        }

        function getData(url) {
            var routeName = "";
            if (url == 'quarterly') {
                routeName = "{{ route('chart.quarterly') }}";
            } else if (url == 'yearly') {
                routeName = "{{ route('chart.yearly') }}";
            } else {
                routeName = "{{ route('chart.monthly') }}";
            }


            $.ajax({
                url: routeName,
                type: "GET",
                dataType: "json",

                success: function(data) {
                    serData = [];
                    colors = []
                    // object foreach key value in js
                    for (var key in data) {
                        colors.push(data[key].color);
                        serData.push({
                            name: key,
                            data: data[key].data,

                        });
                    }
                    areaChart.updateSeries(serData);
                    areaChart.updateOptions({
                        colors: [
                            ...colors
                        ],
                        xaxis: {
                            categories: [...data['positive']['categories']]
                        }
                    });

                }
            });
        }
    </script>
@endpush
