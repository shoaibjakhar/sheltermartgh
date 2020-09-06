@if($item->status=='pending')
<a href="{{ route('commission.setpaid','id='.$item->id) }}" class="btn btn-icon btn-primary" data-toggle="tooltip" data-original-title="{{ _(
	'Set confirm payment') }}">Pay</a>
@else
	<a href="javascript:void()" class="btn btn-icon btn-success" data-toggle="tooltip" data-original-title="{{ _(
	'Payment confirm') }}">Paid</a>
@endif
{{--
@if (!$item->is_default)
    <a href="#" class="btn btn-icon btn-danger deleteDialog" data-toggle="tooltip" data-section="{{ route('categories.destroy', $item->id) }}" role="button" data-original-title="{{ trans('core/base::tables.delete_entry') }}" >
        <i class="fa fa-trash"></i>
    </a>
@endif
--}}
