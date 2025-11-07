class ChainedDataHelper {

    static convertListingToHash(listing) {

        let hash = {};

        for (let record of listing) {

            if (hash[record.parent_id] === undefined)
                hash[record.parent_id] = [];

            hash[record.parent_id].push(record);

        }

        return hash;
    }

    static ConvertHashToNestedArray(parent, hash) {

        let children = [];

        if (!hash[parent])
            return children;

        for (let record of hash[parent]) {
            record.children = ChainedDataHelper.ConvertHashToNestedArray(record.id, hash);
            children.push(record);
        }

        return children;
    }

    static convertNestedToFlatArray(errors) {

        let errorList = [];

        for (let error of errors) {
            if (Array.isArray(error)) {
                errorList = [...errorList, ...ChainedDataHelper.convertNestedToFlatArray(error)]
            } else {
                errorList.push(error);
            }
        }

        return errorList;
    }

    static fetchDesiredColumnValueInArrayFromArrayOfObjects(desiredColumn, ArrayOfObjects) {
        const newArr = [];
        for (let object of ArrayOfObjects) {
            newArr.push(object[desiredColumn]);
        }
        return newArr;
    }
}

module.exports = ChainedDataHelper;