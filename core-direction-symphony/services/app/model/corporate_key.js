module.exports = (sequelize, type) => {
    return sequelize.define('corporate_key',
        {
            id:{
                type: type.INTEGER,
                primaryKey: true,
                autoIncrement: true
            },
            corporate_id: {
                type: type.INTEGER,
                allowNull: true
            }, is_deleted: {
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
            }, company_key: {
                type: type.STRING,
                allowNull: false
            }, validate_date: {
                type: type.DATE,
                allowNull: false
            }, start_date: {
                type: type.DATE,
                allowNull: false
            }, type: {
                type: type.ENUM('Discount', 'Package', 'CorePass', 'Profile', 'Default', 'Referral'),
                allowNull: false
            }, is_active: {
                type: type.INTEGER,
                allowNull: true
            }
        },
        {
            tableName: "corporate_key",
        })
};