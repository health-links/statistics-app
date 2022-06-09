 <section id="basic-datatable">
     <div class="row">
         <div class="col-12">
             <div class="card">
                 <table class="datatables-basic2 table">
                     <thead>
                         <tr>
                             <th>id</th>
                             <th>Comment</th>
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

 @push('js')
     <script>
         $(document).ready(function() {
             tableData();
             $('.datatables-basic2').DataTable().ajax.url("{{ route('comments.table') }}").load();
         });
         // when  window load



         function tableData() {
             var dt_basic_table = $('.datatables-basic2');
             if (dt_basic_table.length) {
                 var dt_basic = dt_basic_table.DataTable({
                     "ajax": {
                         "url": "{{ route('comments.table') }}",
                         "type": "GET"
                     },
                     columns: [{
                             data: 'id'
                         }, // used for sorting so will hide this column
                         {
                             data: 'comment'
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



         }
     </script>
 @endpush
