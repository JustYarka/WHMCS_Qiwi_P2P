<?php

function normalize_amount($amount) {
    return number_format(round(floatval($amount), 2, PHP_ROUND_HALF_DOWN), 2, '.', '');
}

function get_hash(array $notificationBody, $merchantSecret) {
    $data = [
        (string) $notificationBody['bill']['amount']['currency'],
        (string) $notificationBody['bill']['amount']['value'],
        (string) $notificationBody['bill']['billId'],
        (string) $notificationBody['bill']['siteId'],
        (string) $notificationBody['bill']['status']['value']
    ];

    echo implode('|', $data), "\n";

    return hash_hmac('sha256', implode('|', $data), $merchantSecret);
}