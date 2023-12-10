@extends('layouts.app')
@section('title', 'Manage Color')
@section('color_select', 'active')    
@section('container')
    <h1 style="margin-bottom: 10px;">Manage color</h1>
    <a href="{{ route('color.index') }}" class="btn btn-success"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h3 class="text-center title-2">{{ !empty($color['id']) ? 'Update Color' : 'Add Color' }}</h3>
                            </div>
                            <hr>

                            <form action="{{ route('color.update')}}" method="post">
                                @csrf
                                <input hidden id="cc-pament" name="id" type="text" class="form-control"  value="{{ @$color->id }}">
                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Color</label>
                                    <input id="cc-pament" name="color" type="text" class="form-control"  value="{{ old('color', @$color->color) }}">
                                    @error('color')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
            
                                <div class="submit-btn">
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                        {{ !empty($color['id']) ? 'Update' : 'Add' }}
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
