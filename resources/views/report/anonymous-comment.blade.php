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
                    <h2>Anonymous Comment List</h2>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="row justify-content-center">
            <div class="col-md-8 text-end mt-4">
                <form action="{{route('anonymous-comment')}}" method="get">
                <button type="submit" name="btn" class="btn btn-primary" value="export">Excel Export</button> 
            </form>
            </div>
    <div class="col-lg-8 margin-tb">
        <table class="table table-bordered mt-4" id="department_list">
            <tr class="btn-primary">
                <th>No</th>
                <th>Description</th>
                <th>Commentted By</th>
            </tr>
            @foreach ($comments as $key => $comment)
            <tr>
                <td>{{ $comments->firstItem() + $key }}</td>
                <td>{{ $comment->description }}</td>
                <td>{{ $comment->commenttedByUser() }}</td>

            </tr>
            @endforeach
        </table>

    </div>
</div>

@endsection
