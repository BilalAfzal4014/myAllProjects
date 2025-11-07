const BusinessError = require('../../errors/businessError');

module.exports = class BaseUseCase {
    constructor(transaction = null) {
        this.transactionInstance = transaction;
    }

    getTransactionInstance() {
        return Promise.resolve(this.transactionInstance);
    }

    commitTransaction() {
        return Promise.resolve(this.transactionInstance);
    }

    rollbackTransaction() {
        return Promise.reject(this.transactionInstance);
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