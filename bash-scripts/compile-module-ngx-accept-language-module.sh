#!/bin/bash
wget http://nginx.org/download/nginx-1.27.tar.gz
gunzip nginx-1.27.tar.gz

# Unpack, configure and build
tar xzvf nginx-1.27.tar.gz
cd nginx-1.27

# Configure NginX
./configure --add-module=/usr/lib/nginx/modules/ngx_accept_language_module.so
make

# Install and finish
sudo make install

# Check version
nginx -v 

# /usr/lib/nginx/modules/ngx_accept_language_module.so
