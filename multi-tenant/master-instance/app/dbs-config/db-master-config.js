const Sequelize = require('sequelize');

const sequelize = new Sequelize("master", "root", "password", {

    host: "localhost",
    port: 3306,
    dialect: 'mysql',

    pool: {
        max: 5,
        min: 0,
        acquire: 30000,
        idle: 10000
    },
});

module.exports = sequelize;