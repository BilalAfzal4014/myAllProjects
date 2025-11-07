const UserEmergencyRepo = require("../../../repositories/useremergencyRepo");

module.exports = class UserEmergencyUseCase {
    constructor(userId) {
        this.userId = userId;
    }

    fetchUserEmergency() {
        return UserEmergencyRepo.findByUserId(this.userId)
            .then(([userEmergency]) => (userEmergency))
    }
}