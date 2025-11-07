const BaseUseCase = require('../../baseUsecase');
const FetchUserUseCase = require('../fetch');
const ErrorTypes = require('../../../errors/errorType');

module.exports = class AuthenticateUserUseCase extends BaseUseCase {
    constructor(userInfo) {
        super();
        this.userInfo = userInfo;
        this.userDetails = null;
    }

    authenticate() {
        return this.verifyIdFromDb()
            .then(() => {
                return this.isAValidUser();
            }).then(() => {
                return this.userDetails;
            })
    }

    verifyIdFromDb() {
        return FetchUserUseCase.fetchById(this.userInfo.identity).then((user) => this.userDetails = user);
    }

    isAValidUser() {
        if (!this.userDetails) {
            this.handleErrorIfExist(
                [],
                ErrorTypes.NOT_FOUND,
                `User doesn't exist`,
                'BusinessError from isAValidUser function in AuthenticateUserUseCase'
            );
        }
    }
}