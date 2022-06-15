<div class="col-lg-12 col-md-6 col-12">
    <div class="card earnings-card">
        <div class="card-header">
            <div class="col-12 revenue-report-wrapper">
                <div class="d-sm-flex justify-content-between align-items-center">
                    <h6 class="heading-card">Topics</h6>
                    <div class="d-flex align-items-center">

                        <div class="d-flex align-items-center me-2 ">
                            <span class="bullet bullet-warning font-small-3 me-50 cursor-pointer" style="
    background-color: rgb(255 76 48);
"></span>
                            <span class="apexcharts-legend-text">Positive</span>
                        </div>
                        <div class="d-flex align-items-center ms-75">
                            <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer" style="
    background-color: rgb(147 250 165);"></span>
                            <span class="apexcharts-legend-text">Negative</span>
                        </div>

                    </div>
                    <div class="d-flex">
                        <div class="mb-1">
                            <select class="select2 form-select select2-hidden-accessible" id="category">
                                <option value="all">Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->c_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-12 revenue-report-wrapper">


                    <div id="revenue-report-chart2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="topicModal" tabindex="-1" aria-labelledby="pricingModalTitle" aria-modal="true"
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
                                        <table class="datatables-basic2 table" id="topic_comments">
                                            <thead>
                                                <tr>
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
        var $negativeChunks = `rgb${colors.negative}`;
        var $positiveChunks = `rgb${colors.positive}`;

        var revenueReportChartOptions;
        var $revenueReportChart = document.querySelector('#revenue-report-chart2');
        // Revenue Report Chart
        // ----------------------------------
        revenueReportChartOptions = {
            chart: {
                height: 330,
                stacked: true,
                type: 'bar',

                toolbar: {
                    show: false
                },
                events: {
                    dataPointSelection: (event, chartContext, config) => {

                        var type = chartContext.w.config.series[config.seriesIndex].name;
                        var topic = chartContext.w.globals.labels[config.dataPointIndex];
                        console.log(type, topic);
                        var query = @json(request()->query());
                        var $category = $('#category option').filter(':selected').val();

                        var url = '';
                        if (query.length !== 0 && query.category !== null) {
                            var filter = query.filter;
                            if ($category !== 'all') {
                                var seq =
                                    `type=${type}&topic=${topic}&filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}&filter[category]=${$category??''}`
                            } else {
                                var seq =
                                    `type=${type}&topic=${topic}&filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}`
                            }
                            url = "{{ route('comments.topic') }}" + "?" + seq;
                        } else if ($category !== null && $category !== 'all') {
                            url = "{{ route('comments.topic') }}" + "?" +
                                `type=${type}&topic=${topic}&filter[category]=${$category??''}`;
                        } else {
                            url = "{{ route('comments.topic') }}" + "?" + `type=${type}&topic=${topic}`;
                        }
                        $('#topic_comments').DataTable().destroy();
                        $('#topicModal').modal('show');
                        var comment_table = $('#topic_comments')
                        var table = comment_table.DataTable({
                            retrieve: true,
                            "ajax": {
                                "url": url,
                                "type": "GET",

                            },
                            columns: [{
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

            plotOptions: {
                bar: {
                    columnWidth: '40%'
                },
                distributed: true
            },
            colors: [$negativeChunks, $positiveChunks],
            series: [{
                    name: 'Negative',
                    data: [...@json($topicNegative)]
                },
                {
                    name: 'Positive',
                    data: [...@json($topicPositive)]
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
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            xaxis: {
                // topics
                categories: [
                    @foreach ($topics as $key => $topic)
                        '{{ $topic->t_name }}',
                    @endforeach
                ],
                labels: {
                    style: {
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
                        fontSize: '0.86rem'
                    },
                    formatter: function(val) {
                        return new Intl.NumberFormat().format(parseInt(Math.abs(val)));
                    }
                }
            },
            fill:{
                type:'solid',
                colors: [$positiveChunks,$negativeChunks],
                opacity: 0.2
            }
        };
        revenueReportChart = new ApexCharts($revenueReportChart, revenueReportChartOptions);
        revenueReportChart.render();

        // Category
        // ----------------------------------
        $('#category').on('change', function() {
            var $category = $('#category').val();
            var query = @json(request()->query());
            var url = '';
            if (query.length !== 0) {
                url = url = "{{ route('charts.topics.category') }}" + "?" +
                    `filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}&filter[category]=${$category??''}`
            } else {
                url = "{{ route('charts.topics.category') }}" + "?" + `filter[category]=${$category??''}`;
            }


            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {

                    let names = [];
                    for (topic in data.topics) {
                        names.push(data.topics[topic].t_name)
                    }

                    revenueReportChart.updateSeries([{
                            name: 'Negative',
                            data: [...data.topicNegative]
                        },
                        {
                            name: 'Positive',
                            data: [...data.topicPositive]
                        }

                    ]);
                    revenueReportChart.updateOptions({
                        xaxis: {
                            categories: [...names]
                        }
                    })



                }
            });
        });
    </script>
@endpush
