#!/bin/bash
# On development.
#sudo chown ima.www-data /data/demo_codebase -R
# On production-server.
#sudo chown admin.www-data /data/demo_codebase -R

# Make sure that Apache have wite access in the below directories.
sudo chmod g+wx /data/demo_codebase/log/
sudo chmod g+w /data/demo_codebase/log -R

# Also for sites
sudo chmod g+wx /data/www/api.headsetservice.dk/pages/cache/
sudo chmod g+w  /data/www/api.headsetservice.dk/pages/cache/ -R

sudo chmod g+wx /data/www/api.headsetservice.dk/pages/templates_c/
sudo chmod g+w  /data/www/api.headsetservice.dk/pages/templates_c/ -R

