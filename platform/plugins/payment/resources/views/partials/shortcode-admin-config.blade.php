<div class="form-group">
    <div class="row">
        <label class="col-lg-3 col-md-3 control-label">{{ trans('plugins/payment::payment.payment_name') }}</label>
        <div class="col-lg-9 col-md-9">
            <input type="text" name="name" class="form-control" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.payment_name') }}">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <label class="col-lg-3 col-md-3 control-label">{{ trans('plugins/payment::payment.amount') }}</label>
        <div class="col-lg-9 col-md-9">
            <input type="number" name="amount" class="form-control" value="1" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.amount') }}">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <label class="col-lg-3 col-md-3 control-label">{{ trans('plugins/payment::payment.currency') }}</label>
        <div class="col-lg-9 col-md-9">
            <input type="text" name="currency" class="form-control" value="USD" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.currency') }}">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <label class="col-lg-3 col-md-3 control-label">{{ trans('plugins/payment::payment.callback_url') }}</label>
        <div class="col-lg-9 col-md-9">
            <input type="text" name="callback_url" class="form-control" value="/" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.callback_url') }}">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <label class="col-lg-3 col-md-3 control-label">{{ trans('plugins/payment::payment.return_url') }}</label>
        <div class="col-lg-9 col-md-9">
            <input type="text" name="return_url" class="form-control" value="/" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.return_url') }}">
        </div>
    </div>
</div>
