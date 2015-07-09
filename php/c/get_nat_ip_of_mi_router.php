<?php

function login_url()
{
	return "http://miwifi.com/cgi-bin/luci/api/xqsystem/login";
}

function logout_url()
{
	return "";
}

function wan_info_url($token)
{
	return "http://miwifi.com/cgi-bin/luci/;stok=$token/api/xqnetwork/wan_info";
}

function login_for_token($username, $password)
{
	$url = login_url();
	$cmd = "curl -s --data \"username=$username&password=$password&logtype=2\" \"$url\" | awk -F\",\" '{print $2}' | awk -F\"\\\"\" '{print $4}'";
	//echo "$cmd\n";
	$result = exec($cmd);
	return $result;
}

function get_nat_ip($token)
{
	$url = wan_info_url($token);
	$cmd = "curl -s \"$url\" | awk -F\",\" '{print $16}' | awk -F\"\\\"\" '{print $4}'";
	$result = exec($cmd);
	return $result;
}

function main($argc, $argv)
{
	if($argc != 3){
		echo "Error";
		return ;
	}
	$token = login_for_token($argv[1], $argv[2]);
	//echo "$token\n";
	$ip = get_nat_ip($token);
	echo "$ip\n";
}

main($argc, $argv);

?>
