const UserProfileUseCase = require("./get-user-profile-usecase");
const UserEmergencyUseCase = require("./get-user-emergency-usecase");
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");

module.exports = class UserModuleUseCase {
    constructor(userId) {
        this.userId = userId;
        this.userProfileUseCaseInteractor = new UserProfileUseCase(this.userId);
        this.userEmergencyUseCaseInteractor = new UserEmergencyUseCase(this.userId);
    }

    fetchUserModule() {
        return this.fetchUserModuleIngredients();
    }

    fetchUserModuleIngredients() {
        return Promise.all([
            this.fetchUserProfile(),
            this.fetchUserEmergency()
        ]).then(([userProfile, userEmergency]) => {
            return {
                ...userProfile,
                user_emergency: userEmergency ? {...userEmergency, phone: userEmergency.phoneNumber} : {}
            }
        });
    }

    fetchUserProfile() {
        return this.userProfileUseCaseInteractor.fetchUserProfile();
    }

    fetchUserEmergency() {
        return this.userEmergencyUseCaseInteractor.fetchUserEmergency()
    }

    static fetchUserByEmail(email){
        return FosUserUserRepo.findByEmail(email);
    }

    static fetchUserById(id){
        return FosUserUserRepo.findById(id);
    }
}