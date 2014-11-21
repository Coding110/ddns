#include "common.h"

#include <stdio.h>
#include <string.h>

int parse_get_query(char *query_string, vector<key_value_t> &kvs)
{
	key_value_t kv;
	
	char *p = query_string, *key_point;
	char key[1024], value[1024];
	
	while(p)
	{
		while ( key_point = strsep(&p,"&"))
		{
			if (*key_point == 0)
				continue;
			else
				break;
		}

		memset(key, 0, 1024);
		memset(value, 0, 1024);
		sscanf(key_point,"%[^=]=%s", key, value); 
		kv.key = key;
		kv.value = value;
		kvs.push_back(kv);
		//printf("string: %s, key: %s, value: %s\n",key_point, key, value);
	}
	return 0;
}

const char *get_value_by_key(vector<key_value_t> &kvs, const char *key)
{
	int len = kvs.size();
	for(int i = 0; i < len; i++)
	{
		if(kvs[i].key.compare(key) == 0){
			//if(kvs[i].value.size > 0)
			return kvs[i].value.c_str();
			//else return NULL;
		}
	}
	return NULL;
}

