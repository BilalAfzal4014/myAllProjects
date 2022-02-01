const UserRepo = require("../../databases/repositories/userRepo");

module.exports = class SaveUserUseCase{
    constructor(user) {
        this.user = user;
    }

    save(){
        return this.saveUser();
    }

    saveUser(){
        return UserRepo.save(this.user);
    }
}