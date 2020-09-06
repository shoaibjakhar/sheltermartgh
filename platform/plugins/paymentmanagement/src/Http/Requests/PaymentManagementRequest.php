<?php

namespace Botble\Paymentmanagement\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PaymentManagementRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|max:255',
            'description' => 'max:400',
            'categories'  => 'required',
            'slug'        => 'required|max:255',
            'status'      => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
