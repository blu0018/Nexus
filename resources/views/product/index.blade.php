@extends('layouts.app')
@section('title', 'Product') 
@section('product_select', 'active') 
@section('container')
    <h1 style="margin-bottom: 10px;">Product</h1>
    @if(session('class') && session('message'))
    <div class="sufee-alert alert with-close alert-{{ session('class') }} alert-dismissible fade show">
    {{ session('message') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
	</button>						
	</div>
    @endif

    <a href="{{ route('product.manage') }}" class="btn btn-success add-btn"><i class="fas fa-plus"></i> Add Product</a>
    @if(!$products->isEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                     @foreach($products as $product)
                        <tr>
                            <td>{{$product->name}}</td>
                            <td>{{$product->slug}}</td>
                            <td style="color:{{$product->status == '1' ? 'green' : '#dc3545'}}">{{$product->status == '1' ? 'Active' : 'Deactive'}}</td>
                            <td><img src="{{ asset('/Product/'.$product->image) }}" style="height: 150px;width:150px;"></td>
                            <td>
                                @if($product->status == 1)
                                <a href="{{ route('product.status', ['type' => 0, 'id' => $product->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-on"></i> Active
                                </a>
                                 @else
                                <a href="{{ route('product.status', ['type' => 1, 'id' => $product->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-off"></i> Inactive
                                </a>
                                @endif
                                <a href="{{ route('product.edit',['id' => $product->id]) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{ route('product.delete', ['id' => $product->id]) }}" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
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
        <h1 class="text-center">No Product Found</h1>
    </div>
    @endif  
@endsection
