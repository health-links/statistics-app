<div class="card earnings-card dount">
    <div class="card-header">
        <h6>Chunks Pie Chart</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 flex">

                <div id="donut-chart"></div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        function decimalAdjust(type, value, exp) {
            // If the exp is undefined or zero...
            if (typeof exp === 'undefined' || +exp === 0) {
                return Math[type](value);
            }
            value = +value;
            exp = +exp;
            // If the value is not a number or the exp is not an integer...
            if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
                return NaN;
            }
            // Shift
            value = value.toString().split('e');
            value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
            // Shift back
            value = value.toString().split('e');
            return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
        }
    </script>
    <script>
        const round10 = (value, exp) => decimalAdjust('round', value, exp);
        var colors = @json($colors);
        var $negativeChunks = `rgb${colors.negative}`;
        var $positiveChunks = `rgb${colors.positive}`;
        var $neutralChunks = `rgb${colors.neutral}`;
        var flatPicker = $('.flat-picker'),
            isRtl = $('html').attr('data-textdirection') === 'rtl';

        var donutChartEl = document.querySelector('#donut-chart'),
            donutChartConfig = {
                chart: {
                    height: 300,
                    type: 'donut'
                },
                legend: {
                    show: true,
                    position: 'bottom'
                },
                labels: ['Negative', 'Positive', 'Neutral'],
                series: [{{ $negativeChunks ?? 0 }}, {{ $positiveChunks ?? 0 }},
                    {{ $neutralChunks ?? 0 }},

                ],
                colors: [$negativeChunks, $positiveChunks, $neutralChunks],
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opt) {
                        return round10(val, -1) + '%';
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
                                        return round10({{ $chunksCount > 0 }} ? val / {{ $chunksCount }} * 100 : 0,
                                            -
                                            1) + '%';

                                    }
                                },
                                total: {
                                    show: true,
                                    fontSize: '1.5rem',
                                    label: 'Negative',
                                    formatter: function(w) {
                                        return round10(
                                            {{ $chunksCount > 0 ? ($negativeChunks / $chunksCount) * 100 : 0 }}, -
                                            1) + '%';

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
@endpush
