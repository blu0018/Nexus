@extends('layouts.app')
@section('title', 'Subscription') 
@section('subscription_select', 'active') 
@section('container')
    <h1 style="margin-bottom: 10px;">Subscription</h1>
    @if(session('class') && session('message'))
    <div class="sufee-alert alert with-close alert-{{ session('class') }} alert-dismissible fade show">
    {{ session('message') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
	</button>						
	</div>
    @endif

    <a href="{{ route('subscription.manage') }}" class="btn btn-success add-btn"><i class="fas fa-plus"></i> Add Subscription</a>
    @if(!$subscriptions->isEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>period</th>
                            <th>price</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                     @foreach($subscriptions as $subscription)
                        <tr>
                            <td>{{$subscription->name}}</td>
                            <td>{{ucfirst($subscription->period)}}</td>
                            <td>{{$subscription->price}}</td>
                            <td style="color:{{$subscription->status == '1' ? 'green' : '#dc3545'}}">{{$subscription->status == '1' ? 'Active' : 'Deactive'}}</td>
                            
                            <td><img src="{{ asset('/Subscription/'.$subscription->image) }}" style="height: 150px;width:150px;"></td>
                            <td>
                                @if($subscription->status == 1)
                                <a href="{{ route('subscription.status', ['type' => 0, 'id' => $subscription->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-on"></i> Active
                                </a>
                                 @else
                                <a href="{{ route('subscription.status', ['type' => 1, 'id' => $subscription->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-toggle-off"></i> Inactive
                                </a>
                                @endif
                                <a href="{{ route('subscription.edit',['id' => $subscription->id]) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{ route('subscription.delete', ['id' => $subscription->id]) }}" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
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
        <h1 class="text-center">No Subscription Found</h1>
    </div>
    @endif  
@endsection
