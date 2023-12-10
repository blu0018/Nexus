@extends('layouts.app')
@section('title', 'Category') 
@section('category_select', 'active') 
@section('container')
    <h1 style="margin-bottom: 10px;">Category</h1>
    @if(session('class') && session('message'))
    <div class="sufee-alert alert with-close alert-{{ session('class') }} alert-dismissible fade show">
    {{ session('message') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
	</button>						
	</div>
    @endif

    <a href="{{ route('category.manage') }}" class="btn btn-success add-btn"><i class="fas fa-plus"></i> Add Category</a>
    @if(!$categories->isEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                     @foreach($categories as $category)
                        <tr>
                            <td>{{$category->name}}</td>
                            <td>{{$category->slug}}</td>
                            <td>{{$category->description}}</td>
                            <td><img src="{{ asset('/Category/'.$category->image) }}" style="height: 150px;width:150px;"></td>
                            <td>
                                @if($category->status == 1)
                                <a href="{{ route('category.status', ['type' => 0, 'id' => $category->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-on"></i> Active
                                </a>
                                 @else
                                <a href="{{ route('category.status', ['type' => 1, 'id' => $category->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-off"></i> Inactive
                                </a>
                                @endif
                                <a href="{{ route('category.edit',['id' => $category->id]) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{ route('category.delete', ['id' => $category->id]) }}" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div>
        <h1 class="text-center">No Category Found</h1>
    </div>
    @endif  
@endsection
