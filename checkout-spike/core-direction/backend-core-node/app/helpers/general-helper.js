const {v4: uuid} = require("uuid");
const _ = require("lodash");
const StorageProviderFactory = require('../providers/storage-providers/storage-provider-factory');
const Base64Helper = require("./base-64-helper")

module.exports = class GeneralHelper {

    static getFileNameFromUrl(url) {
        let fileName = url.split("/");
        fileName = fileName[fileName.length - 1];
        return fileName;
    }

    static removeFile(fileName, directory) {
        return StorageProviderFactory.getStorageProvider(fileName, directory)
            .removeFile(fileName, directory)
            .then(() => {
                console.info(`File ${fileName} removed successfully.`);
                return true;
            }).catch((error) => {
                console.error("Could not remove file due to an error:", error);
                return false;
            });
    }

    static uploadFile(file, directory) {
        return StorageProviderFactory.getStorageProvider(file, directory)
            .saveFile(file, directory)
            .then((fileUrl) => {
                console.info(`File ${fileUrl} uploaded successfully.`);
                return fileUrl;
            }).catch((error) => {
                console.error("Could not upload file due to an error:", error);
                throw new Error(error);
            });
    }

    static uploadBase64EncodedFile(file, directory) {
        return StorageProviderFactory.getStorageProvider(file, directory)
            .saveBase64File(file, directory)
            .then((fileUrl) => {
                console.info(`File ${fileUrl} uploaded successfully.`);
                return fileUrl;
            }).catch((error) => {
                console.error("Could not upload file due to an error:", error);
                throw new Error(error);
            });
    }

    static uploadFiles(file, directory) {
        return StorageProviderFactory.getStorageProvider(file, directory)
            .saveFiles(file, directory)
            .then((filesUrl) => {
                console.info(`File ${filesUrl} uploaded successfully.`);
                return filesUrl;
            }).catch((error) => {
                console.error("Could not upload file due to an error:", error);
                throw new Error(error);
            });
    }

    static isPositiveInteger(value) {
        return (_.isInteger(value) && value > 0);
    }

    static findFromArrayAndSplice(array, value) {
        const index = array.indexOf(value);
        if (index > -1)
            array.splice(index, 1);
        return array;
    }

    static isValidDate(dateString) {
        //let regEx = /^\d{4}-\d{2}-\d{2}$/;
        let regularExpressionString = "^\\d{4}-\\d{2}-\\d{2}$";

        if (!GeneralHelper.matchRegularExpressionWithString(regularExpressionString, dateString))
            return false;

        let d = new Date(dateString);
        let dNum = d.getTime();

        if (!dNum && dNum !== 0)
            return false;

        return d.toISOString().slice(0, 10) === dateString;
    }

    static matchRegularExpressionWithString(regularExpression, string) {
        let regEx = new RegExp(regularExpression);
        return string.match(regEx);
    }

    static isEmptyArray(array) {
        return array.length;
    }

    static getXDaysAheadDateFromCurrentDate(days) {
        let date = new Date();
        date.setDate(date.getDate() + days);
        return date
    }

    static matchAllObjectKeyValuesWithOtherObject(matchTo, matchWith) {
        for (let key in matchTo) {
            if (matchWith[key] === undefined || matchTo[key] != matchWith[key])
                return false;
        }
        return true
    }

    static fetchAllKeysOfObjectInArray(obj) {
        const array = [];
        for (let key in obj) {
            array.push(key);
        }
        return array;
    }

    static getUniqueId() {
        return uuid();
    }

    static makeBase64StringByConcatAllELEOfArrWithGivenDelimiter(array, delimiter = ":") {
        let str = "";
        let firstIteration = true;
        for (let element of array) {
            if (!firstIteration)
                str += delimiter;

            str += element;
            firstIteration = false;
        }
        return Base64Helper.base64Encode(str);
    }

    static addXMinutesToCurrentDateTime(minutes, date = (new Date())) {
        return new Date(date.getTime() + minutes * 60000);
    }

    static checkIfSecondParamDateIsAheadOfFirst(firstDate, secondDate) {
        return secondDate > firstDate;
    }

    static groupByArrayElements(hashKeys, inputList) {
        const similarValueOfHashKeysCollector = GeneralHelper.groupDataBasedOnGivenHashKeys(hashKeys, inputList);
        const outputList = GeneralHelper.convertToList(similarValueOfHashKeysCollector, hashKeys);
        return outputList;
    }


    static groupDataBasedOnGivenHashKeys(hashKeys, inputList) {

        const similarValueOfHashKeysCollector = {};
        GeneralHelper.collectSimilarValueOfHashKeys(hashKeys, similarValueOfHashKeysCollector, inputList);
        return similarValueOfHashKeysCollector;
    }

    static collectSimilarValueOfHashKeys(hashKeys, similarValueOfHashKeysCollector, inputList) {

        for (let element of inputList) {
            const combinedValuesKey = GeneralHelper.collectCombinedValueOfAllKeysWithDelimiter(hashKeys, element, "_");
            if (!GeneralHelper.checkIfKeyExistInObject(similarValueOfHashKeysCollector, combinedValuesKey)) {
                similarValueOfHashKeysCollector[combinedValuesKey] = [];
            }
            similarValueOfHashKeysCollector[combinedValuesKey].push(element);
        }
        return similarValueOfHashKeysCollector;
    }


    static collectCombinedValueOfAllKeysWithDelimiter(hashKeys, object, delimiter = "_") {
        return GeneralHelper.makeKey(hashKeys, object, delimiter);
    }


    static makeKey(hashKeys, object, delimiter) {
        let newKey = '';
        for (let key of hashKeys) {

            if (newKey !== '') {
                newKey += delimiter;
            }

            newKey += object[key];
        }
        return newKey;
    }

    static checkIfKeyExistInObject(object, key) {
        return object[key];
    }

    static convertToList(object, keysCollection) {

        const newList = [];

        for (let key in object) {
            newList.push({
                ...GeneralHelper.breakTheKeyAndMapOnNewObjectKeys(key, keysCollection, "_"),
                list: object[key]
            })

        }

        return newList;

    }

    static breakTheKeyAndMapOnNewObjectKeys(objectkey, keysCollection, delimiter = "_") {
        objectkey = objectkey.split(delimiter);
        const newObject = {};
        let index = 0;
        for (let key of keysCollection) {
            newObject[key] = objectkey[index];
            index++;
        }
        return newObject;
    }

    static fetchDesiredKeysFromObject(objectToCollectFieldsFrom, desired_columns) {
        const objectFieldsCollector = {};
        for (let column of desired_columns) {
            objectFieldsCollector[column] = objectToCollectFieldsFrom[column];
        }
        return objectFieldsCollector;
    }

    static fetchDesiredColumnsFromArrayOfObject(arrayOfObjects, desired_columns) {
        const collectData = [];
        for (let object of arrayOfObjects) {
            collectData.push(
                GeneralHelper.fetchDesiredKeysFromObject(object, desired_columns)
            )
        }
        return collectData;
    }
};
