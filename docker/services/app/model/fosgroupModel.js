module.exports = (sequelize, type) => {
    return sequelize.define('fos_user_group', {
        name: {
            type: type.STRING,
            allowNull: false
        }, roles: {
            type: type.STRING,
            allowNull: false
        }, code: {
            type: type.STRING,
            allowNull: false
        }
    }, {
        tableName: "fos_user_user",
    })
};