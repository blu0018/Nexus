@extends('layouts.app')
@section('title', 'Size') 
@section('coupon_select', 'active') 
@section('container')
    <h1 style="margin-bottom: 10px;">Size</h1>
    @if(session('class') && session('message'))
    <div class="sufee-alert alert with-close alert-{{ session('class') }} alert-dismissible fade show">
    {{ session('message') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
	</button>						
	</div>
    @endif
    
    <a href="{{ route('coupon.manage') }}" class="btn btn-success add-btn"><i class="fas fa-plus"></i> Add Coupan</a>
    @if(!$coupons->isEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Code</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                     @foreach($coupons as $coupon)
                        <tr>
                            <td>{{$coupon->title}}</td>
                            <td>{{$coupon->code}}</td>
                            <td>{{$coupon->value}}</td>
                            <td>
                                @if($coupon->status == 1)
                                <a href="{{ route('coupon.status', ['type' => 0, 'id' => $coupon->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-on"></i> Active
                                </a>
                                 @else
                                <a href="{{ route('coupon.status', ['type' => 1, 'id' => $coupon->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-off"></i> Inactive
                                </a>
                                @endif
                                <a href="{{ route('coupon.edit',['id' => $coupon->id]) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{ route('coupon.delete', ['id' => $coupon->id]) }}" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
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
        <h1 class="text-center">No Size Found</h1>
    </div>
    @endif  
@endsection
