#!/bin/bash
cd /var/www/v3-web-app
rm -rf node_modules
rm -rf package-lock.json
sudo yarn install
sudo yarn build
