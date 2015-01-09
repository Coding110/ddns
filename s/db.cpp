#include <stdio.h>
#include <stdlib.h>
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
		return -1; // internal error
	}

	int row = 0, column = 0;
	char **result = NULL;

	// query if the domain is exist.
	sprintf(sql, "select count(*) from records where name=\"%s\";",
		domain);
	if(SQLITE_OK != sqlite3_get_table(pDb, sql, &result, &row, &column, &err_msg)){
		return -1;
	}
	if(atoi(result[1]) == 0)
	{
    	sqlite3_free_table(result);
		return 1; // have not such record
	}
    sqlite3_free_table(result);
	
	// query if the domain's ip is the same.
	sprintf(sql, "select count(*) from records where name=\"%s\" and content=\"%s\";",
		domain, host);
	if(SQLITE_OK != sqlite3_get_table(pDb, sql, &result, &row, &column, &err_msg)){
		return -1;
	}
	if(atoi(result[1]) == 1)
	{
    	sqlite3_free_table(result);
		return 2; // the record still fresh, does not need to update
	}
    sqlite3_free_table(result);

	// update 
	sprintf(sql, "update records set content = \"%s\" where name = \"%s\" and content <> \"%s\";",
		host, domain, host);

	if(SQLITE_OK != sqlite3_exec(pDb, sql, 0, 0, &err_msg))
	{
		ret = -1;
	}

	sqlite3_close(pDb);

	return ret;
}

