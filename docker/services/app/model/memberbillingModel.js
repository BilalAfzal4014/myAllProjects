module.exports = (sequelize, type) => {
    return sequelize.define('member_billing',
        {
            user_id: {
                type: type.INTEGER,
                allowNull: false
            }, charge_id: {
                type: type.STRING,
                allowNull: false
            }, track_id: {
                type: type.STRING,
                allowNull: false
            }, card_id: {
                type: type.STRING,
                allowNull: false
            }, last_four: {
                type: type.STRING,
                allowNull: false
            }, amount: {
                type: type.DECIMAL,
                allowNull: false
            }, currency: {
                type: type.STRING,
                allowNull: false
            }, created_date: {
                type: type.DATE,
                allowNull: false
            }, updated_date: {
                type: type.DATE,
                allowNull: false
            }, transaction_response: {
                type: type.TEXT,
                allowNull: false
            }, transaction_type: {
                type: type.ENUM('Payment', 'Refund', 'Cancellation'),
                allowNull: false
            }, vat: {
                type: type.DECIMAL,
                allowNull: false
            }
        },
        {
            tableName: "member_billing",
        })
};