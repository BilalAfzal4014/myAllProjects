#!/bin/bash
cd /var/www/backend-core-node
sudo npm install
sudo npm fund
sudo pm2 kill
sudo NODE_ENV=dev pm2 start server.js --update-env --exp-backoff-restart-delay=100 -i max
