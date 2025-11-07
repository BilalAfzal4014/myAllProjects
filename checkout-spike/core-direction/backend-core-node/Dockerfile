FROM node:14-alpine3.10

WORKDIR /app

ADD . /app

RUN npm install

ENV DB_CLIENT mysql
ENV DB_HOST localhost
ENV DB_PORT 3306
ENV DB_NAME febys-db
ENV DB_USER root
ENV DB_PASSWORD my-secret-pw
ENV APP_PORT 80

EXPOSE 80

CMD ["npm", "start"]
