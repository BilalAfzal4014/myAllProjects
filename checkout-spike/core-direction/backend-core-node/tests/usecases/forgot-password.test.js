require("dotenv").config();
const ForgotPasswordUseCase = require("../../app/usecases/auth/forgot-password/forgot-password-usecase");
const GeneralHelper = require("../../app/helpers/general-helper");
const FosUserUserModel = require("../json-data-for-test-cases/models/fosUserUserModel");
jest.mock("../../app/email-providers/impl/stripe/stripe");
jest.mock("../../app/repositories/fosUserUserRepo");
jest.mock("../../app/repositories/baseRepo");

describe('Forgot password testcases', function () {

    const baseUrl = "http://localhost:4001/v1/forgot-password/confirm-password";

    test("Without payLoad", function () {
        expect.assertions(1);
        return (new ForgotPasswordUseCase({}, baseUrl))
            .recoverPassword()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Forgot password validation failed',
                    extraInfo: [{field: 'email', error: 'email is required'}],
                    errorLocation: 'BusinessError from validation function in ForgotPasswordUseCase'
                });
            });
    });

    test("Email which doesn't exist", function () {
        expect.assertions(1);
        return (new ForgotPasswordUseCase({
            email: "johnDoe@gmail.com"
        }, baseUrl))
            .recoverPassword()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Forgot password validation failed',
                    extraInfo: [
                        {field: 'email', error: "email, johnDoe@gmail.com doesn't exist"}
                    ],
                    errorLocation: 'BusinessError from validation function in ForgotPasswordUseCase'
                });
            });
    });

    test("Email which exist", function () {
        const emailWhichExist = FosUserUserModel[0].email;
        expect.assertions(1);
        return (new ForgotPasswordUseCase({
            email: emailWhichExist
        }, baseUrl))
            .recoverPassword()
            .then((result) => {
                expect(GeneralHelper.fetchAllKeysOfObjectInArray(result)).toEqual(["emailResponse", "confirmationLink"]);
            });
    });

});