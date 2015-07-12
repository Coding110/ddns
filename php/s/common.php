<?php

if(!defined('DDNS_ROOT_PATH'))
{
	header("HTTP/1.1 403 Forbidden");
	exit();
}

require_once("DdnsModel.php");

function open()
{
	$db_host 	 = ""; 
	$db_user 	 = ""; 
	$db_password = ""; 
	$db_name 	 = ""; 

	$handle = new MysqlDB();
	$handle->open($db_host, $db_user, $db_password, $db_name);
	return $handle;
}

function close($handle)
{
	$handle->close();
	unset($handle);
}

function init_pdns($sql_file)
{
	// check if already initialized.
	$ret = true;
	$h = open();
	if($h->init_check() == false)
	{
		if($h->exec_sql_file($sql_file) == true)
		{
			$ret = true;
		}
		else
		{
			$ret = false;
		}
	}
	else
	{
		$ret = false;
	}
	close($h);
	return $ret;
}

function new_zone($domain)
{
	$h = open();
	$ret = $h->create_new_zone($domain);
	close($h);
	return $ret;
}

function add_record($domain_id, $sub_domain, $type, $content)
{
	$h = open();
	$ret = $h->add_record($domain_id, $sub_domain, $type, $content);
	close($h);
	return $ret;
}

function update_record($sub_domain, $content)
{
	$h = open();
	$ret = $h->update_record($sub_domain, $content);
	close($h);
	return $ret;
}

function list_domains()
{
	$h = open();
	$result = $h->get_zones();
	close($h);
	return $result;
}

function list_domain_records($domain_id)
{
	$h = open();
	$result = $h->get_records($domain_id);
	close($h);
	return $result;
}
