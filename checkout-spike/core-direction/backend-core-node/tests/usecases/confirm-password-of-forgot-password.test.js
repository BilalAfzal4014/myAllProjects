require("dotenv").config();
const ConfirmPasswordOfForgotPasswordUseCase = require("../../app/usecases/auth/forgot-password/confirm-password-of-forgot-password-usecase");
const FosUserUserModel = require("../json-data-for-test-cases/models/fosUserUserModel");
jest.mock("../../app/repositories/fosUserUserRepo");
jest.mock("../../app/repositories/baseRepo");

describe('Confirm password of forgot password testcases', function () {

    test("Without payLoad and token which doesn't exist", function () {
        expect.assertions(1);
        return (new ConfirmPasswordOfForgotPasswordUseCase("some-random-token", {}))
            .savePassword()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Confirm password of forgot password validation failed',
                    extraInfo: [
                        {
                            field: 'confirmationLinkToken',
                            error: "ConfirmationLink token doesn't exist"
                        },
                        {field: 'password', error: 'password is required'}
                    ],
                    errorLocation: 'BusinessError from validation function in ConfirmPasswordOfForgotPasswordUseCase'
                });
            });
    });

    test("Without payLoad and token which exist but expired", function () {
        expect.assertions(1);
        const expiredToken = FosUserUserModel[0].confirmation_token;
        return (new ConfirmPasswordOfForgotPasswordUseCase(expiredToken, {}))
            .savePassword()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Confirm password of forgot password validation failed',
                    extraInfo: [
                        {field: 'confirmationLinkToken', error: 'Token Expired'},
                        {field: 'password', error: 'password is required'}
                    ],
                    errorLocation: 'BusinessError from validation function in ConfirmPasswordOfForgotPasswordUseCase'
                });
            });
    });


    test("Without payLoad but valid token", function () {
        expect.assertions(1);
        const validToken = FosUserUserModel[1].confirmation_token;
        return (new ConfirmPasswordOfForgotPasswordUseCase(validToken, {}))
            .savePassword()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Confirm password of forgot password validation failed',
                    extraInfo: [{field: 'password', error: 'password is required'}],
                    errorLocation: 'BusinessError from validation function in ConfirmPasswordOfForgotPasswordUseCase'
                });
            });
    });

    test("Low strength password and expired token", function () {
        expect.assertions(1);
        const expiredToken = FosUserUserModel[0].confirmation_token;
        return (new ConfirmPasswordOfForgotPasswordUseCase(expiredToken, {password: "pass"}))
            .savePassword()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Confirm password of forgot password validation failed',
                    extraInfo: [
                        {field: 'confirmationLinkToken', error: 'Token Expired'},
                        {
                            field: 'password',
                            error: "password doesn't pass regular expression"
                        }
                    ],
                    errorLocation: 'BusinessError from validation function in ConfirmPasswordOfForgotPasswordUseCase'
                });
            });
    });

    test("Valid password and valid token", function () {
        expect.assertions(1);
        const validToken = FosUserUserModel[1].confirmation_token;
        return (new ConfirmPasswordOfForgotPasswordUseCase(validToken, {password: "John@123"}))
            .savePassword()
            .then((result) => {
                expect(result).toBeTruthy();
            });
    });

});
