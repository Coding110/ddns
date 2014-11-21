<?php

$domain = "";
$host = "default";

if($argc == 2){
    $domain = $argv[1];
}else if($argc == 3){
    $domain = $argv[1];
    $host = $argv[2];
}

$url = "http://www.becktu.com:1080/main.cgi?md=ddns&dm=".$domain."&host=".$host;
$msg = file_get_contents($url);
Logging($msg);

function Logging($log)
{
    $logfile = "ddns_client.log";
    $date = date('Y-m-d H:i:s')." - ";
    file_put_contents($logfile, $date.$log."\n", FILE_APPEND);
}

?>
