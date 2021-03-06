<?php

function qiwi_p2p_config() {
    $configarray = [
        "FriendlyName" => [
            "Type" => "System",
            "Value"=>"QIWI P2P"
        ],
        "secret" => [
            "FriendlyName" => "Секретный ключ",
            "Type" => "text",
            "Size" => "100",
            "Description" => "<b>Подсказка:</b> Секретный ключ из пары P2P"
        ],
        "public" => [
            "FriendlyName" => "Публичный ключ",
            "Type" => "text",
            "Size" => "100",
            "Description" => "<b>Подсказка:</b> Публичный ключ из пары P2P"
        ],
        "theme" => [
            "FriendlyName" => "Код стиля",
            "Type" => "text",
            "Size" => "100",
            "Description" => "<b>Подсказка:</b> Во вкладке \"Форма приема переводов\""
        ],
        "successURL" => [
            "FriendlyName" => "Ссылка для редиректа при успехе",
            "Type" => "text",
            "Size" => "100",
            "Description" => "<b>Подсказка:</b> Для редиректа клиентов после успешной оплаты"
        ],
    ];

    return $configarray;
}

function qiwi_p2p_link($params) {
    global $_LANG;

    $code = '
<form method="get" action="https://oplata.qiwi.com/create">
    <input type="hidden" name="publicKey" id="publicKey" value="'.$params['public'].'"/>
    <input type="hidden" name="billId" id="billId" value="b'.$params['invoiceid'].'"/>
    <input type="hidden" name="amount" id="amount" value="'.$params['amount'].'"/>
    <input type="hidden" name="successUrl" id="successURL" value="'.$params['successURL'].'"/>
    <input type="hidden" name="customFields[themeCode]" id="theme" value="'.$params['theme'].'"/>
    <input type="hidden" name="comment" id="comment" value="'.$params['description'].'"/>
    <input type="submit" value="'.$_LANG["invoicespaynow"].'" />
</form>
    ';

    return $code;
}