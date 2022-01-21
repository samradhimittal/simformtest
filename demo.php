<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Users</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
      <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
      <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
      <link href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" rel="stylesheet">
      
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
      <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
      <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
      <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
      <script src="/vendor/datatables/buttons.server-side.js"></script>
   </head>
   <body>
      <div class="container mt-2">
         <div class="row">
            <div class="col-lg-12 margin-tb">
               <div class="pull-left">
                  <h2>User List</h2>
               </div>
               <div class="pull-right mb-2">
                  <a class="btn btn-success" onClick="add()" href="javascript:void(0)"> Add User</a>
               </div>
            </div>
         </div>
         @if ($message = Session::get('success'))
         <div class="alert alert-success">
            <p>{{ $message }}</p>
         </div>
         @endif
        <div class="card-body">
            <table class="table table-bordered" id="ajax-crud-datatable">
               <thead>
                  <tr>
                     <th>Id</th>
                     <th>First Name</th>
                     <th>Last Name</th>
                     <th>Email</th>
                     <th>Type</th>
                     <th>Amount</th>
                     <td>Date</td>
                     <th>Created At</th>
                     <th>Action</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
      <!-- boostrap user model -->
      <div class="modal fade" id="user-modal" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title" id="UserModel"></h4>
               </div>
               <div class="modal-body">
                  @include('auth.register')
               </div>
               <div class="modal-footer"></div>
            </div>
         </div>
      </div>
      <!-- end bootstrap model -->

      <!-- boostrap user model -->
      <div class="modal fade" id="edit-user-modal" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title" id="editUserModel">Edit User</h4>
               </div>
               <div class="modal-body" id="editUserDetail">
                 
               </div>
            </div>
         </div>
      </div>
      <!-- end bootstrap model -->
   </body>
   
   <script type="text/javascript">
      $(document).ready( function () {
      $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $('#ajax-crud-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('users') }}",
                columns: [
                        { data: 'id', name: 'id' },                      
                        { data: 'first_name', name: 'first_name' },
                        { data: 'last_name', name: 'last_name' },
                        { data: 'email', name: 'email' },
                        { data: 'type', name: 'type' },
                        { data: 'amount', name: 'amount' },
                        { data: 'date', name: 'date' },
                        { data: 'created_at', name: 'created_at' },
                        {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: ['csv', 'excel']
            });
        });
      function add(){
            $('#UserForm').trigger("reset");
            $('#UserModel').html("Add User");
            $('#user-modal').modal('show');
            $('#id').val('');
      }   
      function editFunc(id){
         $.ajax({
            type:"POST",
            url: "{{ url('edit-user') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                  console.log(res);
                  $('#editUserDetail').html(res.html);
                  $('#edit-user-modal').modal('show');
            }
         });
      }  
      function deleteFunc(id){
      if (confirm("Delete Record?") == true) {
      var id = id;
      // ajax
      $.ajax({
      type:"POST",
      url: "{{ url('delete-user') }}",
      data: { id: id },
      dataType: 'json',
      success: function(res){
      var oTable = $('#ajax-crud-datatable').dataTable();
      oTable.fnDraw(false);
      }
      });
      }
      }
      $('#UserForm').submit(function(e) {
         e.preventDefault();
         var formData = new FormData(this);
         $.ajax({
               type:'POST',
               url: "{{ url('store-user')}}",
               data: formData,
               cache:false,
               contentType: false,
               processData: false,
               success: (data) => {
                    if($.isEmptyObject(data.error)){
                        $("#user-modal").modal('hide');
                        var oTable = $('#ajax-crud-datatable').dataTable();
                        oTable.fnDraw(false);
                        $("#btn-save").html('Submit');
                        $("#btn-save"). attr("disabled", false);
                    }else{
                        printErrorMsg(data.error);
                    }
                       
               },
               error: function(data){
                  console.log(data);
               }
         });
      });

       function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }

      
   </script>
</html>