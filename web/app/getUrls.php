<?php
session_start();
include(dirname(__FILE__) . "/../database.php");

function json_response($message = null, $code = 200){
    header_remove();
    http_response_code($code);

    header('Content-Type: application/json');
    $status = array(
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
        );
    header('Status: '.$status[$code]);
    return json_encode(array(
        'status' => $code < 300, // success or not?
        'message' => $message
        ));
}

if(!isset($_SESSION['username'])){
    echo json_response("You need to login",401);
    exit();
}

$query = "SELECT * FROM urls";
$stmt = $db->query($query);
$urls = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($urls as &$el) {
    $el['url_header'] = html_entity_decode($el['url_header'],ENT_QUOTES);
    $el['url_body'] = html_entity_decode($el['url_body'],ENT_QUOTES);
}
echo json_response($urls);