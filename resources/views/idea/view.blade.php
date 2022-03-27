@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
@stop
@section('content')
<div class="container">
    <div class="row justify-content-center">
            <div class="col-md-8 text-end mt-4">
                <a class="btn btn-primary m-2 float-right" href="{{ route('ideas.index') }}"><i class="fa fa-arrow-left"></i> Back To List</a>
            </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <p><b>{{ $idea->title }} Created By {{ $idea->annonymous == true ? "Anonymous" : $idea->createdByUser()}}</b></p>
                    <p>
                        {{ $idea->description }}
                    </p>
                     @foreach($idea->comments as $comment)
                        <div class="display-comment">
                            <strong>{{ $comment->annonymous == true ? "Anonymous" : $comment->user->full_name }}</strong><br>

                                <span>{{ $comment->description }}</span> &nbsp; 
                                @if(auth()->id() == $comment->user_id)
                                <a class="category_edit" data-edit="{{$comment}}" data-toggle="modal" data-target="#editModal"> Edit </a>
                                @endif
                                
                       
                        </div>
                    @endforeach
                    @if(!$closure_check)
                    <hr />
                    <h4>Add comment</h4>
                    <form method="post" action="{{ route('comment.add') }}">
                        @csrf

                        <div class="form-group">
                            <input type="text" name="comment_body" {{$disable}} class="form-control" />
                            <input type="hidden" name="idea_id" id="idea_id" value="{{ $idea->id }}" />
                        </div>

                        <div class="row">
                            
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" {{$disable}}  value="Add Comment" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <button type="button" id="reaction_btn" data-id="1" class="btn btn-{{isset($reaction_up) ? $reaction_up : 'outline-secondary'}}">
                                <i class="fa fa-thumbs-up"></i>
                            </button>
                            <button type="button" id="reaction_btn_1" data-id="2" class="btn btn-{{isset($reaction_down) ? $reaction_down : 'outline-secondary'}}">
                                <i class="fa fa-thumbs-down"></i>
                            </button>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-check">
                                    
                                <input type="checkbox" name="annonymous" value="1" class="form-check-input"/>
                                <label for="annonymous" class="form-check-label">{{ __('Anonymous') }}</label>
                                </div>

                            </div>
                        </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Comment</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="EditformData" method="POST">
                        @csrf
                        @method("PATCH")
                        <input type="hidden" name="id" id="edit-form-id">
                        <input type="hidden" name="idea_id" id="idea_id" />
                        <div class="form-group">
                            <!-- <label for="description">Comment </label> -->

                            <textarea name="description" id="description" class="form-control" cols="40" rows="5" placeholder="Enter Description" required=""></textarea>
                        </div>
                        <div class="form-group">
                                <div class="form-check">
                                    
                                <input type="checkbox" name="annonymous" id="anonymous" value="1" class="form-check-input"/>
                                <label for="annonymous" class="form-check-label">{{ __('Anonymous') }}</label>
                                </div>

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
@endsection
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $('#reaction_btn,#reaction_btn_1').on('click', function(e){
        e.preventDefault();
        var up_down = $(this).data('id');
        var idea_id = $('#idea_id').val();
        var url = '{{ route("reaction.add") }}';

          $.ajax({
                url: url, 
                type: 'POST',
                dataType: "json", 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id : idea_id,
                    up_down : up_down,
                }
                ,
                success: function(response) {
                    location.reload();
                }
                
          });
      });

        $('.category_edit').on("click",function () {
          var edit_datas = $(this).data('edit');
          var check = edit_datas.annonymous;

          $(".modal-body #edit-form-id").val(edit_datas.id);
          $(".modal-body #idea_id").val(edit_datas.idea_id);
          $(".modal-body #description").val(edit_datas.description);
          $("#anonymous").prop('checked', check);
          // $(".modal-body #btn_save").html("Update");
      });

    $("#btn_update").click(function(e) {

        e.preventDefault();
       let comment = $('#edit-form-id').val();
       let url = "{{ route('comments.update', ':comment') }}"
       url  = url.replace(':comment', comment);

        $.ajax({
            url: url,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $("#EditformData").serialize(),
            success: function(response) {
                $("#editModal").modal('hide');
                $("#EditformData")[0].reset();
                window.location.reload();

            }
        });
        
    });
});
</script>
@stop