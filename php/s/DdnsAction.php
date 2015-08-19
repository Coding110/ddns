<?php

if(!defined('DDNS_ROOT_PATH'))
{
	header("HTTP/1.1 403 Forbidden");
	exit();
}

require_once('common.php');

global $g_key_word;
$g_key_word = "";

/*
 *	PATH
 *		/ddns		
 *	POST parameters
 *		md: method
 *		dm: domain
 *		host: host
 *		ts: time stamp
 *		key: key
 */
function ddns_enter()
{
	$uri = $_SERVER['REQUEST_URI'];
	$path = parse_url($uri, PHP_URL_PATH);

	if($path == "/ddns"){
		$PARAM = parse_query($uri);
		$PARAM['md'] = isset($PARAM['md'])?$PARAM['md']:'';
		$PARAM['dm'] = isset($PARAM['dm'])?$PARAM['dm']:'';
		$PARAM['host'] = isset($PARAM['host'])?$PARAM['host']:'';
		$PARAM['ts'] = isset($PARAM['ts'])?$PARAM['ts']:'';
		$PARAM['key'] = isset($PARAM['key'])?$PARAM['key']:'';

		if($PARAM['md'] == "update"){
			ddns_update($PARAM);
		}else{
		}
	}else if($path == "/getip"){
		get_origin_host_ip();
	}else{
		header("HTTP/1.1 403 Forbidden");
		return; 
	}
}

function ddns_update($param)
{
	if(check_valid($param['dm'], $param['ts'], $param['key']) != true)
	{
		header("HTTP/1.1 403 Forbidden");
		return; 
	}

	header("HTTP/1.1 200 OK");

	if(update_record($param['dm'], $param['host']) == true){
		echo "{err:0}";
	}else{
		echo "{err:1}";
	}
	return;
}

function get_origin_host_ip()
{
	header("HTTP/1.1 200 OK");
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    	$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	    $ip = $_SERVER['REMOTE_ADDR'];
	}
	echo "{\"ip\":\"$ip\"}";
}

function check_valid($domain, $timestamp, $key)
{
	global $g_key_word;
	$str = $timestamp.$g_key_word.$domain;
	$key_check = md5($str);
	if($key == $key_check){ 
		return true;
	}else{
		return false;
	}
}

function parse_query($var)
{
  /**
   *  Use this function to parse out the query array element from
   *  the output of parse_url().
   */
  $var  = parse_url($var, PHP_URL_QUERY);
  $var  = html_entity_decode($var);
  $var  = explode('&', $var);
  $arr  = array();

  foreach($var as $val)
   {
    $x          = explode('=', $val);
    $arr[$x[0]] = $x[1];
   }
  unset($val, $x, $var);
  return $arr;
}
