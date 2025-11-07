const BusinessError = require("../../../errors/business-error");
const ErrorTypes = require("../../../errors/error-types");
const HttpResponseHandler = require("../../../errors/handlers/http-error-response-handler");
const GeneralHelper = require("../../../helpers/general-helper");

module.exports = class PaginationValidator {
    constructor(reqBody, orderByColumns, orderByType) {
        this.reqBody = reqBody;
        this.orderByColumns = orderByColumns;
        this.orderByType = orderByType;
        this.fieldsError = [];
    }

    static validatePagination(orderByColumns, orderByTypes = ['asc', 'desc']) {
        return (req, res, next) => {
            new PaginationValidator(req.body, orderByColumns, orderByTypes)
                .validatePaginationRequestBody(res, next);
        };
    }

    validatePaginationRequestBody(res, next) {
        if (this.hasErrors()) {
            this.handleErrors(res);
        } else {
            next();
        }
    }

    handleErrors(res) {
        const error = new BusinessError(
            ErrorTypes.MISSING_DATA,
            "There are few errors in the payLoad", this.fieldsError,
            "BusinessError from validatePaginationRequestBody in paginationValidator"
        );
        return new HttpResponseHandler(res).handleError(error);
    }

    hasErrors() {
        if (this.validateListingObject()) {
            this.validateChunkSize();
            this.validatePageNo();
            this.validateQueryString();
            this.validateOrderByCol();
            this.validateOrderByType();
        }

        return this.fieldsError.length > 0;
    }


    validateListingObject() {
        if (this.reqBody.listing === undefined) {
            this.addFieldError("listing", "No listing property defined");
            return false;
        }

        return true;
    }

    validateChunkSize() {
        if (this.reqBody.listing.chunkSize === undefined) {
            this.addFieldError("chunkSize", "No chunkSize property defined");
        } else if (!GeneralHelper.isPositiveInteger(this.reqBody.listing.chunkSize)) {
            this.addFieldError("chunkSize", "chunkSize should only be a positive integer");
        }
    }

    validatePageNo() {
        if (this.reqBody.listing.pageNo === undefined) {
            this.addFieldError("pageNo", "No pageNo property defined");
        } else if (!GeneralHelper.isPositiveInteger(this.reqBody.listing.pageNo)) {
            this.addFieldError("pageNo", "pageNo should only be positive integer");
        }
    }

    validateQueryString() {
        if (this.reqBody.listing.queryStr === undefined) {
            this.addFieldError("queryStr", "No queryStr property defined");
        }
    }

    validateOrderByCol() {
        if (this.reqBody.listing.orderByCol === undefined) {
            this.addFieldError("orderByCol", "no orderByCol property defined");
        } else if (this.orderByColumns.indexOf(this.reqBody.listing.orderByCol.toLocaleLowerCase()) < 0) {
            this.addFieldError("orderByCol", "orderByCol property cannot have that value");
        }
    }

    validateOrderByType() {
        if (this.reqBody.listing.orderByType === undefined) {
            this.addFieldError("orderByType", "No orderByType property defined");
        } else if (this.orderByType.indexOf(this.reqBody.listing.orderByType.toLocaleLowerCase()) < 0) {
            this.addFieldError("orderByType", "orderByType property cannot have that value");
        }
    }

    addFieldError(field, error) {
        this.fieldsError.push({field, error});
    }
};