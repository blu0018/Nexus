@extends('layouts.app')
@section('title', 'Manage Size')
@section('coupon_select', 'active')    
@section('container')
    <h1 style="margin-bottom: 10px;">Manage Size</h1>
    <a href="{{ route('coupon.index') }}" class="btn btn-success"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h3 class="text-center title-2">{{ !empty($Size['id']) ? 'Update Size' : 'Add Size' }}</h3>
                            </div>
                            <hr>

                            <form action="{{ route('coupon.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input hidden id="cc-pament" name="id" type="text" class="form-control"  value="{{ @$coupon->id }}">
                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Title</label>
                                    <input id="cc-pament" name="title" type="text" class="form-control"  value="{{ old('title', @$coupon->title) }}">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Code</label>
                                    <input id="cc-pament" name="code" type="text" class="form-control"  value="{{ old('code', @$coupon->code) }}">
                                    @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cc-payment" class="control-label mb-1">Value</label>
                                    <input id="cc-pament" name="value" type="text" class="form-control"  value="{{ old('value', @$coupon->value) }}">
                                    @error('slug')
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
