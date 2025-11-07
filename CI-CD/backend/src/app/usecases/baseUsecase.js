const BaseRepo = require('../databases/repository/base');
const BusinessError = require('../errors/businessError');

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

    handleErrorIfExist(errorsList, errorType, message = '', location, data = {}) {
        if (this.hasError(errorsList, message)) {
            this.handleError(
                errorsList,
                errorType,
                message,
                location,
                data
            );
        }
    }

    hasError(errorsList, message) {
        return errorsList.length > 0 || !!message;
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
}