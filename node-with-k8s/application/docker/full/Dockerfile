FROM node:19

WORKDIR /usr/src/app

RUN npm install -g nodemon

COPY ../.. .

CMD ["nodemon", "server.js"]

EXPOSE 3000