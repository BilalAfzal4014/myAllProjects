const HttpErrorResponseHandler = require("../../app/errors/handlers/http-error-response-handler");
const ErrorTypes = require('../../app/errors/error-types');
const BusinessError = require('../../app/errors/business-error');


describe("Http response handler", function () {

    test("Http Business error handler", function () {
        let businessErrorInstance = new BusinessError(
            ErrorTypes.NOT_FOUND,
            "Dummy message", [{
                key: "Dummy Key",
                value: "Dummy Value"
            }],
            "Dummy tracer"
        );


        let responseData = {};

        const resp = {
            status: function (status) {
                return {
                    json: function (data) {
                        responseData = data;
                    }
                }
            }
        };

        const httpErrorResponseHandlerInstance = new HttpErrorResponseHandler(resp);
        httpErrorResponseHandlerInstance.handleError(businessErrorInstance);

        expect(responseData).toEqual({
            status: 404,
            data: {},
            message: 'Dummy message',
            errors: [{key: 'Dummy Key', value: 'Dummy Value'}]
        });


        businessErrorInstance = new BusinessError(
            ErrorTypes.MISSING_DATA,
            "Dummy message", [{
                key: "Dummy Key",
                value: "Dummy Value"
            }],
            "Dummy tracer"
        );

        httpErrorResponseHandlerInstance.handleError(businessErrorInstance);
        expect(responseData).toEqual({
            status: 400,
            data: {},
            message: 'Dummy message',
            errors: [{key: 'Dummy Key', value: 'Dummy Value'}]
        });


    });

    test("Http General/Application error handler", function () {

        const errorInstance = new Error("Cannot find .then of undefined");

        let responseData = {};

        const resp = {
            status: function (status) {
                return {
                    json: function (data) {
                        responseData = data;
                    }
                }
            }
        };

        const httpErrorResponseHandlerInstance = new HttpErrorResponseHandler(resp);
        httpErrorResponseHandlerInstance.handleError(errorInstance);

        expect(responseData).toEqual({
            status: 500,
            data: {},
            message: 'Server error occurred',
            errors: []
        });

    });


});