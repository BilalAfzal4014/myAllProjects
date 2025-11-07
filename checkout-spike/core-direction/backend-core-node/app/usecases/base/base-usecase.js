const BusinessError = require('../../errors/business-error');
const BaseRepo = require("../../repositories/baseRepo");
const {v4: uuid} = require("uuid");

module.exports = class BaseUseCase {

    constructor(transaction = null) {
        this.transactionInstance = transaction;
    }

    getTransactionInstance() {
        return BaseRepo.startTransaction()
            .then((transaction) => {
                return this.transactionInstance = transaction;
            });
    }

    commitTransaction() {
        return BaseRepo.commitTransaction(this.transactionInstance);
    }

    rollbackTransaction() {
        return BaseRepo.rollbackTransaction(this.transactionInstance);
    }


    handleErrorIfExist(errorsList, errorType, message, location, data = {}) {
        if (this.hasError(errorsList)) {
            this.handleError(
                errorsList,
                errorType,
                message,
                location,
                data
            );
        }
    }

    hasError(errorsList) {
        return errorsList.length > 0;
    }

    handleError(errorsList, errorType, message, location, data = {}) {
        throw new BusinessError(
            errorType,
            message,
            errorsList,
            location,
            data
        );
    }

    generateToken() {
        return uuid();
    }
}