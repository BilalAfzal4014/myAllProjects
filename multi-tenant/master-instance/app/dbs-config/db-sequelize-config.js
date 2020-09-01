const Sequelize = require('sequelize');

function makeSequelizeTenantConnection(db_name, user_name, password, host, port) {
    const sequelize = new Sequelize(db_name, user_name, password, {

        host,
        port,
        dialect: 'mysql',

        pool: {
            max: 5,
            min: 0,
            acquire: 30000,
            idle: 10000
        },
    });

    return sequelize;
}


module.exports = makeSequelizeTenantConnection;