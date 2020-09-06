<?php

namespace Botble\Vendor\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Storage;

/**
 * @mixin \Eloquent
 */
class PaymentMethod extends Authenticatable
{
    /**
     * @var string
     */
    protected $table = 'vendor_payment_method';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_id',
        'account_holder',
        'account_number',
        'bank_name',
        'bank_code',
        'type',
        'status',
    ];


    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
