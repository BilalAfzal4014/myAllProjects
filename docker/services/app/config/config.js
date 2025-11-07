const mysql = require('mysql');
const Sequelize = require('sequelize');
const UserModel = require('../model/userModel');
const ApikeyModel = require('../model/apikeyModel');
const AppVersionModel = require('../model/appversionModel');
const RefreshTokenModel = require('../model/refreshtokenModel');
const fosgroupModel = require('../model/fosgroupModel');
const fosusergroupModel = require('../model/fosusergroupModel');
const MemberBillingModel = require('../model/memberbillingModel');
const CorprateKeyModel = require('../model/corporate_key');
const MemberKeyModel = require('../model/memberkeyModel');
const ActionModel = require('../model/action');
const sequelize = new Sequelize(process.env.DATABASE, process.env.USER_NAME, process.env.PASSWORD, {
    host: 'localhost',
    dialect: 'mysql',
    pool: {
        max: 10,
        min: 0,
        acquire: 30000,
        idle: 10000
    },
    define: {
        underscored: false,
        freezeTableName: false,
        charset: 'utf8',
        dialectOptions: {
            collate: 'utf8_general_ci'
        },
        timestamps: false
    },
});
sequelize.authenticate().then(() => {
    console.log('Connection has been established successfully.');
}).catch(err => {
    console.error('Unable to connect to the database:', err);
});
const User = UserModel(sequelize, Sequelize);
const Apikey = ApikeyModel(sequelize, Sequelize);
const AppVersion = AppVersionModel(sequelize, Sequelize);
const RefreshToken = RefreshTokenModel(sequelize, Sequelize);
const FosGroup = fosgroupModel(sequelize, Sequelize);
const FosUserGroup = fosusergroupModel(sequelize, Sequelize);
const MemberBilling = MemberBillingModel(sequelize, Sequelize);
const CorprateKey = CorprateKeyModel(sequelize, Sequelize);
const MemberKey = MemberKeyModel(sequelize, Sequelize);
const Action = ActionModel(sequelize, Sequelize);
User.hasMany(MemberKey, {
    foreignKey: 'member_id'
});
User.hasOne(CorprateKey, {
    foreignKey: 'corporate_id'
});
CorprateKey.belongsTo(User,
    {foreignKey: 'corporate_id'}
);
module.exports = {
    //connection,
    sequelize,
    User,
    Apikey,
    AppVersion,
    RefreshToken,
    FosGroup,
    FosUserGroup,
    MemberBilling,
    CorprateKey,
    MemberKey,
    Action
};
/** process is envirment variable **/
/** Difference B/w  mysql.connection and mysql.createPool**/
/** mysql.creatPool is used to limitize the connection**/
// const connection = mysql.createConnection({
// //    connectionLimit: 10,
//     host: process.env.HOST,
//     user: process.env.USER_NAME,
//     password: process.env.PASSWORD,
//     database: process.env.DATABASE
// });
// connection.connect(function (err) {
//     if (err) throw err;
//     console.log("Connected!");
// });
///connection.end();


