#! /bin/sh

# ddns may not update ip immediately, daly to reload nginx.
# 1).reload nginx untill the ip is change
# 2).if the ip is same in 10 minutes after dig, reload nginx;
#
# $1: domain for check.
# $2: second for a check loop, suggest 10s. 

if [[ $# -ne 2 ]] ; then
	echo "Usage: $0 <domain> <second>";
	exit 1;
fi

domain=$1;
sec=$2;
i=1;
dig_time=0;
max_dig_time=600;

old_ip=`dig $domain | grep $domain | grep -v ";" | awk '{print $5}'`;
echo "old ip: $old_ip";
echo "domain: $domain";

while [[ $i -ne 0 ]]; do
	sleep $sec;
	let "dig_time+=$sec";
	echo "dig time: $dig_time";

	new_ip=`dig $domain | grep $domain | grep -v ";" | awk '{print $5}'`;
	echo "new ip: $new_ip";

	if [[ $old_ip != $new_ip ]]; then
		echo "do something";
		/usr/local/nginx/sbin/nginx -s reload
		break;
	fi;

	if [[ $dig_time -gt $max_dig_time ]]; then
		echo "Time out";
		break;
	fi;
done;

echo "Task over.";
