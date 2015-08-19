<?php

global $g_key_word;
$g_key_word = "";

main($argc, $argv);
function main($argc, $argv)
{
	$domain = "";
	//$host = get_nat_ip_138();
	$host = get_nat_ip_becktu();
	
	if($argc == 2){
	    $domain = $argv[1];
	}else if($argc == 3){
	    $domain = $argv[1];
	    $host = $argv[2];
	}

	$ts = time();
	$key = create_key($ts, $domain);
	//echo $key."\n";

	$url = "http://dyn.becktu.com:8080/ddns?md=update&dm=$domain&host=$host&ts=$ts&key=$key";
	echo $url."\n";

	$msg = file_get_contents($url);
	echo $msg."\n";
	Logging($msg);
}

function create_key($ts, $domain)
{
	global $g_key_word;
	$str = $ts.$g_key_word.$domain;
	$md5str = md5($str);
	$key = $md5str;
	return $key;
}

function main_old($argc, $argv)
{
	$domain = "";
	//$host = "default";
	$host = get_nat_ip();
	
	if($argc == 2){
	    $domain = $argv[1];
	}else if($argc == 3){
	    $domain = $argv[1];
	    $host = $argv[2];
	}
	
	$url = "http://www.becktu.com:1080/main.cgi?md=ddns&dm=".$domain."&host=".$host;
	$msg = file_get_contents($url);
	Logging($msg);
}

function Logging($log)
{
	date_default_timezone_set("PRC");
	$logfile = "/home/hua/bin/ddns_client.log";
	$date = date('Y-m-d H:i:s')." - ";
	file_put_contents($logfile, $date.$log."\n", FILE_APPEND);
}

function get_nat_ip()
{
	$dyn_check_ip_url = "http://opendata.baidu.com/api.php?query=ip&resource_id=6006";
	$msg = file_get_contents($dyn_check_ip_url);
	$host_info = json_decode($msg);
	return $host_info->data[0]->origip;
}

function get_nat_ip_138()
{
	$dyn_check_ip_url = "http://1111.ip138.com/ic.asp";
	$msg = file_get_contents($dyn_check_ip_url);
	$start = stripos($msg, "[");
	$end = stripos($msg, "]");
	$ip = substr($msg, $start+1, $end - $start - 1);
	//echo "|$ip|\n";
	return $ip;
}

function get_nat_ip_becktu()
{
	$dyn_check_ip_url = "http://dyn.becktu.com:8080/getip";
	$msg = file_get_contents($dyn_check_ip_url);
	$host_info = json_decode($msg);
	return $host_info->ip;
}

?>
