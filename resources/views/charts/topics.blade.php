<div class="col-lg-12 col-md-6 col-12">
    <div class="card earnings-card">
        <div class="card-body">
            <div class="row">

                <div class="col-12 revenue-report-wrapper">
                    <div class="d-sm-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-50 mb-sm-0">Topics</h4>
                        <div class="d-flex align-items-center">

                            <div class="d-flex align-items-center me-2 ">
                                <span class="bullet bullet-warning font-small-3 me-50 cursor-pointer" style="
    background-color: rgb(147 250 165);
"></span>
                                <span>Positive</span>
                            </div>
                            <div class="d-flex align-items-center ms-75">
                                <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer" style="
    background-color: rgb(255 76 48);"></span>
                                <span>Negative</span>
                            </div>

                        </div>
                        <div class="d-flex">
                            <div class="mb-1">
                                <select class="select2 form-select select2-hidden-accessible" id="category">
                                    <option value="all">Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->c_id }}">{{ $category->c_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <div id="revenue-report-chart2"></div>
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
                height: 230,
                stacked: true,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '10%',
                    endingShape: 'rounded'
                },
                distributed: true
            },
            colors: [$positiveChunks, $negativeChunks],
            series: [{
                    name: 'Positive',
                    data: [...@json($topicPositive)]
                },
                {
                    name: 'Negative',
                    data: [...@json($topicNegative)]
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
                        colors: $positiveChunks,
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
                        colors: $positiveChunks,
                        fontSize: '0.86rem'
                    },
                    formatter: function(val) {
                        return parseInt(Math.abs(val));
                    }
                }
            }
        };
        revenueReportChart = new ApexCharts($revenueReportChart, revenueReportChartOptions);
        revenueReportChart.render();

        // Category
        // ----------------------------------
        $('#category').on('change', function() {
            var $category = document.querySelector('#category');
            var routeName = "{{ route('charts.topics.category') }}";
            $.ajax({
                url: routeName,
                type: "GET",
                dataType: "json",
                data: {
                    category: $category.value
                },
                success: function(data) {

                   let names = [];
                   for(topic in data.topics){
                       names.push(data.topics[topic].t_name)
                   }

                    revenueReportChart.updateSeries([{
                            name: 'Positive',
                            data: [...data.topicPositive]
                        },
                        {
                            name: 'Negative',
                            data: [...data.topicNegative]
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
