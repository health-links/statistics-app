<div class="row">

    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
        <div class="d-flex flex-row">
            <div class="avatar bg-light-primary me-2">
                <div class="avatar-content">
                    <i data-feather="thumbs-down" onclick="getOverall('negative')" data-bs-toggle="modal"
                        data-bs-target="#overallModal" class="avatar-icon"></i>
                </div>
            </div>
            <div class="my-auto">
                <h4 class="fw-bolder mb-0">{{ number_format($overAllComments->negative) ?? 0 }}</h4>
                <p class="card-text font-small-3 mb-0">Negative</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
        <div class="d-flex flex-row">
            <div class="avatar bg-light-info me-2">
                <div class="avatar-content">
                    <i data-feather="smile" onclick="getOverall('positive')" data-bs-toggle="modal"
                        data-bs-target="#overallModal" class="avatar-icon"></i>
                </div>
            </div>
            <div class="my-auto">
                <h4 class="fw-bolder mb-0">{{ number_format($overAllComments->positive) ?? 0 }}</h4>
                <p class="card-text font-small-3 mb-0">Positive</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
        <div class="d-flex flex-row">
            <div class="avatar bg-light-danger me-2">
                <div class="avatar-content">
                    <i data-feather="meh" onclick="getOverall('neutral')" data-bs-toggle="modal"
                        data-bs-target="#overallModal" class="avatar-icon"></i>
                </div>
            </div>
            <div class="my-auto">
                <h4 class="fw-bolder mb-0">{{ number_format($overAllComments->neutral) ?? 0 }}</h4>
                <p class="card-text font-small-3 mb-0">Neutral</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="d-flex flex-row">
            <div class="avatar bg-light-success me-2">
                <div class="avatar-content">
                    <i data-feather="bar-chart-2" onclick="getOverall('mixed')" data-bs-toggle="modal"
                        data-bs-target="#overallModal" class="avatar-icon"></i>
                </div>
            </div>
            <div class="my-auto">
                <h4 class="fw-bolder mb-0">{{ number_format($overAllComments->mixed) ?? 0 }}</h4>
                <p class="card-text font-small-3 mb-0">Mixed</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="overallModal" tabindex="-1" aria-labelledby="overallModalTitle" aria-modal="true"
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
                                        <table class="datatables-basic2 table" id="comment_type">
                                            <thead>
                                                <tr>

                                                    <th>Comment</th>
                                                    <th>Rate</th>
                                                    <th>Topics</th>
                                                    <th>Categories</th>
                                                    <th>Bookmark Icon</th>

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
            function getOverall(type) {
                var query = @json(request()->query());
                var url = '';
                if (query.length !== 0) {
                    var filter = query.filter;
                    url = "{{ route('comments.types') }}" + "?" +
                        `filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}`;
                } else {
                    url = "{{ route('comments.types') }}";
                }
                var comment_table = $('#comment_type')
                 comment_table.DataTable().destroy();
                var table = comment_table.DataTable({
                    "ajax": {
                        "url": url,
                        "type": "GET",
                        "data": {
                            type: type
                        }
                    },
                    columns: [
                        {
                            data: 'comment'
                        },
                        {
                            data: 'r_rate'
                        },
                        {
                            data: ''
                        },
                        {
                            data: 'categories'
                        },
                        {
                            data: 'client'
                        },
                    ],
                    columnDefs: [{
                            targets: [2],
                            render: function(data, type, row, meta) {
                                var topics = row.topics;
                                var html = '';
                                topics.map(function(topic) {
                                    html = '<b style="color:#333">' + topic.t_name +
                                        '</b>: ' + topic.t_type;
                                })
                                return html;
                            },
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
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                                $(node).parent().removeClass('btn-group');
                                setTimeout(function() {
                                    $(node).closest('.dt-buttons').removeClass('btn-group')
                                        .addClass(
                                            'd-inline-flex');
                                }, 50);
                            }
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
        </script>
    @endpush
