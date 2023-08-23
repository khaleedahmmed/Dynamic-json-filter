<?php

return [
    'DataProviderX' => [
        'parentAmount' => 'balance',
        'Currency' => 'currency',
        'parentEmail' => 'email',
        'statusCode' => 'status',
        'registerationDate' => 'created_at',
        'parentIdentification' => 'id',
    ],
    'DataProviderY' => [
        'balance'=> 'balance',
        'currency'=>'currency',
        'email'=>'email',
        'status'=>'status',
        'status_mapping' => [
            100 => 1, // Map status codes in 'y' to 'x' values
            200 => 2,
            300 => 3,
        ],
        'created_at'=> 'created_at',
        'id'=> 'id'
    ],
];
