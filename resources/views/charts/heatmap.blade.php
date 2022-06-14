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
<div class="modal fade show" id="heatmapModal" tabindex="-1" aria-labelledby="pricingModalTitle" aria-modal="true"
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
                                        <table class="datatables-basic2 table" id="heatmap_comments">
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
                        },
                        events: {
                        dataPointSelection: (event, chartContext, config) => {
                            var category = chartContext.w.config.series[config.seriesIndex].name;
                            var topic = chartContext.w.globals.labels[config.dataPointIndex];

                            var query = @json(request()->query());
                            var url = '';
                            if (query.length !== 0) {
                                var filter = query.filter;
                                url = "{{ route('comments.heatmap') }}" + "?" +
                                    `filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}`;
                            } else {
                                url = "{{ route('comments.heatmap') }}";
                            }
                            $('#heatmap_comments').DataTable().destroy();
                            $('#heatmapModal').modal('show');
                            var comment_table = $('#heatmap_comments')
                            var table = comment_table.DataTable({
                                retrieve: true,
                                "ajax": {
                                    "url": url,
                                    "type": "GET",
                                    "data": {
                                        topic: topic,
                                        category: category,
                                    }
                                },
                                columns: [
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

                                name: '{!! $key !!}',
                                data: [
                                    @foreach ($category as $key => $count)
                                        {
                                            x: '{!! $key !!}',
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
