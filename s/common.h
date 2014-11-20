#ifndef _COMMON_H_
#define _COMMON_H_

#include <iostream>
#include <vector>
#include <string>
using namespace std;

typedef struct _key_value_t{
	string key;
	string value;
}key_value_t;

int parse_get_query(char *query_string, vector<key_value_t> &kvs);
const char *get_value_by_key(vector<key_value_t> &kvs, const char *key);

#endif

