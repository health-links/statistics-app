 <div class="col-lg-12 col-md-6 col-12">
     <div class="card earnings-card">
         <div class="card-body">
             <div class="row">

                 <div class="col-12">
                     <div>
                         <h2>Chunks Pie Chart</h2>
                     </div>
                     <div id="donut-chart"></div>
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
         var $mixedChunks = `rgb${colors.mixed}`;
         var flatPicker = $('.flat-picker'),
             isRtl = $('html').attr('data-textdirection') === 'rtl';

         var donutChartEl = document.querySelector('#donut-chart'),
             donutChartConfig = {
                 chart: {
                     height: 350,
                     type: 'donut'
                 },
                 legend: {
                     show: true,
                     position: 'bottom'
                 },
                 labels: ['negative', 'positive', 'neutral', 'mixed'],
                 series: [{{ $negativeChunks->count ?? 0 }}, {{ $positiveChunks->count ?? 0 }},
                     {{ $neutralChunks->count ?? 0 }},
                     {{ $mixedChunks->count ?? 0 }}
                 ],
                 colors: [$positiveChunks, $negativeChunks, $neutralChunks, $mixedChunks],
                 dataLabels: {
                     enabled: true,
                     formatter: function(val, opt) {
                         return parseInt(val) + '%';
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
                                         return Math.round({{ $chunksCount }} ? (Math.round(val /
                                             {{ $chunksCount }} * 100)) : 0).toFixed(2) + '%';

                                     }
                                 },
                                 total: {
                                     show: true,
                                     fontSize: '1.5rem',
                                     label: 'negative',
                                     formatter: function(w) {

                                         return Math.round(
                                             {{ $chunksCount > 0 ? ($positiveChunks->count / $chunksCount) * 100 : 0 }}
                                         ).toFixed(2) + '%';
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
