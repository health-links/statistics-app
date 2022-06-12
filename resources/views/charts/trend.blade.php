<div class="card earnings-card">
    <div class="card-header trendheader">
        <h6 class="card-title" style="float:left">Trend</h6>
        <div class="btnsWrap">
            <div
                class="d-flex flex-md-row flex-column justify-content-md-between justify-content-start align-items-md-center align-items-start">
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
        </div>
    </div>
    <div class="card-body">

        <div class="row">

            <div class="col-12">

                <div id="trend-chart"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="trendModal" tabindex="-1" aria-labelledby="pricingModalTitle" aria-modal="true"
    role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-5">
                <div id="pricing-plan">
                    <div class="row pricing-card">
                        <section id="basic-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <table class="datatables-basic2 table" id="trend_comments">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Comment</th>
                                                    <th>Comment Rate</th>
                                                    <th>Month</th>
                                                    <th>Quarter</th>
                                                    <th>Year</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
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
        var $period = 'monthly';
        var flatPicker = $('.flat-picker'),
            isRtl = $('html').attr('data-textdirection') === 'rtl';
        var areaChartEl = document.querySelector('#trend-chart'),
            areaChartConfig = {
                chart: {
                    height: 300,
                    type: 'area',
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false
                    },
                    events: {
                        markerClick: (event, chartContext, config) => {

                            var type = chartContext.w.config.series[config.seriesIndex].name;
                            var date = chartContext.w.globals.categoryLabels[config.dataPointIndex];
                            var query = @json(request()->query());
                            var url = '';
                            if (query.length !== 0) {
                                var filter = query.filter;
                                url = "{{ route('comments.trend') }}" + "?" +
                                    `filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}`;
                            } else {
                                url = "{{ route('comments.trend') }}";
                            }
                            $('#trend_comments').DataTable().destroy();
                            $('#trendModal').modal('show');
                            var comment_table = $('#trend_comments')
                            var table = comment_table.DataTable({
                                retrieve: true,
                                "ajax": {
                                    "url": url,
                                    "type": "GET",
                                    "data": {
                                        type: type,
                                        date: date,
                                        period: $period
                                    }
                                },
                                columns: [{
                                        data: 'sn_id'
                                    },
                                    {
                                        data: 'sn_comment'
                                    },
                                    {
                                        data: 'r_rate'
                                    },
                                    {
                                        data: 'sn_month'
                                    },
                                    {
                                        data: 'sn_quarter'
                                    },
                                    {
                                        data: 'sn_year'
                                    }

                                ],
                                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                                displayLength: 10,
                                lengthMenu: [10, 15, 25, 50, 75, 100],
                                buttons: [{
                                        extend: 'collection',
                                        className: 'btn btn-outline-secondary dropdown-toggle me-2',
                                        text: feather.icons['share'].toSvg({
                                            class: 'font-small-4 me-50'
                                        }) + 'Export',
                                        buttons: [{
                                                extend: 'print',
                                                text: feather.icons['printer'].toSvg({
                                                    class: 'font-small-4 me-50'
                                                }) + 'Print',
                                                className: 'dropdown-item',
                                                exportOptions: {
                                                    columns: [3, 4, 5, 6, 7]
                                                }
                                            },
                                            {
                                                extend: 'csv',
                                                text: feather.icons['file-text'].toSvg({
                                                    class: 'font-small-4 me-50'
                                                }) + 'Csv',
                                                className: 'dropdown-item',
                                                exportOptions: {
                                                    columns: [3, 4, 5, 6, 7]
                                                }
                                            },
                                            {
                                                extend: 'excel',
                                                text: feather.icons['file'].toSvg({
                                                    class: 'font-small-4 me-50'
                                                }) + 'Excel',
                                                className: 'dropdown-item',
                                                exportOptions: {
                                                    columns: [3, 4, 5, 6, 7]
                                                }
                                            },
                                            {
                                                extend: 'pdf',
                                                text: feather.icons['clipboard'].toSvg({
                                                    class: 'font-small-4 me-50'
                                                }) + 'Pdf',
                                                className: 'dropdown-item',
                                                exportOptions: {
                                                    columns: [3, 4, 5, 6, 7]
                                                }
                                            },
                                            {
                                                extend: 'copy',
                                                text: feather.icons['copy'].toSvg({
                                                    class: 'font-small-4 me-50'
                                                }) + 'Copy',
                                                className: 'dropdown-item',
                                                exportOptions: {
                                                    columns: [3, 4, 5, 6, 7]
                                                }
                                            }
                                        ],

                                    },

                                ],

                                language: {
                                    paginate: {
                                        // remove previous & next text from pagination
                                        previous: '&nbsp;',
                                        next: '&nbsp;'
                                    }
                                }
                            });

                        }

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
                colors: [
                    @foreach ($trendChartData as $key => $value)

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

                        ...(@json($trendChartData['positive']['categories']))
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
                        formatter: function(val) {
                            return val == undefined || val == NaN ? 0 : new Intl.NumberFormat().format(val);
                        }
                    }
                }
            };
        if (typeof areaChartEl !== undefined && areaChartEl !== null) {
            var areaChart = new ApexCharts(areaChartEl, areaChartConfig);
            areaChart.render();

            $('#monthly').on('click', function() {
                $period = 'monthly';
                getData('monthly');
            });
            $('#quarterly').on('click', function() {
                $period = 'quarterly';
                getData('quarterly');
            });
            $('#yearly').on('click', function() {
                $period = 'yearly';
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
            var query = @json(request()->query());

            if (query.length !== 0) {
                var filter = query.filter;
                routeName += "?" +
                    `filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}`;
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
