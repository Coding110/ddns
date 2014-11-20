
CXX=g++
TARG=main
LIB=-L/usr/local/fcgi/lib -lfcgi
INC=-I/usr/local/fcgi/include
SRC=main.cpp common.cpp ddns.cpp db.cpp config.cpp

all:
	$(CXX) $(SRC) -o $(TARG) $(LIB) $(INC)

