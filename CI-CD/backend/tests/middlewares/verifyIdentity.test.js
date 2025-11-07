jest.mock('../../src/app/usecases/baseUsecase');
jest.mock('../../src/app/databases/repository/user');
const verifyIdentityTest = require('../../src/app/express/middlewares/Identity');
const BusinessError = require('../../src/app/errors/businessError');
const ErrorTypes = require('../../src/app/errors/errorType');
const FetchUserUseCase = require('../../src/app/usecases/user/fetch')

const ERROR = new BusinessError(
    ErrorTypes.NOT_FOUND,
    'Your identity is not recognised',
    [],
    'BusinessError from verifyIdentity middleware'
);

let NEXT;

const middleWareNextFunction = {
    next: (param = null) => param
}

describe('Identity Middleware', () => {


    /*
         this will also work and is better bcz we have spyed only once but only resetting the count
         beforeAll(() => {
             NEXT = jest.spyOn(middleWareNextFunction, 'next');
         });

         afterEach(() => {
             jest.clearAllMocks()
         });
     */


    beforeEach(() => {
        NEXT = jest.spyOn(middleWareNextFunction, 'next');
    });

    afterEach(() => {
        jest.restoreAllMocks();
    });

    test('allow if path is "/users/authenticate"', () => {
        const req = {
            path: '/users/authenticate',
            headers: {}
        };
        const middleWare = verifyIdentityTest();
        middleWare(req, {}, NEXT);
        expect(NEXT).toBeCalled();
        expect(NEXT).toHaveBeenCalledWith();
    });

    test('allow if identity is provided and found', () => {
        expect.assertions(2);
        const req = {
            path: '',
            headers: {
                identity: 1
            }
        };

        const middleWare = verifyIdentityTest();
        return middleWare(req, {}, NEXT)
            .then(() => {
                expect(NEXT).toBeCalled();
                expect(NEXT).toHaveBeenCalledWith();
            });
    });

    test('do not allow if identity is provided and but not found', () => {
        expect.assertions(2);
        const req = {
            path: '',
            headers: {
                identity: 11
            }
        };

        const middleWare = verifyIdentityTest();
        return middleWare(req, {}, NEXT)
            .then(() => {
                expect(NEXT).toBeCalled();
                expect(NEXT).toHaveBeenCalledWith(ERROR);
            });

    });

    test('do not allow if identity is not provided', () => {
        expect.assertions(2);
        const req = {
            path: '',
            headers: {}
        };

        const middleWare = verifyIdentityTest();
        return middleWare(req, {}, NEXT)
            .then(() => {
                expect(NEXT).toBeCalled();
                expect(NEXT).toHaveBeenCalledWith(ERROR);
            });
    });

    test('do not allow if identity promise rejects', () => {
        expect.assertions(2);

        const spy = jest.spyOn(FetchUserUseCase, 'fetchById').mockRejectedValue(null);

        const req = {
            path: '',
            headers: {}
        };

        const middleWare = verifyIdentityTest();
        return middleWare(req, {}, NEXT)
            .then(() => {
                expect(NEXT).toBeCalled();
                expect(NEXT).toHaveBeenCalledWith(ERROR);
            });
    });

});