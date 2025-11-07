const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const FosUserUserRepo = require("../../repositories/fosUserUserRepo");
const GeneralHelper = require("../../helpers/general-helper");
const StorageDirectories = require('../../constants/storage-directories');

module.exports = class UpdateBannerImageUseCase extends BaseUseCase {
    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
    }

    updateProfile() {
        return this.performPreUpdateActions()
            .then(() => {
                return this.updateCompanyLogoInDB();
            });
    }

    performPreUpdateActions() {
        return Promise.all([
            this.uploadImage(),
            this.removePreviousImage()
        ]);
    }

    uploadImage() {
        return GeneralHelper.uploadFile(this.payLoad.file, StorageDirectories.USER_COMPANY_LOGO)
            .then((fileName) => {
                return this.payLoad.fileName = fileName;
            });
    }

    removePreviousImage() {
        return this.fetchPreviousImageFromDB()
            .then(() => {
                return this.removeImage();
            });
    }

    fetchPreviousImageFromDB() {
        return FosUserUserRepo.findById(this.payLoad.user_id)
            .then((user) => {
                return this.payLoad.previousFileName = user.company_banner;
            });
    }

    removeImage() {
        return GeneralHelper.removeFile(this.payLoad.previousFileName, StorageDirectories.USER_COMPANY_LOGO);
    }

    updateCompanyLogoInDB() {
        return FosUserUserRepo.updateMultipleFieldsById(this.payLoad.user_id, this.prepareData(), this.transactionInstance);
    }

    prepareData() {
        return {company_banner: this.payLoad.fileName};
    }
}
