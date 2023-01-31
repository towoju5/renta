<?php

return [
    'currencies' => [
        'NGN'
    ],
    'coefficient' => [
        'NGN' => 1
    ],
    'exchange' => [
        // 'COI' => [
        //     'POI' => 100,
        //     'CNY' => 1
        // ],
        // 'CNY' => [
        //     'COI' => 1
        // ]
    ],

    'transaction' => [
        'table' => 'transactions',
        'model' => \Moecasts\Laravel\Wallet\Models\Transaction::class,
    ],
    'transfer' => [
        'table' => 'transfers',
        'model' => \Moecasts\Laravel\Wallet\Models\Transfer::class,
    ],
    'wallet' => [
        'table' => 'wallets',
        'model' => \Moecasts\Laravel\Wallet\Models\Wallet::class,
        'default' => [
            'currency' => 'NGN'
        ],
    ],
];
