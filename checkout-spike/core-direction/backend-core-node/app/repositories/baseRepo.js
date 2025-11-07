const BaseModel = require("../models/baseModel");

module.exports = class BaseRepo {

    static startTransaction() {
        return BaseModel.startTransaction();
    }

    static commitTransaction(transaction) {
        if (transaction) return transaction.commit();
    }

    static rollbackTransaction(transaction) {
        if (transaction) return transaction.rollback();
    }

    static findByAttributesWithOrderByAndGivenModel(
        model,
        attributes,
        extraAttributes,
        orderBy,
        dontFetchDeleted
    ) {
        const query = model.query();

        query.where(function (innerQuery) {
            for (let attribute of attributes) {
                innerQuery.orWhere(attribute.key, attribute.value);
            }
        });

        for (let attribute of extraAttributes) {
            query.where(attribute.key, attribute.value);
        }

        if (dontFetchDeleted) {
            query.where("is_deleted", 0);
        }
        query.orderBy(orderBy.key, orderBy.order_by);
        return query;
    }


    static findByAttributeWhereIdIsNotAndGivenModel(
        model,
        attributes,
        id,
        extraAttributes,
        dontFetchDeleted,
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
            query.where("is_deleted", 0);
        }

        if (id) {
            query.whereNot("id", id);
        }
        return query;
    }

};
