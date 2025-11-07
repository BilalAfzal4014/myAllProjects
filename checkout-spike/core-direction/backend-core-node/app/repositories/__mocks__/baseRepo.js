module.exports = class BaseRepo {

    static startTransaction() {
        return Promise.resolve({});
    }

    static commitTransaction(transaction) {
        return Promise.resolve({});
    }

    static rollbackTransaction(transaction) {
        return Promise.resolve({});
    }

    static findByAttributeWhereIdIsNotAndGivenModel(model, attributes, id, extraAttributes, dontFetchDeleted) {
        let searchedModelElements = [];
        for (let element of model) {
            for (let attribute of attributes) {
                if (element[attribute.key] == attribute.value) {
                    if (!id || element.id != id) {
                        searchedModelElements.push(element);
                        break;
                    }
                }
            }
        }

        if (!attributes.length)
            searchedModelElements = [...model];

        if (extraAttributes.length) {
            for (let i = 0; i < searchedModelElements.length; i++) {
                let element = searchedModelElements[i];
                for (let attribute of extraAttributes) {
                    if (element[attribute.key] != attribute.value) {
                        searchedModelElements.splice(i, 1);
                        i--;
                        break;
                    }
                }
            }
        }

        return Promise.resolve(searchedModelElements);
    }

};
