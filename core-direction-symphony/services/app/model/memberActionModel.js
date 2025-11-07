module.exports = (sequelize, type) => {
    return sequelize.define('member_action',{
            member_id: {
                type: type.INTEGER,
                allowNull: true
            }, action_id: {
                type: type.INTEGER,
                allowNull: true
            }, is_deleted: {
                type: type.INTEGER,
                allowNull: false
            }, modifiedby: {
                type: type.INTEGER,
                allowNull: false
            }, created_date: {
                type: type.DATE,
                allowNull: false
            }, updated_date: {
                type: type.DATE,
                allowNull: false
            }, isCompleted: {
                type: type.INTEGER,
                allowNull: false
            }, name: {
                type: type.STRING,
                allowNull: false
                // allowNull defaults to true
            }, description: {
                type: type.TEXT,
                allowNull: false
            }, point: {
                type: type.TEXT,
                allowNull: false
            }, image_name: {
                type: type.TEXT,
                allowNull: false
            }
        },{
            tableName: "member_action",
        })
};