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
                    <h4 class="with-actions">{{ trans('plugins/vendor::dashboard.referrals_title') }}</h4>
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
                        <th>SR. #</th>
                        <th>Referral Agent Name</th>
                        <th>Email</th>
                        <th>Created at</th>
                      </thead>
                      <tbody>
                        @if(!empty($my_referrals))
                          @foreach($my_referrals as $key => $referral)
                            <tr>
                              <td>{{ $key+1 }}</td>
                              <td>{{ $referral['first_name'] }} {{ $referral['last_name'] }}</td>
                              <td>{{ $referral['email'] }}</td>
                              <td>{{ $referral['created_at'] }}</td>
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
  <br>
  <div class="settings">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3"></div>
        <div class="col-12 col-md-9">
            <div class="main-dashboard-form">
              <div class="mb-5">
                <!-- Title -->
                <div class="row">
                  <div class="col-12">
                    <h4 class="with-actions">{{ trans('plugins/vendor::dashboard.invite_friends') }}</h4>
                  </div>
                </div>

            <!-- Content -->
                <div class="row">
                  <div class="col-12">
                    <form>
                      <div class="col-md-8">
                        <div class="form-group">
                          <label>{{ trans('plugins/vendor::dashboard.share_referral_link') }}</label>
                          <input type="text" name="" class="form-control" readonly="true" value="{{$referral_link}}">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <input type="button" class="btn btn-info" value="{{ trans('plugins/vendor::dashboard.copy_link') }}">
                      </div>
                    </form>
                  </div>
                </div>

              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
