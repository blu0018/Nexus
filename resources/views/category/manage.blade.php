@extends('layouts.app')
@section('title', 'Manage Category')
@section('category_select', 'active')    
@section('container')
    <h1 style="margin-bottom: 10px;">Manage Category</h1>
    <a href="{{ route('category.index') }}" class="btn btn-success"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h3 class="text-center title-2">{{ !empty($category['id']) ? 'Update Category' : 'Add Category' }}</h3>
                            </div>
                            <hr>

                            <form action="{{ route('category.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input hidden id="cc-pament" name="id" type="text" class="form-control"  value="{{ @$category->id }}">
                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Name</label>
                                    <input id="cc-pament" name="name" type="text" class="form-control"  value="{{ old('name', @$category->name) }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Slug</label>
                                    <input id="cc-pament" name="slug" type="text" class="form-control"  value="{{ old('slug', @$category->slug) }}">
                                    @error('slug')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group has-success">
                                    <label for="cc-name" class="control-label mb-1">Description</label><br>
                                    <textarea id="myTextarea" name="description" rows="8" cols="126">{{ old('description', @$category->description) }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="image" class="control-label mb-1">Image</label>
                                    <div class="input-group">
                                        <input id="image" name="image" type="file" class="form-control">
                                    </div>
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if(@$category->image)
                                    <div class="col-6 mt-3">
                                        <img src="{{ asset('/Category/'.$category->image) }}" style="width:150px; height:150px" alt="Category Image">
                                    </div>
                                @endif

                                <div class="submit-btn">
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                        {{ !empty($category['id']) ? 'Update' : 'Add' }}
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
