require("dotenv").config();
const LoginModuleUseCase = require("../../app/usecases/auth/login/login-module-usecase");
const FosUserUserModel = require("../json-data-for-test-cases/models/fosUserUserModel");
const GeneralHelper = require("../../app/helpers/general-helper");
jest.mock("../../app/repositories/baseRepo");
jest.mock("../../app/repositories/fosUserUserRepo");
jest.mock("../../app/repositories/fosUserUserGroupRepo");
jest.mock("../../app/repositories/refreshTokensRepo");

describe("Login module with credentials", function () {
    test("Login with wrong login type", function () {
        expect.assertions(1);
        return (new LoginModuleUseCase("someDummyType", {}))
            .login()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Login validation failed',
                    extraInfo: [
                        {
                            field: 'type',
                            error: 'type can only have these CREDENTIALS,SOCIAL values'
                        }
                    ],
                    errorLocation: 'BusinessError from validateLoginType function in LoginModuleUseCase'
                });
            });
    });

    test("Login with incorrect email", function () {
        expect.assertions(1);
        return (new LoginModuleUseCase("CREDENTIALS", {email: "", password: ""}))
            .login()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Login with credentials validation failed',
                    extraInfo: [{field: 'email', error: 'email cannot have that value'}],
                    errorLocation: 'BusinessError from validate function in LoginWithCredentialsUseCase'
                });
            });
    });

    test("Login with email which doesn't exist", function () {
        expect.assertions(1);
        return (new LoginModuleUseCase("CREDENTIALS", {email: "johnDoe@gmail.com", password: ""}))
            .login()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Login with credentials validation failed',
                    extraInfo: [
                        {
                            field: 'email',
                            error: "email, johnDoe@gmail.com doesn't exist"
                        }
                    ],
                    errorLocation: 'BusinessError from validate function in LoginWithCredentialsUseCase'
                });
            });
    });

    test("Login with not the assignee of member group", function () {
        const userNotAssigneeOfMemberGroup = FosUserUserModel[1].email;
        expect.assertions(1);
        return (new LoginModuleUseCase("CREDENTIALS", {email: userNotAssigneeOfMemberGroup, password: ""}))
            .login()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'User is not the assignee of required group',
                    extraInfo: [{field: '', error: ''}],
                    errorLocation: 'BusinessError from performLoginAction function in LoginWithCredentialsUseCase'
                });
            });
    });

    test("Login with incorrect password", function () {
        const userAssigneeOfMemberGroup = FosUserUserModel[0].email;
        expect.assertions(1);
        return (new LoginModuleUseCase("CREDENTIALS", {
            email: userAssigneeOfMemberGroup,
            password: "1234567"
        })).login()
            .catch((error) => {
                expect(error).toEqual({
                    type: 'NOT_FOUND',
                    message: 'Login failed due to incorrect password',
                    extraInfo: [{field: 'password', error: 'password is incorrect'}],
                    errorLocation: 'BusinessError from performLoginAction function in LoginWithCredentialsUseCase'
                });
            });
    });

    test("Login with env password", function () {
        const userAssigneeOfMemberGroup = FosUserUserModel[0].email;
        expect.assertions(1);
        return (new LoginModuleUseCase("CREDENTIALS", {
            email: userAssigneeOfMemberGroup,
            password: "12345678"
        })).login()
            .then((result) => {
                expect(GeneralHelper.fetchAllKeysOfObjectInArray(result)).toEqual(["jwtToken", "refreshToken"]);
            });

    });

    test("Login with database password", function () {
        const userAssigneeOfMemberGroup = FosUserUserModel[0].email;
        expect.assertions(1);
        return (new LoginModuleUseCase("CREDENTIALS", {
            email: userAssigneeOfMemberGroup,
            password: "123456"
        })).login()
            .then((result) => {
                expect(GeneralHelper.fetchAllKeysOfObjectInArray(result)).toEqual(["jwtToken", "refreshToken"]);
            });
    });
});