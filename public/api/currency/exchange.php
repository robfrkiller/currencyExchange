<?php

require __DIR__ . '/../../../vendor/autoload.php';

use App\Lib\Currency;
use App\Lib\CurrencyException;

try {
    $amount = isset($_GET['amount']) && is_numeric($_GET['amount'])
        ? (float) $_GET['amount']
        : null;

    if (!(
        isset($_GET['src'], $_GET['dst'])
        && is_string($_GET['src'])
        && is_string($_GET['dst'])
        && is_float($amount)
        )
    ) {
        throw new InvalidArgumentException('Parameter error.');
    }

    Currency::init();

    $srcCurrency = $_GET['src'];
    $dstCurrency = $_GET['dst'];

    $data = [
        'success' => true,
        'message' => '',
        'result' => number_format(Currency::exchange($srcCurrency, $dstCurrency, $amount), 2),
    ];
} catch (CurrencyException | InvalidArgumentException $e) {
    $data = [
        'success' => false,
        'message' => $e->getMessage(),
        'result' => '',
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
