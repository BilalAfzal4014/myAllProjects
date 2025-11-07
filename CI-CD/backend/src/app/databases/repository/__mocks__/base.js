module.exports = class BaseRepo {
    static findByAttributeWhereIdIsNotAndGivenModel(
        model,
        attributes = [],
        id = null,
        extraAttributes = [],
        dontFetchDeleted = true,
        transaction = null
    ) {

        let result = model.filter((row) => {
            const anyTrue = attributes.map((attribute) => {
                return row[attribute.key] === attribute.value;
            });
            return !anyTrue.length || anyTrue.indexOf(true) > -1;
        }).filter((row) => {
            const allTrue = extraAttributes.map((attribute) => {
                return row[attribute.key] === attribute.value;
            });
            return !allTrue.length || allTrue.indexOf(false) < 0;
        });

        if (dontFetchDeleted) {
            result = result.filter((row) => {
                return row.deletedAt === null
            });
        }

        if (id) {
            result = result.filter((row) => {
                return row.id !== id
            });
        }

        return Promise.resolve(result);
    }

    static startTransaction() {
        return Promise.resolve(null);
    }

    static commitTransaction(transaction) {
        return Promise.resolve(transaction);
    }

    static rollbackTransaction(transaction) {
        return Promise.reject(transaction);
    }
};