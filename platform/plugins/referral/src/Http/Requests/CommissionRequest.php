<?php

namespace Botble\Referral\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CommissionRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'property'        => 'required',
            'property_type'        => 'required',
            'price'       => 'required',
            'commission'      => 'required',
            'admin_commission'      => 'required',
            'client_commission'      => 'required',
            'vendor_commission'      => 'required',
        ];
    }
}
