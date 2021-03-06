<?php

// qiwi addresses. Because HMAC isn`t working
$addr_range1 = range(ip2long('79.142.16.1'), ip2long('79.142.31.254'));
$addr_range2 = range(ip2long('195.189.100.1'), ip2long('195.189.103.254'));
$addr_range3 = range(ip2long('91.232.230.1'), ip2long('91.232.231.254'));
$addr_range4 = range(ip2long('91.213.51.1'), ip2long('91.213.51.254'));

if(!in_array(ip2long($_SERVER['REMOTE_ADDR']), $addr_range1) && !in_array(ip2long($_SERVER['REMOTE_ADDR']), $addr_range2) && !in_array(ip2long($_SERVER['REMOTE_ADDR']), $addr_range3) && !in_array(ip2long($_SERVER['REMOTE_ADDR']), $addr_range4)) {
    exit();
}

if (file_exists("../../../init.php")) {
    require_once("../../../init.php");
} else {
    require_once("../../../dbconnect.php");
    require_once("../../../includes/functions.php");
}
require_once("../../../includes/gatewayfunctions.php");
require_once("../../../includes/invoicefunctions.php");

require_once 'qiwi_p2p_functions.php';

$module = 'qiwi_p2p';
$vars = getGatewayVariables($module);

$data = json_decode(file_get_contents('php://input'), true);
//$headers = getallheaders();
//$headers = array_change_key_case($headers, CASE_LOWER);    // Because sometimes qiwi send header in different case
//$hmac_hash = $headers['x-api-signature-sha256'];  
//$my_hmac_hash = get_hash($data, $vars['secret']);

$request = json_decode(file_get_contents('php://input'), true)['bill'];

/*if(strcmp($hmac_hash, $my_hmac_hash) !== 0) {
    echo '{"error": 151}';
    exit();
}*/

$id = substr($request['billId'], 1);
$id = checkCbInvoiceID($id, $vars['name']);
checkCbTransID($request['billId']);
$result = mysql_fetch_array(select_query( "tblinvoices", "id", ["id" => $id]));

if(!$result['id']) {
    echo '{"error": 300}';
    exit();
}

if(strcmp($request['status']['value'], 'PAID') !== 0) {
    echo '{"error": 5}';
    exit();
}

if(intval($request['amount']['value']) === 0) {
    echo '{"error": 5}';
    exit();
}


echo '{"error": 0}';
logTransaction($module, $request, 'Success');

$command = 'AddInvoicePayment';
$postData = array(
    'invoiceid' => (string) $id,
    'transid' => $request['billId'],
    'gateway' => $vars['name'],
    'date' => date('Y-m-d H:i:s'),
);
$adminUsername = 'ADMIN_NAME'; 
localAPI($command, $postData, $adminUsername);

