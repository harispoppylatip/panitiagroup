<?php

return [
    'Merchant_ID' => env('MIDTRANS_Merchant_ID'),
    'Client_Key' => env('MIDTRANS_Client_Key'),
    'Server_Key' => env('MIDTRANS_Server_Key'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
];
