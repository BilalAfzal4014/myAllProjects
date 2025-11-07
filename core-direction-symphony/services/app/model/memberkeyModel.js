module.exports = (sequelize, type) => {
    return sequelize.define('member_key',
        {
            id:{
                type: type.INTEGER,
                primaryKey: true,
                autoIncrement: true
            },
            member_id: {
                type: type.INTEGER,
                allowNull: true
            }, key_id: {
                type: type.INTEGER,
                allowNull: true
            }, created: {
                type: type.DATE,
                allowNull: false
            }
        },
        {
            tableName: "member_key",
        })
};