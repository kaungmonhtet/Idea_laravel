@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
@stop
    
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white bg-primary mb-3">Edit Idea</div>
                <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success mt-3 alert-flash">
                        <span>{{ $message }}</span>
                    </div>
                @endif
                    <form method="post" action="{{ route('ideas.update',$idea) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row mb-3">
                            <label for="category_id" class="col-md-3 col-form-label">{{ __('Category') }}</label>

                            <div class="col-md-6">
                                <select id="category_id" type="text" class="form-control form-select @error('category_id') is-invalid @enderror" name="category_id" value="{{ old('category_id') }}" required autocomplete="category_id" autofocus>
                                    <option value="">Select One</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{$category->id == $idea->category_id ? 'selected' : ''}}>{{$category->description}}</option>
                                    @endforeach
    
                                </select>
                            </div>
                        </div>
<!-- 
                        <div class="row mb-3">
                            <label for="department_id" class="col-md-4 col-form-label">{{ __('Department') }}</label>

                            <div class="col-md-6">
                                <select id="department_id" type="text" class="form-control @error('department_id') is-invalid @enderror" name="department_id" value="{{ old('department_id') }}" required autocomplete="department_id" autofocus>
                                    <option value="">Select One</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}" {{$department->id == $idea->department_id ? 'selected' : ''}}>{{$department->description}}</option>
                                    @endforeach
    
                                </select>

                            </div>
                        </div> -->

                        <div class="row mb-3">
                            <label for="academic_year_id" class="col-md-3 col-form-label">{{ __('Academic Year') }}</label>

                            <div class="col-md-6">
                                <select id="academic_year_id" type="text" class="form-control form-select @error('academic_year_id') is-invalid @enderror" name="academic_year_id" value="{{ old('academic_year_id') }}" autocomplete="academic_year_id" disabled>
                                    <option value="">Select One</option>
                                    @foreach($academic_years as $year)
                                        <option value="{{$year->id}}" {{$year->id == $idea->academic_year_id ? 'selected' : ''}}>{{$year->academic_year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row closure_date">
                            <label for="closure_date" class="col-md-3 col-form-label">{{ __('Closure Date') }}</label>
                            <!-- <input type="hidden" name="closure_date" value=""> -->
                            <label class="col-md-3 col-form-label" id="closure_date">{{$idea->academic->closure_date}}</label>
                                            

                            <label for="final_closure_date" class="col-md-3 col-form-label">{{ __('Final Closure Date') }}</label>
                            <label class="col-md-3 col-form-label" id="final_closure_date">{{$idea->academic->final_closure_date}}</label>
                        </div>



                        <div class="form-group">
                            <label class="label">Title </label>
                            <input type="text" name="title" class="form-control" value="{{$idea->title}}" required/>
                        </div>

                        <div class="form-group">
                            <label class="label">Description </label>
                            <textarea name="description" rows="10" cols="30" class="form-control" required>{{$idea->description}}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="label">Document </label>
                            <input type="file" name="document_url" value="{{Storage::url($idea->document_url)}}" class="form-control" placeholder="hihih" />
                            <p>Document Url : {{$idea->document_url ?? ''}}</p>
                        </div>

                        <div class="row">
                            <div class="form-group ml-4">
                                <div class="form-check">
                                    
                                <input type="checkbox" name="annonymous" value="1" {{$idea->annonymous == 1 ? 'checked' : ''}} class="form-check-input"/>
                                <label for="annonymous" class="form-check-label">{{ __('Anonymous') }}</label>
                                </div>

                            </div>


                            <div class="form-group ml-4">
                                <div class="form-check">
                                    
                                <input type="checkbox" name="term" id="term" checked class="form-check-input"/>
                                <label for="term"  class="form-check-label">{{ __('Terms & Conditions') }}</label>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success save" value="Submit"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
