<?php

return [
    'webhooks' => [
        'customer' => env('ZAPIER_WEBHOOK_CUSTOMER'),
        'product' => env('ZAPIER_WEBHOOK_PRODUCT'),
        'invoice' => env('ZAPIER_WEBHOOK_INVOICE'),
    ],
];
