module.exports = (sequelize, type) => {
    return sequelize.define('api_key',
        {
            is_deleted: {
                type: type.INTEGER,
                allowNull: true
            }, modifiedby: {
                type: type.STRING,
                allowNull: false
            }, created_date: {
                type: type.DATE,
                allowNull: false
            }, updated_date: {
                type: type.DATE,
                allowNull: false
            }, api_key: {
                type: type.STRING,
                allowNull: false
            }, api_password: {
                type: type.STRING,
                allowNull: false
                // allowNull defaults to true
            }, is_active: {
                type: type.INTEGER,
                allowNull: false
            }
        },
        {
            tableName: "api_key",
        })
};