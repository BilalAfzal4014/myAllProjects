module.exports = (sequelize, type) => {
    return sequelize.define('fos_user_user_group', {
        user_id: {
            type: type.INTEGER,
            allowNull: false
        }, group_id: {
            type: type.INTEGER,
            allowNull: false
        }
    }, {
        tableName: "fos_user_user_group",
    })
};