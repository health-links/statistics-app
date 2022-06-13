 <section id="basic-datatable">
     <div class="row">
         <div class="col-12">

             <div class="card">
                 <table class="datatables-basic2 table" id="comment_table">
                     <thead>
                         <tr>
                             <th>Comment</th>
                             <th>Insights</th>
                             <th>Categories</th>
                             <th>Bookmark Icon</th>
                             <th>Flag Icon</th>
                         </tr>
                     </thead>
                 </table>

             </div>
         </div>
     </div>
 </section>

 @push('js')
     <script>
         var query = @json(request()->query());
         var url = '';
         if (query.length !== 0) {
             var filter = query.filter;
             url = "{{ route('comments.table') }}" + "?" +
                 `filter[client_id]=${filter.client_id}&filter[service_id]=${filter.service_id ??''}&filter[from]=${filter.from??''}&filter[to]=${filter.to??''}`;
         } else {
             url = "{{ route('comments.table') }}";
         }

         var comment_table = $('#comment_table')
         var table = comment_table.DataTable({
             "ajax": {
                 "url": url,
                 "type": "GET"
             },
             columns: [{
                     data: 'comment'
                 },
                 {
                     data: ''
                 },
                 {
                     data: 'categories'
                 },
                 {
                     data: ''
                 },
                 {
                     data: ''
                 },
             ],
             columnDefs: [{
                     targets: [1],
                     render: function(data, type, row, meta) {
                         var topics = row.topics;
                         var html = '';
                         topics.map(function(topic) {
                             html = '<b style="color:#333">' + topic.t_name +
                                 '</b>: ' + topic.t_type;
                         })
                         return html;
                     },
                 },
                 {
                     targets: [-2],
                     title: 'Bookmark Icon',
                     render: function(data, type, row, meta) {
                         var html = '';
                         if (row.bookmark == 0) {
                             html = "<a  href='javascript:void(0)' id='bookmark-" + row.id +
                                 "'onClick='updateBookmark(" + row.id +
                                 ")'>" + "<i class='fa-regular fa-bookmark'></i>" + "</a>" +
                                 "<span class='spinner-border text-secondary d-none'  role='status'  id='bookspinner-" +
                                 row.id +
                                 "'>" + "</span>";

                         } else {
                             html = "<a  href='javascript:void(0)' disabled id='bookmark-" + row.id +
                                 "'onClick='updateBookmark(" + row.id +
                                 ")'><i class='fa-solid fa-bookmark'></i></a>" +
                                 "<span class='spinner-border text-secondary d-none'  role='status'  id='bookspinner-" +
                                 row.id +
                                 "'>" + "</span>";

                         }
                         return html;
                     },
                 },
                 {
                     targets: [-1],
                     render: function(data, type, row, meta) {

                         var html = '';
                         if (row.flag == 0) {
                             html = "<a  href='javascript:void(0)' id='flag-" + row.id +
                                 "'onClick='updateFlag(" + row.id +
                                 ")'>" + "<i class='fa-regular fa-flag'></i>" + "</a>" +
                                 "<span class='spinner-border text-secondary d-none'  role='status'  id='flagspinner-" +
                                 row.id +
                                 "'>" + "</span>";
                         } else {
                             html = "<a  href='javascript:void(0)' disabled id='flag-" + row.id +
                                 "'onClick='updateFlag(" + row.id +
                                 ")'><i class='fa-solid fa-flag'></i></a>" +
                                 "<span class='spinner-border text-secondary d-none'  role='status'  id='flagspinner-" +
                                 row.id +
                                 "'>" + "</span>";
                         }
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


         function updateBookmark(id) {
             $('#bookmark-' + id).addClass('d-none');
             $('#bookspinner-' + id).removeClass('d-none');
             var url = "{{ route('comments.updateBookmark') }}";
             $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });
             $.ajax({
                 url: url,
                 type: "POST",
                 data: {
                     id: id
                 },
                 success: function(data) {

                     if (data.status == 'success') {
                         toastr.success(data.message);
                         $('#bookmark-' + id).removeClass('d-none');
                         $('#comment_table').DataTable().ajax.reload();
                         $('#bookspinner-' + id).addClass('d-none');

                     } else {
                         toastr.error(data.message);
                         $('#bookmark-' + id).removeClass('d-none');
                         $('#bookspinner-' + id).addClass('d-none');
                     }
                 }
             });
         }

         function updateFlag(id) {

             $('#flag-' + id).addClass('d-none');
             $('#flagspinner-' + id).removeClass('d-none');
             var url = "{{ route('comments.updateFlag') }}";
             var data = {
                 id: id
             };
             $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });
             $.ajax({
                 url: url,
                 type: "POST",
                 data: data,
                 success: function(data) {
                     if (data.status == 'success') {
                         toastr.success(data.message);
                         $('#flag-' + id).removeClass('d-none');
                         $('#comment_table').DataTable().ajax.reload();
                         $('#flagspinner-' + id).addClass('d-none');

                     } else {
                         toastr.error(data.message);
                         $('#flag-' + id).removeClass('d-none');
                         $('#flagspinner-' + id).addClass('d-none');
                     }
                 }
             });
         }
     </script>
 @endpush
