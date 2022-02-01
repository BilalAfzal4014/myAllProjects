const UserRepo = require("../../databases/repositories/userRepo");

module.exports = class FetchUserUseCase {
    static fetchAllUsers() {
        return UserRepo.findAll();
    }

    static fetchUserById(userId) {
        return UserRepo.findById(userId);
    }
}