<?php

return [
    [
        'name' => 'Payments',
        'flag' => 'payment.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'payment.destroy',
        'parent_flag' => 'payment.index',
    ],

];
