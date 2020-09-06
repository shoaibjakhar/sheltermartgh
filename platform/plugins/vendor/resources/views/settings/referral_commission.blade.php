@extends('plugins/vendor::layouts.skeleton')
@section('content')
  <div class="settings">
    <div class="container">
      <div class="row">
        @include('plugins/vendor::settings.sidebar')
        <div class="col-12 col-md-9">
            <div class="main-dashboard-form">
          <div class="mb-5">
            <!-- Title -->
            <div class="row">
              <div class="col-12">
                <h4 class="with-actions">{{ trans('plugins/vendor::dashboard.referrals_commission_title') }}</h4>
              </div>
            </div>

            <!-- Content -->
                @if (session('status'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                @endif

                <div class="row">
                  <div class="col-12">
                    <table class="table table-responsive">
                      <thead>
                        <th>#</th>
                        <th>Property</th>
                        <th>Own Property Commission</th>
                        <th>Your Referrar User</th>
                        <th>Your Referral Commission</th>
                        <th>Status</th>
                      </thead>
                      <tbody>
                        @if($commission->count()>0)
                        @php
                        $count=1;
                        @endphp
                        @foreach($commission AS $commisItem)
                        <tr>
                          <td>{{ str_pad($count++, 4, "0", STR_PAD_LEFT)}}</td>

                          <td>{{ $commisItem->property->name }}</td>

                          @if($commisItem->vendor->id==auth()->guard('vendor')->user()->id)
                          <td>{{ $commisItem->client_commission }}</td>
                          @else
                          <td>0</td>
                          @endif


                          @if($commisItem->vendor->id!=auth()->guard('vendor')->user()->id)
                          <td>{{ $commisItem->vendor->first_name.' '.$commisItem->vendor->last_name }}</td>
                          @else
                          <td>{{ ($parent)?$parent->first_name.' '.$parent->last_name:'No Referral' }}</td>
                          @endif
                            
                          @if($commisItem->vendor->id!=auth()->guard('vendor')->user()->id)
                          <td>{{ $commisItem->vendor_commission }}</td>
                          @else
                          <td>{{ ($parent)?$commisItem->vendor_commission:0 }}</td>
                          <!-- <td>{{   $commisItem->vendor_commission }}</td> -->
                          @endif
                          <td>
                            {{ ($commisItem->status=='clear')?'Paid':'Pending' }}
                          </td>
                          
                        </tr>
                        @endforeach
                        @endif
                        
                      </tbody>
                    </table>
                  </div>
                </div>  

              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
