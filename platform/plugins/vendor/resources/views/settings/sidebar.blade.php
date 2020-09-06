<div class="col-12 col-md-3">
  <div class="list-group mb-3 br2" style="box-shadow: rgb(204, 204, 204) 0px 1px 1px;">
    <div class="list-group-item fw6 bn light-gray-text">
      {{ trans('plugins/vendor::dashboard.sidebar_title') }}
    </div>
    <a href="{{ route('public.vendor.settings') }}" class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.vendor.settings') active @endif">
      <i class="fas fa-user-circle mr-2"></i>
      <span>{{ trans('plugins/vendor::dashboard.sidebar_information') }}</span>
    </a>
    <a href="{{ route('public.vendor.security') }}" class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.vendor.security' ) active @endif">
      <i class="fas fa-user-lock mr-2"></i>
      <span>{{ trans('plugins/vendor::dashboard.sidebar_security') }}</span>
    </a>
    @php
    $type="bank";
    @endphp
    <a href="{{ route('public.vendor.payment_method','type='.$type)}}" class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.vendor.payment_method' && ($selectedtype) && $selectedtype==$type) active @endif">
      <i class="fas fa-university mr-2"></i>
      <span>{{ _('Bank Account') }}</span>
      
      @if(isset($user) && $user->bankAccount&&$user->bankAccount->status=='active')
      <i class="fas fa-check check-blue"></i>
      @endif
      
    </a>
    @php
    $type="mobileMoney";
    @endphp
    <a href="{{ route('public.vendor.payment_method','type='.$type) }}" class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.vendor.payment_method'  && ($selectedtype)&&$selectedtype==$type) active @endif">
      <i class="fas fa-mobile mr-2"></i>
      <span>{{ _("Mobile Money") }}</span>
      @if(isset($user)&&$user->mobileMoney&&$user->mobileMoney->status=='active')
      <i class="fas fa-check check-blue"></i>
      @endif
    </a>
    @php
    $type="other";
    @endphp
    <a href="{{ route('public.vendor.payment_method','type='.$type) }}" class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.vendor.payment_method' && ($selectedtype)&&$selectedtype==$type) active @endif">
      <i class="fas fa-money-check mr-2"></i>
      <span>{{ _("Other") }}</span>
      @if(isset($user) && $user->otherAccount&&$user->otherAccount->status=='active')
      <i class="fas fa-check check-blue"></i>
      @endif
    </a>
  </div>
</div>
