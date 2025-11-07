const _ = require("lodash");
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");
const UserProfileEntity = require("../../../entities/user-module/get-user-profile-entity");
const QRCode = require('qrcode')

module.exports = class UserProfileUseCase {
    constructor(user_id) {
        this.user_id = user_id;
        this.user = null;
        this.qr_code = null;
        this.userProfileEntityInstance = new UserProfileEntity();
    }

    fetchUserProfile() {
        return this.getProfile()
            .then(([user]) => {
                this.user = user;
                return this.generateQRImage()
            }).then((url) => {
                this.user.qr_code = url;
                return this.returnSelectedUserData();
            });
    }

    getProfile(dontFetchDeleted = false) {
        return FosUserUserRepo.findByAttributes([], [{
            key: "id",
            value: this.user_id
        }], dontFetchDeleted);
    }

    generateQRImage() {
        return QRCode.toDataURL(`{user_id:${this.user_id} }`);
    }

    async returnSelectedUserData() {
        return _.pick(this.user, this.userProfileEntityInstance.getUserFieldsForProfile());
    }
}