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
                    <h2>Idea List</h2>
                </div>
            </div>
            <div class="col-md-12 text-end mt-4">
                <form action="{{route('ideas.index')}}" method="get">
                    <select name="category_id" onchange="this.form.submit()" class="col-form-label m-1 float-left">
                      <option value="all">All</option>
                      <option value="name" {{request("category_id") == "name" ? 'selected' : ''}}>Department Name</option>
                      <option value="asc" {{request('category_id') == "asc" ? 'selected' : ''}}>Latest</option>
                      <option value="desc" {{request('category_id') == "desc" ? 'selected' : ''}}>Newest</option>
                      <option value="like" {{request('category_id') == "like" ? 'selected' : ''}}>Most Liked</option>
                      <option value="comment" {{request('category_id') == "comment" ? 'selected' : ''}}>Most Comment</option>
                      
                    </select>
                </form>
                <a class="btn btn-primary m-1 float-right" href="{{ route('ideas.create') }}"><i class="fa fa-plus"></i> Add New Idea</a>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8 margin-tb">
            <table  class="table table-bordered mt-4">
                <thead>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Created By</th>
                    <th>View Count</th>
                    <th>Like Count</th>
                    <th>Unlike Count</th>
                    <th>Comments Count</th>
                    <th>Action</th>
                </thead>
                <tbody>
                @foreach($ideas as $key => $idea)
                <tr>
                    <td>{{ $ideas->firstItem() + $key}}</td>
                    <td>{{ $idea->title }}</td>
                    <td>{{ $idea->annonymous == true ? "Anonymous" : $idea->createdByUser()}}</td>
                    <td>{{ $idea->view_count }}</td>
                    <td>{{ $idea->likeCount() }}</td>
                    <td>{{ $idea->unlikeCount() }}</td>
                    <td>{{ $idea->commentCount() }}</td>
                    <td>
                        <a href="{{ route('ideas.show', $idea->id) }}" class="btn btn-success btn-sm"><span class="fa fa-eye"></span></a>

                        @if($idea->user->isOwner())
                        <a href="{{ route('ideas.edit', $idea) }}" class="btn btn-primary btn-sm"><span class="fa fa-edit"></span></a>
                        @if(!Auth::user()->isStaff() && $idea->user->isOwner())
                        <button type="button" class="btn btn-danger btn-sm open_delete" data-toggle="modal" data-id="{{$idea->id}}" data-target="#modal_delete"><span class="fa fa-trash"></span></button>
                        @if($idea->document_url)
                        <a class="btn btn-secondary btn-sm" href="{{ Storage::url($idea->document_url) }}" download="">
                            <span class="fa fa-download"></span>
                        </a>
                        @endif
                        @endif
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>

            </table>
            {{$ideas->links("pagination::bootstrap-4")}}
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
<script type="text/javascript">
 
$(document).on("click", ".open_delete", function () { 
     var id = $(this).data('id');
     var url = '{{ route("ideas.destroy", ":id") }}';
     url = url.replace(':id', id);
     $('#delete_form').attr('action', url);
 });
  
</script>
@endsection