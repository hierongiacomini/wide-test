<?php
session_start();

function remove_utf8_bom_head($text) {
    if(substr(bin2hex($text), 0, 6) === 'efbbbf') {
        $text = substr($text, 3);
    }
    return $text;
}

include(dirname(__FILE__) . "/database.php");
$query = "SELECT url_id,url_url FROM urls WHERE url_checked=0";

$stmt = $db->query($query);
$rows = $stmt->fetchAll();
$totalRows = $stmt->rowCount();
for ($i=0;$i < $totalRows; $i++){
    $ch = curl_init();
    $row = $rows[$i];
    echo $row['url_url'];
    curl_setopt($ch, CURLOPT_URL, $row['url_url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,5);
    curl_setopt($ch, CURLOPT_BUFFERSIZE, 10485764);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    $body = utf8_encode($body);
    $body = remove_utf8_bom_head($body);
    $body = htmlentities($body,ENT_QUOTES);
    $header = str_replace('\r\n', '<br>', $header);
    $header = htmlentities(nl2br($header),ENT_QUOTES);
    $query = "UPDATE urls SET url_checked=1, url_code='{$httpcode}', url_header='{$header}',url_body='{$body}' WHERE url_id='{$row['url_id']}'";
    $Nstmt = $db->query($query);
    curl_close($ch);
}
exit();
?>