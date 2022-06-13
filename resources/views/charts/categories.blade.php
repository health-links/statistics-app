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
                        "{{ $value }}",
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
