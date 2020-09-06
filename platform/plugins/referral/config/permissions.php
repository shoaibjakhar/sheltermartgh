<?php

return [
    [
        'name' => 'Referral Mangement',
        'flag' => 'referral.index',
    ],
    [
        'name'        => 'referral',
        'flag'        => 'referral.index',
        'parent_flag' => 'referral.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'referral.create',
        'parent_flag' => 'referral.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'referral.edit',
        'parent_flag' => 'referral.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'referral.destroy',
        'parent_flag' => 'referral.index',
    ],

    
];