const BaseModel = require('../models/base');

module.exports = class BaseRepo {
    static findByAttributeWhereIdIsNotAndGivenModel(
        model,
        attributes = [],
        id = null,
        extraAttributes = [],
        dontFetchDeleted = true,
        transaction = null
    ) {
        const query = model.query(transaction);

        query.where(function (innerQuery) {
            for (let attribute of attributes) {
                innerQuery.orWhere(attribute.key, attribute.value);
            }
        });

        for (let attribute of extraAttributes) {
            query.where(attribute.key, attribute.value);
        }

        if (dontFetchDeleted) {
            query.where('deleted_at', null);
        }

        if (id) {
            query.whereNot("id", id);
        }
        return query;
    }

    static startTransaction() {
        return BaseModel.startTransaction();
    }

    static commitTransaction(transaction) {
        if (transaction) return transaction.commit();
    }

    static rollbackTransaction(transaction) {
        if (transaction) return transaction.rollback();
    }
};