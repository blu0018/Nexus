@extends('layouts.app')
@section('title', 'Manage Subscription')
@section('subscription_select', 'active')    
@section('container')
    <h1 style="margin-bottom: 10px;">Manage Subscription</h1>
    <a href="{{ route('subscription.index') }}" class="btn btn-success"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h3 class="text-center title-2">{{ isset($subscription->id) ? 'Update Subscription' : 'Add Subscription' }}</h3>
                            </div>
                            <hr>

                            <form action="{{ route('subscription.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ @$subscription->id }}">
                            
                                <div class="form-group">
                                    <label for="name" class="control-label mb-1">Name</label>
                                    <input id="name" name="name" type="text" class="form-control" value="{{ old('name', @$subscription->name) }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image" class="control-label mb-1">Image</label>
                                    <div class="input-group">
                                        <input id="image" name="image" type="file" class="form-control">
                                    </div>
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if(@$subscription->image)
                                    <div class="col-6 mt-3">
                                        <img src="{{ asset('/Subscription/'.$subscription->image) }}" style="width:150px; height:150px" alt="Subscription Image">
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="free_trial" class="control-label mb-1">Free Trial</label>
                                    <select id="free_trial" name="free_trial" class="form-control">
                                        <option value="1" {{ old('free_trial', @$subscription->free_trial) == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('free_trial', @$subscription->free_trial) == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('free_trial')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="free_trial_days" class="control-label mb-1">Free Trial Days</label>
                                    <input id="free_trial_days" name="free_trial_days" type="text" class="form-control" value="{{ old('free_trial_days', @$subscription->free_trial_days) }}">
                                    @error('free_trial_days')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="period" class="control-label mb-1">Period</label>
                                    <input id="period" name="period" type="text" class="form-control" value="{{ old('period', @$subscription->period) }}">
                                    @error('period')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="price" class="control-label mb-1">Price</label>
                                    <input id="price" name="price" type="text" class="form-control" value="{{ old('price', @$subscription->price) }}">
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="submit-btn">
                                    <button type="submit" class="btn btn-lg btn-info btn-block">
                                        {{ isset($subscription->id) ? 'Update' : 'Add' }}
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
