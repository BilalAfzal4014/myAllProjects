


#docker run -p 81:3306 -v /Users/bilalafzal/Documents/data-docker/mysql-data:/var/lib/mysql --name mysql-container --network chat-network mysql





docker run -p 81:3306 -v /Users/bilalafzal/Documents/data-docker/mysql-data:/var/lib/mysql --name mysql-container --network chat-network \
  -e MYSQL_ROOT_PASSWORD=root_password \
  -e MYSQL_DATABASE=my_database \
  -e MYSQL_USER=my_user \
  -e MYSQL_PASSWORD=my_user_password \
  -d mysql:8.0