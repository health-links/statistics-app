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
<div class="modal fade show" id="chunkModal" tabindex="-1" aria-labelledby="pricingModalTitle" aria-modal="true"
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
                                        <table class="datatables-basic2 table" id="chunk_comments">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Comment</th>
                                                    <th>Chunk Rate</th>
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
        var ddd = (i) => getData(i);
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
                    type: 'donut',
                    events: {
                        dataPointSelection: (event, chartContext, config) => {
                            var index = config.dataPointIndex;
                            types = ['negative', 'positive', 'neutral']
                            var query = @json(request()->query());
                            var url = '';
                            if (query.length !== 0) {
                                var filter = query.filter;
                                url = "{{ route('comments.chunks') }}" + "?" +
                                    `filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}`;
                            } else {
                                url = "{{ route('comments.chunks') }}";
                            }
                            $('#chunk_comments').DataTable().destroy();
                            $('#chunkModal').modal('show');
                            var comment_table = $('#chunk_comments')
                            var table = comment_table.DataTable({
                                retrieve: true,
                                "ajax": {
                                    "url": url,
                                    "type": "GET",
                                    "data": {
                                        type: types[index]
                                    }
                                },
                                columns: [{
                                        data: 'sn_id'
                                    },
                                    {
                                        data: 'sn_comment'
                                    },
                                    {
                                        data: 'ch_rate'
                                    },

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
