const Sequelize = require('sequelize');

function getUserDelModel(sequelize) {

    const UserDel = sequelize.define('user_del', {
        name: {
            type: Sequelize.DataTypes.STRING,
            field: 'name'
        }
    }, {
        underscored: true
    });

    return UserDel;

}


module.exports = getUserDelModel;