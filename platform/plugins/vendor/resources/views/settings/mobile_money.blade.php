@extends('plugins/vendor::layouts.skeleton')
@section('content')

  <div class="settings crop-avatar">
    <div class="container">
      <div class="row">
        @include('plugins/vendor::settings.sidebar')
        <div class="col-12 col-md-9">
            <div class="main-dashboard-form">
                <!-- Setting Title -->
                <div class="row">
                    <div class="col-12">
                        <h4 class="with-actions">{{ _('Mobile Money Information') }}</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 order-lg-0">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <form action="{{ route('public.vendor.payment_method') }}" id="setting-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Name -->
                            <input type="hidden" name="type" value="{{ $selectedtype }}">
                            <!-- first name -->
                            <div class="form-group">
                                <label for="first_name">{{ _('First Name') }}</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" required value="{{ old('first_name') ?? ($user->mobileMoney)?$user->mobileMoney->first_name:'' }}">
                            </div>
                            <!-- last name -->
                            <div class="form-group">
                                <label for="last_name">{{ _('Last Name') }}</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" required value="{{ old('last_name') ?? ($user->mobileMoney)?$user->mobileMoney->last_name:'' }}" >
                            </div>

                            <div class="form-group">
                                <label for="account_no">{{ _('Mobile Account No') }}</label>
                                <input type="text" class="form-control" name="account_no" id="account_no" required value="{{ old('account_no') ?? ($user->mobileMoney)?$user->mobileMoney->account_number:'' }}">
                            </div>
                            <!-- Name -->
                            <div class="form-group">
                                <label for="account_holder">{{ _('Account Holder') }}</label>
                                <input type="text" class="form-control" name="account_holder" id="account_holder" required value="{{ old('account_holder') ?? ($user->mobileMoney)?$user->mobileMoney->account_holder:'' }}">
                            </div>
                             <div class="form-group custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="switch1" value="active" name="status" {{ ($user->mobileMoney&&$user->mobileMoney->status=='active')?'checked':'' }}>
                              <label class="custom-control-label" for="switch1">Acitve</label>
                            </div>
                            @if($user->mobileMoney)
                            <button type="submit" class="btn btn-primary fw6">{{ trans('plugins/vendor::dashboard.update') }}</button>
                            @else
                            <button type="submit" class="btn btn-primary fw6">{{ trans('plugins/vendor::dashboard.save') }}</button>
                            @endif
                        </form>
                    </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <!-- Laravel Javascript Validation -->
  <script type="text/javascript" src="{{ asset('vendor/core/packages/js-validation/js/js-validation.js')}}"></script>
  {!! JsValidator::formRequest(\Botble\Vendor\Http\Requests\SettingRequest::class); !!}
  <script type="text/javascript">
     function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result);
                $('#blah').show();    
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    // index => month [0-11]
    let numberDaysInMonth = [31,28,31,30,31,30,31,31,30,31,30,31];

    $(document).ready(function() {
      // Init form select
      initSelectBox();
    });

    function initSelectBox() {
      let oldBirthday = '{{ $user->dob }}';
      let selectedDay = '';
      let selectedMonth = '';
      let selectedYear = '';

      if (oldBirthday !== '') {
        selectedDay = parseInt(oldBirthday.substr(8, 2));
        selectedMonth = parseInt(oldBirthday.substr(5, 2));
        selectedYear = parseInt(oldBirthday.substr(0, 4));
      }

      let dayOption = `<option value="">{{ trans('plugins/vendor::dashboard.day_lc') }}</option>`;
      for (let i = 1; i <= numberDaysInMonth[0]; i++) { //add option days
        if (i === selectedDay) {
          dayOption += `<option value="${i}" selected>${i}</option>`;
        } else {
          dayOption += `<option value="${i}">${i}</option>`;
        }
      }
      $('#day').append(dayOption);

      let monthOption = `<option value="">{{ trans('plugins/vendor::dashboard.month_lc') }}</option>`;
      for (let j = 1; j <= 12; j++) {
        if (j === selectedMonth) {
          monthOption += `<option value="${j}" selected>${j}</option>`;
        } else {
          monthOption += `<option value="${j}">${j}</option>`;
        }
      }
      $('#month').append(monthOption);

      let d = new Date();
      let yearOption = `<option value="">{{ trans('plugins/vendor::dashboard.year_lc') }}</option>`;
      for (let k = d.getFullYear(); k >= 1918; k--) {// years start k
        if (k === selectedYear) {
          yearOption += `<option value="${k}" selected>${k}</option>`;
        } else {
          yearOption += `<option value="${k}">${k}</option>`;
        }
      }
      $('#year').append(yearOption);
    }

    function isLeapYear(year) {
      year = parseInt(year);
      if (year % 4 !== 0) {
        return false;
      }
      if (year % 400 === 0) {
        return true;
      }
      if (year % 100 === 0) {
        return false;
      }
      return true;
    }

    function changeYear(select) {
      if (isLeapYear($(select).val())) {
        // Update day in month of leap year.
        numberDaysInMonth[1] = 29;
      } else {
        numberDaysInMonth[1] = 28;
      }

      // Update day of leap year.
      let monthSelectedValue = parseInt($("#month").val());
      if (monthSelectedValue === 2) {
        let day = $('#day');
        let daySelectedValue = parseInt($(day).val());
        if (daySelectedValue > numberDaysInMonth[1]) {
          daySelectedValue = null;
        }

        $(day).empty();

        let option = `<option value="">{{ trans('plugins/vendor::dashboard.day_lc') }}</option>`;
        for (let i = 1; i <= numberDaysInMonth[1]; i++) { //add option days
          if (i === daySelectedValue) {
            option += `<option value="${i}" selected>${i}</option>`;
          } else {
            option += `<option value="${i}">${i}</option>`;
          }
        }

        $(day).append(option);
      }
    }

    function changeMonth(select) {
      let day = $('#day');
      let daySelectedValue = parseInt($(day).val());
      let month = 0;

      if ($(select).val() !== '') {
        month = parseInt($(select).val()) - 1;
      }

      if (daySelectedValue > numberDaysInMonth[month]) {
        daySelectedValue = null;
      }

      $(day).empty();

      let option = `<option value="">{{ trans('plugins/vendor::dashboard.day_lc') }}</option>`;

      for (let i = 1; i <= numberDaysInMonth[month]; i++) { //add option days
        if (i === daySelectedValue) {
          option += `<option value="${i}" selected>${i}</option>`;
        } else {
          option += `<option value="${i}">${i}</option>`;
        }
      }

      $(day).append(option);
    }
  </script>
@endpush
