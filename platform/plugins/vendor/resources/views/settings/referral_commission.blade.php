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
                        <th>Id</th>
                        <th>Your Referrar User</th>
                        <th>Your Referral Commission</th>
                        <th>Status</th>
                        <th>Action</th>
                      </thead>
                      <tbody>
                        <tr>
                          <td>0001</td>
                          <td>Edith Appiah Kumi</td>
                          <td>pegesinc@gmail.com</td>
                          <td>Pending</td>
                          <td>Action</td>
                        </tr>
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