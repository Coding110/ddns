<?php

if(!defined('DDNS_ROOT_PATH'))
{
	header("HTTP/1.1 403 Forbidden");
	exit();
}

/* 
 *	Class for mysql/mariadb
 */
class MysqlDB
{
	public $mysqli;

	function __construct()
	{
	}

	function open($host, $usr, $passwd, $db)
	{
		$this->mysqli = new mysqli($host, $usr, $passwd, $db);
	}

	function close()
	{
		$this->mysqli->close();
	}

	function create_new_zone($domain)
	{
		$sql = "INSERT INTO domains (name, type) values ('$domain', 'NATIVE');";
		if($this->mysqli->query($sql) == false)
		{
			return false;
		}
		$id = $this->mysqli->insert_id;
		$sql = "INSERT INTO records (domain_id, name, content, type,ttl,prio)". 
			"VALUES ($id,'$domain','localhost ahu@ds9a.nl 1','SOA',86400,NULL);";
		return $this->mysqli->query($sql);
	}

	function add_record($domain_id, $sub_domain, $type, $content)
	{
		$sql = "INSERT INTO records (domain_id, name, content, type,ttl,prio) ".
			   "VALUES ($domain_id,'$sub_domain','$content','$type',120,NULL);";
		return $this->mysqli->query($sql);
	}

	function update_record($sub_domain, $content)
	{
		$sql = "update records set content = '$content' where name = '$sub_domain';";
		return $this->mysqli->query($sql);
	}

	function get_zones()
	{
		$sql = "select id, name, type from domains;";
		if($result = $this->mysqli->query($sql))
		{
			$rows = array();
			while($row = $result->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			return $rows;
		}else{
			//echo "Get zones failed, ";
			//echo $this->mysqli->error."\n";
			return false;
		}
	}

	function get_records($domain_id)
	{
		$sql = "select id, name, type, content from records where domain_id = $domain_id";
		if($result = $this->mysqli->query($sql))
		{
			$rows = array();
			while($row = $result->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			return $rows;
		}else{
			//echo "Get zones failed, ";
			//echo $this->mysqli->error."\n";
			return false;
		}
	}

	function exec_sql_file($sql_file)
	{
		$commands = file_get_contents($sql_file);
		return $this->mysqli->multi_query($commands);
	}

	function init_check()
	{
		$sql = "show tables like 'domains';";
		return $this->mysqli->query($sql);
	}
}

/* 
 *	Class for sqlite3	
 */
class SqliteDB
{
	
	function sqlite_open($location,$mode) 
	{ 
	    $handle = new SQLite3($location, $mode); 
	    //$handle = new SQLite3(); 
		//$handle->open($location, $mode);
	    return $handle; 
	} 
	
	function sqlite_query($dbhandle,$query) 
	{ 
	    //$array['dbhandle'] = $dbhandle; 
	    //$array['query'] = $query; 
	    $result = $dbhandle->query($query); 
	    return $result; 
	} 
	
	function sqlite_update_dns($dbhandle, $id, $ip)
	{
		$sql = "update records set content = \"".$ip."\" where id = $id;";
		echo "update SQL: $sql\n";
	    $result = $dbhandle->exec($sql); 
	    //$result = $dbhandle->query($sql); 
		var_dump($result);
	}
	
	function sqlite_fetch_array(&$result,$type) 
	{ 
	    #Get Columns 
	    $i = 0; 
	    while ($result->columnName($i)) 
	    { 
	        $columns[ ] = $result->columnName($i); 
	        $i++; 
	    } 
		//print_r($columns);
	    
	    $resx = $result->fetchArray(SQLITE3_ASSOC); 
	    return $resx; 
	} 
}

