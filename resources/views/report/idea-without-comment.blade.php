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
                    <h2>Idea without Comment List</h2>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="row justify-content-center">
            <div class="col-md-8 text-end mt-4">
                <form action="{{route('ideas-without-comment')}}" method="get">
                <button type="submit" name="btn" class="btn btn-success" value="export">Excel Export</button> 
            </form>
            </div>
    <div class="col-lg-8 margin-tb">
        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-3 alert-flash">
                <span>{{ $message }}</span>
            </div>
        @endif
        <table class="table table-bordered mt-4" id="department_list">
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Description</th>
                <th>View Count</th>
                <th>Created By</th>
            </tr>
            @foreach ($ideas as $key => $idea)
            <tr>
                <td>{{ $ideas->firstItem() + $key }}</td>
                <td>{{ $idea->title }}</td>
                <td>{{ $idea->description }}</td>
                <td>{{ $idea->view_count }}</td>
                <td>{{ $idea->createdByUser() }}</td>

            </tr>
            @endforeach
        </table>

    </div>
</div>

@endsection
