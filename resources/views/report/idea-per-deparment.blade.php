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
                    <h2>Departments per Idea List</h2>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="row justify-content-center">
            <div class="col-md-8 text-end mt-4">
                <form action="{{route('ideas-per-department')}}" method="get">
                <button type="submit" name="btn" class="btn btn-success" value="export">Excel Export</button> 
            </form>
            </div>
    <div class="col-lg-8 margin-tb">

        <table class="table table-bordered mt-4" id="department_list">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Description</th>
                <th>Idea Count</th>
                <th>Percentage</th>
                <th>Contributor Count</th>
                <!-- <th width="180px">Action</th> -->
            </tr>
            @foreach ($departments as $key => $department)
            <tr>
                <td>{{ $departments->firstItem() + $key }}</td>
                <td>{{ $department->code }}</td>
                <td>{{ $department->description }}</td>
                <td>{{ $department->idea_count }}</td>
                <td>{{ $department->idea_count/100 }}</td>
                <td>{{ $department->getCount($department->user) + $department->idea_count }}</td>

            </tr>
            @endforeach
        </table>

    </div>
</div>

@endsection
