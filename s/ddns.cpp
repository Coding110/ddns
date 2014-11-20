#include <string.h>
#include "ddns.h"
#include "db.h"
#include "common.h"

#define JSON_STR_SIZE 256
typedef struct _http_result_t
{
	int result;
	string error;
}http_result_t;

char *result_json(http_result_t &http_result);

/*
 * 	对GET参数进行处理
 * 	@return: string, result in json format data, need delete[] by caller
 * 		such as:
 * 		{
 * 			"result":0, // 0 - OK, 1 - Failed
 * 			"error":null // string or NULL
 * 		}
 */
char *http_query_proc(vector<key_value_t> &kvs)
{
	http_result_t http_result;
	http_result.result = 0;

	const char *method = get_value_by_key(kvs, "md");	
	if(method == NULL)
	{
		http_result.result = 1;
		http_result.error = "Bad request";
	}

	if(strncmp(method, "ddns", 4) == 0){
	// ddns
		const char *domain = get_value_by_key(kvs, "dm");
		const char *host = get_value_by_key(kvs, "host");
		if(domain == NULL || host == NULL){
			// error
		}
		if(host == "default"){
			// 此时把连接另一端的IP设为对应域名的IP
		}
		dns_update(domain, host);
	}else{
		http_result.result = 1;
		http_result.error = "unsupported method";
	}
	return result_json(http_result);
}

char *result_json(http_result_t &http_result)
{
	char *result = new char[JSON_STR_SIZE];
	snprintf(result, JSON_STR_SIZE, "{\"reslut\":%d,\"error\":\"%s\"}", 
		http_result.result, http_result.error.c_str());
	return result;
}

