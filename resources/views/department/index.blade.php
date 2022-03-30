@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
@stop
    
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8 margin-tb">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mt-5">
                    <h2>Departments List</h2>
                </div>
            </div>
            <div class="col-md-12 text-end mt-4">
                <button type="button" class="btn btn-primary m-1 float-right" data-toggle="modal" data-target="#addModal">
                    <i class="fa fa-plus"></i> Add New Department
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8 margin-tb">
        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-3 alert-flash">
                <span>{{ $message }}</span>
            </div>
        @endif
        <table class="table table-bordered mt-4" id="department_list">
            <tr class="btn-primary">
                <th>No</th>
                <th>Name</th>
                <th>Description</th>
                <th width="180px">Action</th>
            </tr>
            @foreach ($departments as $key => $department)
            <tr>
                <td>{{ $departments->firstItem() + $key }}</td>
                <td>{{ $department->code }}</td>
                <td>{{ $department->description }}</td>
                <td>
                    <a class="btn btn-primary btn-sm department_edit" data-edit="{{$department}}" data-toggle="modal" data-target="#editModal">
                    <span class="fa fa-edit"></span></a>
                    <button type="button" class="btn btn-danger btn-sm open_delete" data-toggle="modal" data-id="{{$department->id}}" data-target="#modal_delete"><span class="fa fa-trash"></span></button>
                </td>
            </tr>
            @endforeach
        </table>
        {{$departments->links("pagination::bootstrap-4")}}
    </div>
</div>

<!-- Add Record  Modal -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-white bg-primary mb-3">
                <h4 class="modal-title">Add New Department</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="formData" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="code">Code: </label>
                        <input type="text" class="form-control" name="code" placeholder="Enter Code" required="">
                    </div>
                    <div class="form-group">
                        <label for="description">Description: </label>

                        <textarea name="description" class="form-control" cols="40" rows="5" placeholder="Enter Description" required=""></textarea>
                    </div>
                    <hr>
                    <div class="form-group float-right">
                        <button type="submit" class="btn btn-success" id="btn_save">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

  <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-white bg-primary mb-3">
                    <h4 class="modal-title">Edit Department</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="EditformData" method="POST">
                        @csrf
                        @method("PATCH")
                        <input type="hidden" name="id" id="edit-form-id">
                        <div class="form-group">
                            <label for="code">Code: </label>
                            <input type="text" class="form-control" name="code" id="code" placeholder="Enter Code" required="">
                        </div>
                        <div class="form-group">
                            <label for="description">Description: </label>
                            <!-- <input type="text" class="form-control" name="txtDescription" id="txtDescription" placeholder="Enter Description" required=""> -->
                            <textarea name="description" id="description" class="form-control" cols="40" rows="5" placeholder="Enter Description" required=""></textarea>
                        </div>
                        <hr>
                        <div class="form-group float-right">
                            <button type="submit" class="btn btn-primary" id="btn_update">Update</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="alert_ModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><span class="fa fa-info-circle"></span> Confirmation</h4>
                </div>
                <div class="modal-body">
                    Are you sure want to delete?
                </div>
                <div class="modal-footer">
                    <form id="delete_form" method="POST"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-primary" id="delete-btn">Yes</button>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> 
<script type="text/javascript">
 $(document).ready(function() {
      $('#formData').on('btn_save', function(e){

        var url = '{{ route("departments.store") }}';

          $.ajax({
                url: url, 
                type: 'POST', 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $('#formData').serialize(),
                success: function(data){
                    $("#addModal").modal('hide');
                    $("#formData")[0].reset();
                    $("#department_list").load(window.location + " #department_list");
                },
                error: function(error) {
                    console.log('error');
                }
          });
      });
    

      $('.department_edit').on("click",function () {
          var edit_datas = $(this).data('edit');  
          $(".modal-body #edit-form-id").val(edit_datas.id);
          $(".modal-body #code").val(edit_datas.code);
          $(".modal-body #description").val(edit_datas.description);
          // $(".modal-body #btn_save").html("Update");
      });

      $("#btn_update").click(function(e) {

            e.preventDefault();
           let dept = $('#edit-form-id').val();
           let url = "{{ route('departments.update', ':dept') }}"
           url  = url.replace(':dept', dept);
           
            $.ajax({
                url: url,
                type: "POST",
                data: $("#EditformData").serialize(),
               success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated successfully',
                    },4000);
                   
                    $("#editModal").modal('hide');
                    $("#EditformData")[0].reset();
                    setTimeout(function(){location.reload()}, 2200);
                    //window.location.reload();
                } 
            });
            
        });

      $(document).on("click", ".open_delete", function () { 
             var id = $(this).data('id');
             var url = '{{ route("departments.destroy", ":id") }}';
             url = url.replace(':id', id);
             $('#delete_form').attr('action', url);
         });
  });
</script>
@endsection