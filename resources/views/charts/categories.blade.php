<div class="col-lg-12 col-md-12 col-12">
    <div class="card earnings-card">
        <div class="card-header">
            <h6 class="heading-card">Categories</h6>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-12">

                    <div id="bar-chart2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="categoryModal" tabindex="-1" aria-labelledby="pricingModalTitle" aria-modal="true"
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
                                        <table class="datatables-basic2 table" id="category_comments">
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
        var $neutralChunks = `rgb${colors.neutral}`;
        var options = {

            series: [
                @foreach ($categoryChartData as $key => $category)
                    {
                        name: "{{ $key }}",
                        data: [...@json($category['data'])]
                    },
                @endforeach
            ],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: false
                },
                events: {
                    dataPointSelection: (event, chartContext, config) => {
                        var type = chartContext.w.config.series[config.seriesIndex].name;
                        var category = chartContext.w.globals.labels[config.dataPointIndex];
                        var query = @json(request()->query());
                        var url = '';
                        if (query.length !== 0) {
                            var filter = query.filter;
                            url = "{{ route('comments.category') }}" + "?" +
                                `filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}`;
                        } else {
                            url = "{{ route('comments.category') }}";
                        }
                        $('#category_comments').DataTable().destroy();
                        $('#categoryModal').modal('show');
                        var comment_table = $('#category_comments')
                        var table = comment_table.DataTable({
                            retrieve: true,
                            "ajax": {
                                "url": url,
                                "type": "GET",
                                "data": {
                                    type: type,
                                    category: category,

                                }
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
                    horizontal: true,
                    barHeight: '60%',

                },
            },
            colors: [$positiveChunks, $negativeChunks, $neutralChunks],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories: [
                    @foreach (!empty($categoryChartData) ? $categoryChartData['positive']['name'] : [] as $key => $value)
                        "{!! $value !!}",
                    @endforeach
                ]

            },
            yaxis: {
                title: {
                    text: undefined
                },
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return round10(val, -1)
                    }
                }
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                offsetX: 40
            }
        };

        var chart = new ApexCharts(document.querySelector("#bar-chart2"), options);
        chart.render();
    </script>
@endpush
