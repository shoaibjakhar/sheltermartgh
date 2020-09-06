<?php

return [
    [
        'name' => 'Payment Management',
        'flag' => 'plugins.paymentmanagement',
    ],
    [
        'name'        => 'paymentmanagement',
        'flag'        => 'paymentmanagement.index',
        'parent_flag' => 'paymentmanagement.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'paymentmanagement.create',
        'parent_flag' => 'paymentmanagement.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'paymentmanagement.edit',
        'parent_flag' => 'paymentmanagement.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'paymentmanagement.destroy',
        'parent_flag' => 'paymentmanagement.index',
    ],
];