@extends('layouts.app')
@section('title', 'Manage Size')
@section('size_select', 'active')    
@section('container')
    <h1 style="margin-bottom: 10px;">Manage Size</h1>
    <a href="{{ route('size.index') }}" class="btn btn-success"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h3 class="text-center title-2">{{ !empty($size['id']) ? 'Update Size' : 'Add Size' }}</h3>
                            </div>
                            <hr>

                            <form action="{{ route('size.update')}}" method="post">
                                @csrf
                                <input hidden id="cc-pament" name="id" type="text" class="form-control"  value="{{ @$size->id }}">
                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Size</label>
                                    <input id="cc-pament" name="size" type="text" class="form-control"  value="{{ old('size', @$size->size) }}">
                                    @error('size')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
            
                                <div class="submit-btn">
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                        {{ !empty($Size['id']) ? 'Update' : 'Add' }}
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
