<?php

if(!defined('DDNS_ROOT_PATH') && php_sapi_name() != "cli")
{
	header("HTTP/1.1 403 Forbidden");
	exit();
}

define('DDNS_ROOT_PATH', './');
/*
 *	Loacl Tool
 *		Initial DNS database
 *		Create new zone
 *		Modify DNS
 */

require_once("common.php");

function usage($command)
{
	print "Usage: php $command <cmd> [option [...]]\n";
	print "\n";
	print "cmd:\n";
	print "  init    initial powderdns database, option: <sql_file>\n";
	print "  list    list zones or records of a domain, option: <zone|domain_id>\n";
	print "  new     create a new zone, option: <domain>\n";
	print "  add     add a record for zone, option: <domain id> <sub_domain> <type> <content>\n";
	print "  update  update a record for zone, option: <sub_domain> <content>\n";
	print "  help	 show this information.\n";
	print "\n";
	exit();
}

main($argc, $argv);
function main($argc, $argv)
{
	if($argc == 1){
		usage($argv[0]);
	}


	if($argv[1] == "init")
	{
		if($argc != 3){
			usage($argv[0]);
		}
		if(init_pdns($argv[2]) == true){
			echo "Powerdns's database initialize success.\n";
		}else{
			echo "Powerdns's database initialize failed or has initialized..\n";
		}
	}
	else if($argv[1] == "new")
	{
		if($argc != 3){
			usage($argv[0]);
		}
		if(new_zone($argv[2]) == true){
			echo "Create new zone for '$argv[2]' success.\n";
		}else{
			echo "Create new zone for '$argv[2]' failed.\n";
		}
	}
	else if($argv[1] == "add")
	{
		if($argc != 6){
			usage($argv[0]);
		}
		if(add_record($argv[2],$argv[3],$argv[4],$argv[5]) == true){
			echo "Add record '$argv[3]' for '$argv[2]' success.\n";
		}else{
			echo "Add record '$argv[3]' for '$argv[2]' failed.\n";
		}
	}
	else if($argv[1] == "update")
	{
		if($argc != 4){
			usage($argv[0]);
		}
		if(update_record($argv[2],$argv[3]) == true){
			echo "Update record '$argv[2]' success.\n";
		}else{
			echo "Update record '$argv[2]' failed.\n";
		}
	}
	else if($argv[1] == "list")
	{
		if($argc != 3){
			usage($argv[0]);
		}
		if($argv[2] == "zone")
		{
			$res = list_domains();
			printf(" id    domain\n");
			printf("------------------------\n");
			foreach( $res as $i)
			{
				printf(" %-6d%s\n", $i['id'], $i['name']);
			}
		}
		else if(is_numeric($argv[2]))
		{
			
			$res = list_domain_records($argv[2]);
			printf(" %-6s%-24s%-8s%-20s\n", "id", "domain", "type", "content");
			printf("-----------------------------------------------------------\n");
			foreach( $res as $i)
			{
				printf(" %-6s%-24s%-8s%-20s\n", $i["id"], $i["name"], $i["type"], $i["content"]);
			}
		}else{
			usage($argv[0]);
		}
	}
	else if($argv[1] == "help")
	{
		usage($argv[0]);
	}
	else
	{
		usage($argv[0]);
	}
}


