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
                    <h2>Idea List By Final Closure Date</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
            <div class="col-md-8 text-end mt-4">
                <a class="btn btn-primary m-2 float-right" href="{{ route('download-zip') }}"><i class="fa fa-download"></i> Download</a>
            </div>
    <div class="col-lg-8 margin-tb">
        <table  class="table table-bordered mt-4">
            <thead>
                <th>ID</th>
                <th>Title</th>
                <th>Created By</th>
                <th>View Count</th>
                <!-- <th>Action</th> -->
            </thead>
            <tbody>
            @foreach($ideas as $key => $idea)
            <tr>
                <td>{{ $ideas->firstItem() + $key}}</td>
                <td>{{ $idea->title }}</td>
                <td>{{ $idea->annonymous == true ? "Anonymous" : $idea->createdByUser()}}</td>
                <td>{{ $idea->view_count }}</td>
                <td>

                </td>
            </tr>
            @endforeach
            </tbody>

        </table>
        {{$ideas->links("pagination::bootstrap-4")}}
    </div>
</div>

@endsection
