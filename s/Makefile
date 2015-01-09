
CXX=g++
TARG=main
LIB=-L/usr/local/fcgi/lib -lfcgi -lsqlite3
INC=-I/usr/local/fcgi/include
COM_SRC=common.cpp ddns.cpp db.cpp config.cpp
MAIN_SRC=main.cpp 
TEST_SRC=test.cpp 
DEBUG=-g

all:
	$(CXX) $(MAIN_SRC) $(COM_SRC) -o $(TARG) $(LIB) $(INC) $(DEBUG)

test:
	$(CXX) $(TEST_SRC) $(COM_SRC) -o test $(LIB) $(INC) $(DEBUG)
