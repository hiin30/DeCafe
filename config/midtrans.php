<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/env.php';

// Set your Midtrans Server Key
\Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY') ?: '';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = (getenv('MIDTRANS_IS_PRODUCTION') === 'true');
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

?> 