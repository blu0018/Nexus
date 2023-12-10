@extends('layouts.app')
@section('title', 'Manage Product')
@section('product_select', 'active')    
@section('container')
    <h1 style="margin-bottom: 10px;">Manage Product</h1>
    <a href="{{ route('product.index') }}" class="btn btn-success"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h3 class="text-center title-2">{{ !empty($product['id']) ? 'Update Product' : 'Add Product' }}</h3>
                            </div>
                            <hr>

                            <form action="{{ route('product.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input hidden id="cc-pament" name="id" type="text" class="form-control"  value="{{ @$product->id }}">
                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Name</label>
                                    <input id="cc-pament" name="name" type="text" class="form-control"  value="{{ old('name', @$product->name) }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Slug</label>
                                    <input id="cc-pament" name="slug" type="text" class="form-control"  value="{{ old('slug', @$product->slug) }}">
                                    @error('slug')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group has-success">
                                    <label for="cc-name" class="control-label mb-1">Category</label><br>
                                    <select name='category_id'>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{$category->name == $category->name ? 'selected' : '' }} >{{ $category->name }}</option>                                        
                                        @endforeach()
                                    </select>

                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-6" style="padding-left:0px; margin-bottom: 15px">
                                    <label for="image" class="control-label mb-1">Image</label>
                                    <div class="input-group">
                                        <input id="image" name="image" type="file" class="form-control">
                                    </div>
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if(@$product->image)
                                    <div class="col-6 mt-3">
                                        <img src="{{ asset('/Product/'.$product->image) }}" style="width:150px; height:150px" alt="product Image">
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Brand</label>
                                    <input id="cc-pament" name="brand" type="text" class="form-control"  value="{{ old('brand', @$product->brand) }}">
                                    @error('brand')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Model</label>
                                    <input id="cc-pament" name="model" type="text" class="form-control"  value="{{ old('brand', @$product->model) }}">
                                    @error('model')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group has-success">
                                    <label for="cc-name" class="control-label mb-1">Short Description</label><br>
                                    <textarea id="myTextarea" name="short_desc" rows="8" cols="126">{{ old('descshort_description', @$product->short_desc) }}</textarea>
                                    @error('short_desc')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group has-success">
                                    <label for="cc-name" class="control-label mb-1">Description</label><br>
                                    <textarea id="myTextarea" name="desc" rows="8" cols="126">{{ old('desc', @$product->desc) }}</textarea>
                                    @error('desc')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Keywords</label>
                                    <input id="cc-pament" name="keywords" type="text" class="form-control"  value="{{ old('brand', @$product->keywords) }}">
                                    @error('keywords')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">	Technical Specification	</label>
                                    <input id="cc-pament" name="technical_specification" type="text" class="form-control"  value="{{ old('brand', @$product->	technical_specification	) }}">
                                    @error('technical_specification')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Uses</label>
                                    <input id="cc-pament" name="uses" type="text" class="form-control"  value="{{ old('uses', @$product->uses) }}">
                                    @error('uses')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Warranty</label>
                                    <input id="cc-pament" name="warranty" type="text" class="form-control"  value="{{ old('warranty', @$product->warranty) }}">
                                    @error('warranty')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="submit-btn">
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                        {{ !empty($product['id']) ? 'Update' : 'Add' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
