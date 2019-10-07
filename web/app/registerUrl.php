<?php
session_start();

function json_response($message = null, $code = 200){
    header_remove();
    http_response_code($code);
    //header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
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

include(dirname(__FILE__) . "/../database.php");
$_POST = json_decode(file_get_contents('php://input'), true);
$url = $db->quote($_POST['url']);
$query = "INSERT INTO urls (url_url) VALUES ({$url})";
$stmt = $db->query($query);
$stmt->closeCursor();
echo json_response($stmt,201);