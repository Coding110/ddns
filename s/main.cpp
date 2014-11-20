#include <stdio.h>
#include <unistd.h>
#include <sys/types.h>
#include <fcgiapp.h>
#include <fcgi_stdio.h>
#include <stdlib.h>
#include <string.h>

#include "common.h"

int request_post(FCGX_Stream *in, FCGX_Stream *out, FCGX_ParamArray *envp);
int request_get(FCGX_Stream *in, FCGX_Stream *out, FCGX_ParamArray *envp);
int post_data_handle(char* buf, int buflen, char* contentSplit,FCGX_Stream *out);

void test();
void key_values_print(vector<key_value_t> &kvs);

int main(int argc, char *argv[])
{
	FCGX_Stream *in;
	FCGX_Stream *out;
	FCGX_Stream *err;
	FCGX_ParamArray envp;

	char *method = NULL;
	while(FCGX_Accept(&in, &out, &err, &envp) >= 0)
	{
		method = FCGX_GetParam("REQUEST_METHOD", envp);
		if(strcmp(method, "POST") == 0){
			request_post(in, out, &envp);
		}else if(strcmp(method, "GET") == 0){
			request_get(in, out, &envp);
		}

	}

	FCGX_Finish();

	test();

	return 0;
}

void test()
{
	//printf("hello\n");
	char query[] = "md=d&dm=www.becktu.com&host=2.3.4.5";
	vector<key_value_t> kvs;
	parse_get_query(query, kvs);
	key_values_print(kvs);
	kvs.clear();

	char query1[] = "md=&dm=www.becktu.com&host=2.3.4.5";
	parse_get_query(query1, kvs);
	key_values_print(kvs);
	kvs.clear();

	char query2[] = "md=&dm=www.becktu.com&host";
	parse_get_query(query2, kvs);
	key_values_print(kvs);
	kvs.clear();
}

void key_values_print(vector<key_value_t> &kvs)
{
	int len = kvs.size();
	printf("Key value set, size(%d):\n", len);
	printf("No.\tKey\tValue\n");
	for(int i = 0; i < len; i++)
	{
		printf("%d\t%s\t%s\n", i, kvs[i].key.c_str(), kvs[i].value.c_str());
	}
}

int request_get(FCGX_Stream *in, FCGX_Stream *out, FCGX_ParamArray *envp)
{
	char *query_string = FCGX_GetParam("QUERY_STRING", *envp);
    FCGX_FPrintF(out, "Content-type: text/html\r\n"
    	"\r\n"
    	""
		"query: %s", query_string);
	return 0;
}


#define MAX_LEN 0xfff
int request_post(FCGX_Stream *in, FCGX_Stream *out, FCGX_ParamArray *envp)
{
	// have not test
	char *contentSplit;  
	char *buffer = NULL;
  
    char *content_type = FCGX_GetParam("CONTENT_TYPE", *envp); 
    contentSplit = strchr(content_type,'=')+1; 
    char *content_length = FCGX_GetParam("CONTENT_LENGTH", *envp); 
	int len = 0;

    if(content_length != NULL)  
    {  
        len = strtol(content_length, NULL, 10);  
        if(len == 0)  
        {  
			FCGX_FPrintF(out, "No data posted\n");  
        }else  
        {  
            if(len > MAX_LEN)                     
                len = MAX_LEN;  
            buffer = (char*)malloc(len);  
            if(buffer)  
            {  
                for(int i=0;i<len;i++)  
                {  
                    buffer[i] = FCGX_GetChar(in);  
                }  
                post_data_handle(buffer,len,contentSplit,out);  
                free(buffer);  
            }  
        }  
    }
	return 0;
}

int post_data_handle(char* buf, int buflen, char* contentSplit,FCGX_Stream *out)
{
	int len;
	int s;
	char* p;

	len = buflen;
	p = buf;
	//s = findstr(p,len,contentSplit); 
	if(s == -1)
		return -1;

	len -= s;
	p += s;
	while(1)
	{
		//s = findstr(p,len,contentSplit);
		if(s == -1)
			break;

		//ParserData(p,s-strlen(contentSplit)-4,out); 

		len -= s;
		p+= s;
	}
	return 0;
}