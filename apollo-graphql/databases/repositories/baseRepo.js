module.exports = class BaseRepo {

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
