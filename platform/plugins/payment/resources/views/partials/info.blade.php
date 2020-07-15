<p>{{ __('Created At') }}: <strong>{{ $payment->created_at }}</strong></p>
<p>{{ __('Payment Channel') }}: <strong>{{ $payment->payment_channel->label() }}</strong></p>
<p>{{ __('Total') }}: <strong>{{ $payment->amount }} {{ $payment->currency }}</strong></p>
{!! $detail !!}
