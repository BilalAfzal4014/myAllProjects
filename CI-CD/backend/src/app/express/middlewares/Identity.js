const FetchUserUseCase = require('../../usecases/user/fetch');
const BusinessError = require('../../errors/businessError');
const ErrorTypes = require('../../errors/errorType');

module.exports = verifyIdentity = () => {
    return (req, res, next) => {
        if (req.path === '/users/authenticate') return next();

        const identity = req.headers.identity;
        return FetchUserUseCase.fetchById(identity)
            .then((user) => {
                if (!user) {
                    return next(getNotVerifiedError());
                }
                next();
            }).catch(() => {
                return next(getNotVerifiedError());
            });
    }
}


function getNotVerifiedError() {
    return new BusinessError(
        ErrorTypes.NOT_FOUND,
        'Your identity is not recognised',
        [],
        'BusinessError from verifyIdentity middleware'
    );
}