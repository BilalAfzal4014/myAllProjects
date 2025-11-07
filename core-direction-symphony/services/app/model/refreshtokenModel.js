module.exports = (sequelize, type) => {
    return sequelize.define('refresh_tokens',
        {
            refresh_token: {
                type: type.TEXT,
                allowNull: true
            }, username: {
                type: type.TEXT,
                allowNull: true
            }, valid: {
                type: type.DATE(6),
                allowNull: false
            }
        },
        {
            tableName: "refresh_tokens",
        })
};