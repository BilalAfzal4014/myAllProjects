module.exports = (sequelize, type) => {
    return sequelize.define('app_version',
        {
            is_deleted: {
                type: type.INTEGER,
                allowNull: true
            }, modifiedby: {
                type: type.INTEGER,
                allowNull: false
            }, created_date: {
                type: type.DATE,
                allowNull: false
            }, updated_date: {
                type: type.DATE,
                allowNull: false
            }, device_type: {
                type: type.ENUM('iphone', 'android') ,
                allowNull: false
            }, version: {
                type: type.STRING,
                allowNull: false
            }, status: {
                type: type.INTEGER,
                allowNull: false
            }, current_release: {
                type: type.INTEGER,
                allowNull: false
            }
        },
        {
            tableName: "app_version",
        })
};