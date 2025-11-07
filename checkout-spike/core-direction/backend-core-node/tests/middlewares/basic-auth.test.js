const basicAuth = require("../../app/express/middlewares/basic-auth/basic-auth");
const Base64Helper = require("../../app/helpers/base-64-helper");
const ApiKeyModel = require("../json-data-for-test-cases/models/apiKeyModel");
jest.mock("../../app/repositories/apiKeyRepo");

const successString = `${ApiKeyModel[0].api_key}:${ApiKeyModel[0].api_password}`;
const failureString = `${ApiKeyModel[0].api_key}-failureString:${ApiKeyModel[0].api_password}`;

describe("Basic auth test cases", function () {
    test("Basic auth required", function () {

        let nextData;
        const request = {
            headers: {
                authorization: undefined
            }
        };
        const response = {};
        const next = jest.fn(function (data) {
            nextData = data;
        });

        const middleware = basicAuth();
        const middlewareResult = middleware(request, response, next);

        expect.assertions(1);
        expect(nextData).toEqual({
            errorLocation: "BusinessError from basic auth middleware",
            extraInfo: [],
            message: "Basic Authentication required",
            type: "FORBIDDEN"
        });

    });

    test("Basic auth failure", function () {
        let nextData;
        const request = {
            headers: {
                authorization: "Basic " + Base64Helper.base64Encode(failureString)
            }
        };
        const response = {};
        const next = jest.fn(function (data) {
            nextData = data;
        });

        const middleware = basicAuth();
        const middlewareResult = middleware(request, response, next);

        expect.assertions(1);
        return middlewareResult
            .then(() => {
                expect(nextData).toEqual({
                    errorLocation: "BusinessError from basic auth middleware",
                    extraInfo: [],
                    message: "Basic Authentication failed",
                    type: "NOT_FOUND"
                });
            });

    });

    test("Basic auth success", function () {
        let nextData;
        const request = {
            headers: {
                authorization: "Basic " + Base64Helper.base64Encode(successString)
            }
        };
        const response = {};
        const next = jest.fn(function (data) {
            nextData = data;
        });

        const middleware = basicAuth();
        const middlewareResult = middleware(request, response, next);

        expect.assertions(1);
        return middlewareResult
            .then(() => {
                expect(nextData).toBeNull();
            });
    });
});