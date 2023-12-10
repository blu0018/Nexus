@extends('layouts.app')
@section('title', 'Color') 
@section('color_select', 'active') 
@section('container')

    <h1 style="margin-bottom: 10px;">Color</h1>

    @if(session('class') && session('message'))
    <div class="sufee-alert alert with-close alert-{{ session('class') }} alert-dismissible fade show">
    {{ session('message') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
	</button>						
	</div>
    @endif
    
    <a href="{{ route('color.manage') }}" class="btn btn-success add-btn"><i class="fas fa-plus"></i> Color</a>
    @if(!$colors->isEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Color</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                     @foreach($colors as $color)
                        <tr>
                            <td>{{$color->id}}</td>
                            <td>{{$color->color}}</td>
                            <td>
                                @if($color->status == 1)
                                <a href="{{ route('color.status', ['type' => 0, 'id' => $color->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-on"></i> Active
                                </a>
                                 @else
                                <a href="{{ route('color.status', ['type' => 1, 'id' => $color->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-off"></i> Inactive
                                </a>
                                @endif
                                <a href="{{ route('color.edit',['id' => $color->id]) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{ route('color.delete', ['id' => $color->id]) }}" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
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
        <h1 class="text-center">No Color Found</h1>
    </div>
    @endif  
@endsection
