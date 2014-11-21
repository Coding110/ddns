#include <stdio.h>
#include <sqlite3.h>
#include "db.h"



int dns_update(const char* domain, const char *host)
{
	const char *dbfile = "/usr/local/pdns/var/dns.db";
	char sql[1024];
	
	sqlite3 *pDb = NULL;
	char *err_msg = NULL;

	int ret = 0;

	if(SQLITE_OK != sqlite3_open(dbfile, &pDb))
	{
		return -1;
	}

	sprintf(sql, "update records set content = \"%s\" where name = \"%s\" and content <> \"%s\";",
		host, domain, host);

	if(SQLITE_OK != sqlite3_exec(pDb, sql, 0, 0, &err_msg))
	{
		ret = -1;
	}

	sqlite3_close(pDb);

	return ret;
}

