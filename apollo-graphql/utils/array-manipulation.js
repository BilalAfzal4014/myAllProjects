const pushInSameWayAsFirstParamArrayArrived = (keys, data, dataKey, collectionKey) => {
    const collection = [];
    const hash = {};

    for (let element of data) {
        if (!hash[element[dataKey]]) {
            hash[element[dataKey]] = [];
        }
        if (Array.isArray(element[collectionKey])) {
            hash[element[dataKey]].push(...element[collectionKey]);
        } else {
            hash[element[dataKey]].push(element[collectionKey]);
        }

    }

    for (let element of keys) {
        collection.push(hash[element]);
    }

    return collection;
}

module.exports = {
    pushInSameWayAsFirstParamArrayArrived
}