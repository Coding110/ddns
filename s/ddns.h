#ifndef _DDNS_H_
#define _DDNS_H_

#include "common.h"

/*
 *	DDNS 
 *
 *	Query parameters define:
 *		usr - user (Absence)
 *		passwd - password (Absence)
 *		md - method, include 'ddns', 'checkip', and so on. (Requried)
 *		dm - domain for ddns update. (Optional)
 *		host - host ip of domain. (Optional)
 *
 */

char *http_query_proc(vector<key_value_t> &kvs, char *remote_ip);

#endif

