
CXX=g++
TARG=main
LIB=-L/usr/local/fcgi/lib -lfcgi -lsqlite3
INC=-I/usr/local/fcgi/include
SRC=main.cpp common.cpp ddns.cpp db.cpp config.cpp
DEBUG=-g

all:
	$(CXX) $(SRC) -o $(TARG) $(LIB) $(INC) $(DEBUG)

