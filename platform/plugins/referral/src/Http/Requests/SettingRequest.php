<?php

namespace Botble\Referral\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SettingRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vendor_commission'        => 'required|max:255',
            'Userclient_commission'        => 'required|max:255',
            'admin_commission'        => 'required|max:255',
            'status'      => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
