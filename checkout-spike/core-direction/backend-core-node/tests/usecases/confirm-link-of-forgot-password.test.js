require("dotenv").config();
const ConfirmLinkOfForgotPasswordUseCase = require("../../app/usecases/auth/forgot-password/confirm-link-of-forgot-password-usecase");
const FosUserUserModel = require("../json-data-for-test-cases/models/fosUserUserModel");
jest.mock("../../app/repositories/fosUserUserRepo");
jest.mock("../../app/repositories/baseRepo");

describe('Confirm link of forgot password testcases', function () {

    test("Token which doesn't exist", function () {
        expect.assertions(1);
        return (new ConfirmLinkOfForgotPasswordUseCase("someRandomString"))
            .verifyConfirmationToken()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Confirmation link validation failed',
                    extraInfo: [
                        {
                            field: 'confirmationLinkToken',
                            error: "ConfirmationLink token doesn't exist"
                        }
                    ],
                    errorLocation: 'BusinessError from validation function in ConfirmForgotPasswordLinkUseCase'
                });
            });
    });

    test("Token which exist but expired", function () {
        const expiredToken = FosUserUserModel[0].confirmation_token;
        expect.assertions(1);
        return (new ConfirmLinkOfForgotPasswordUseCase(expiredToken))
            .verifyConfirmationToken()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Confirmation link validation failed',
                    extraInfo: [{field: 'confirmationLinkToken', error: 'Token Expired'}],
                    errorLocation: 'BusinessError from validation function in ConfirmForgotPasswordLinkUseCase'
                });
            });
    });

    test("Token which exist and valid", function () {
        const validToken = FosUserUserModel[1].confirmation_token;
        const emailOfValidToken = FosUserUserModel[1].email;
        expect.assertions(1);
        return (new ConfirmLinkOfForgotPasswordUseCase(validToken))
            .verifyConfirmationToken()
            .then((email) => {
                expect(email).toBe(emailOfValidToken);
            });
    });


});
