#include <iostream>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#include "ddns.h"
#include "db.h"
#include "common.h"

#define JSON_STR_SIZE 256
typedef struct _http_result_t
{
	int result;
	string nat_host;
	string error;
}http_result_t;

char *result_json(http_result_t &http_result);
int exec_shell(char **buf, int buf_len);

/*
 * 	对GET参数进行处理
 * 	@return: string, result in json format data, need delete[] by caller
 * 		such as:
 * 		{
 * 			"result":0, // 0 - OK, 1 - Failed
 * 			"error":null // string or NULL
 * 		}
 */
char *http_query_proc(vector<key_value_t> &kvs, char *remote_ip)
{
	http_result_t http_result;
	http_result.result = 0;
	if(remote_ip) http_result.nat_host = remote_ip;

	const char *method = get_value_by_key(kvs, "md");	
	if(method == NULL)
	{
		http_result.result = 1;
		http_result.error = "Bad request";
		return result_json(http_result);
	}

	if(strncmp(method, "ddns", 4) == 0){
	// ddns
		const char *domain = get_value_by_key(kvs, "dm");
		const char *host = get_value_by_key(kvs, "host");
		if(domain == NULL || host == NULL || strlen(domain) < 1 || strlen(host) < 7){
			http_result.result = 1;
			http_result.error = "Incomplete or error setting";
		}
		if(strncmp(host, "default", 6) == 0){
			// 此时把连接另一端的IP设为对应域名的IP
			if(remote_ip){
				host = remote_ip;
			}else{
				host = "0.0.0.0";
			}
		}

		int ret = dns_update(domain, host);
		if(ret < 0){
			http_result.result = 1;
			http_result.error = "Internal error";
		}else if(ret == 1){
			http_result.result = 1;
			http_result.error = "Recorde can't find";
		}else if(ret == 2){
			//char *buferr = new char[1024]; // for test
			//memset(buferr, 0, 1024);
			//sprintf(buferr, " - none");
			//exec_shell(&buferr, 1024);
			http_result.result = 0;
			http_result.error = "Recorde still fresh.";
			//http_result.error = "Recorde still fresh. " + string(buferr);
			//delete[] buferr;
		}else if(ret == 0){
			char *buferr = new char[1024]; // for test
			//sprintf(buferr, " - none");
			memset(buferr, 0, 1024);
			exec_shell(&buferr, 1024);
			http_result.result = 0;
			http_result.error = "Recorde refreshed. " + string(buferr);
			delete[] buferr;
		}else{
		}
	}else{
		http_result.result = 1;
		http_result.error = "unsupported method";
	}
	return result_json(http_result);
}

char *result_json(http_result_t &http_result)
{
	char *result = new char[JSON_STR_SIZE];
	snprintf(result, JSON_STR_SIZE, "{\"reslut\":%d,\"nat-host\":\"%s\", \"error\":\"%s\"}", 
		http_result.result, http_result.nat_host.c_str(), http_result.error.c_str());
	return result;
}

int exec_shell(char **buf, int buf_len)
{
    //FILE *pp = popen("./sbin/cmd.sh 2>&1", "r");
    FILE *pp = popen("ddnscmd 2>&1", "r");
	if(pp == NULL){
		sprintf(*buf, " - failed");
		return 0;
	}
    fgets(*buf, buf_len, pp);

	//char cwd[1024] = {0};
	//getcwd(cwd, 1024);
	//sprintf(*buf+strlen(*buf), ", current dir: %s", cwd);

    pclose(pp);
    return 0;
}
