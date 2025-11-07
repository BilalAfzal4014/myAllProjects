const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const UpdateProfileImageUseCase = require("../user/update-profile-image-usecase");
const UpdateBannerImageUseCase = require("../user/update-banner-image-usecase");
const UpdateProfileInfoUseCase = require("../user/update-profile-info-usecase");


module.exports = class UpdateProfileUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.payload = payload;
        this.updateProfileInteractor = null;
    }

    updateProfile() {
        this.assignUseCaseInteractor();
        return Promise.resolve(this.initiateUseCaseInteractor());
    }

    assignUseCaseInteractor() {
        this.updateProfileInteractor = this.getRequiredUpdateProfileUseCaseInteractor();
    }

    getRequiredUpdateProfileUseCaseInteractor() {
        if (this.payload.file){
            if(this.payload.image_type=='logo'){
                return new UpdateProfileImageUseCase({...this.payload});
            }else if(this.payload.image_type=='banner'){
                return new UpdateBannerImageUseCase({...this.payload});
            }
        }
        else
            return new UpdateProfileInfoUseCase({...this.payload});
    }
    initiateUseCaseInteractor() {
        return this.updateProfileInteractor.updateProfile();
    }
}
