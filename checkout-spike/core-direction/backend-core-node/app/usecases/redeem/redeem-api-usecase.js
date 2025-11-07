const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const CorporateKeyRepo = require("../../repositories/corporateKeyRepo");
const MemberKeyRepo = require("../../repositories/memberKeyRepo");
const MemberPackageRepo = require("../../repositories/memberPackageRepo");
const CorporateKeyPackageRepo = require("../../repositories/corporateKeyPackageRepo");
const GeneralHelper = require("../../helpers/general-helper");
const ErrorTypes = require("../../errors/error-types");
const RedeemConstants = require('../../constants/redeem')

module.exports = class RedeemApiUseCase extends BaseUseCase {
    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
    }

    redeem() {
        return this.redeemKey();
    }

    redeemKey() {
        return CorporateKeyRepo.getKeyInfo(this.payLoad.key).then(async (keyInfo) => {
            if (!keyInfo || keyInfo.type == RedeemConstants.PACKAGE_TYPE_DEFAULT) {
                return this.sendErrorIfKeyInvalid()
            } else {
                if (this.isKeyExpired(keyInfo.validate_date)) {
                    return this.sendErrorIfKeyExpired()
                } else {
                    if (await this.checkIsAlreadyRedeemed({key_id:keyInfo.id,member_id:this.payLoad.user_id})) {
                        return this.sendErrorIfKeyRedeemed();
                    } else {
                        if (keyInfo.type == RedeemConstants.PACKAGE_TYPE_PACKAGE || keyInfo.type == RedeemConstants.PACKAGE_TYPE_COREPASS) {
                            return CorporateKeyPackageRepo.getCorporatePacakges(keyInfo.id).then(async (packages) => {
                                if(packages.length>0){
                                    await this.insertInMemberPackageTable(packages);
                                    await this.insertInMemberKeyTable({member_id:this.payLoad.user_id,key_id:keyInfo.id});
                                    return {};
                                }else {
                                    return this.sendErrorIfNoPackageFound();
                                }
                            })

                        }
                        if (keyInfo.type == RedeemConstants.PACKAGE_TYPE_DISCOUNT) {
                            await this.insertInMemberKeyTable({member_id:this.payLoad.user_id,key_id:keyInfo.id});
                            return {};
                        }else {
                            return this.sendErrorIfNoPackageFound();
                        }

                    }

                }

            }

        });
    }
    async insertInMemberPackageTable(packages) {
        for (let i = 0; i < packages.length; i++) {
            await MemberPackageRepo.addInMemberPackage({member_id:this.payLoad.user_id,package_id:packages[i].package_id});
        }
        return true;
    }
    async insertInMemberKeyTable(payload) {
        return await MemberKeyRepo.addRecord(payload);
    }
    async checkIsAlreadyRedeemed(payload) {
        return MemberKeyRepo.isKeyRedeemed(payload).then((record) => {
            return (record.length > 0) ? true : false;
        });
    }

    sendErrorIfKeyInvalid() {
        return this.handleErrorIfExist([{
                "field": "",
                "error": ""
            }],
            ErrorTypes.NOT_FOUND,
            RedeemConstants.INVALID_KEY,
            "BusinessError from validate function in RedeemApiUseCase"
        );
    }

    sendErrorIfKeyExpired() {
        return this.handleErrorIfExist([{
                "field": "",
                "error": ""
            }],
            ErrorTypes.NOT_FOUND,
            RedeemConstants.KEY_EXPIRED,
            "BusinessError from validate function in RedeemApiUseCase"
        );
    }
    sendErrorIfNoPackageFound() {
        return this.handleErrorIfExist([{
                "field": "",
                "error": ""
            }],
            ErrorTypes.NOT_FOUND,
            RedeemConstants.NOT_FOUND,
            "BusinessError from validate function in RedeemApiUseCase"
        );
    }
    sendErrorIfKeyRedeemed() {
        return this.handleErrorIfExist([{
                "field": "",
                "error": ""
            }],
            ErrorTypes.NOT_FOUND,
            RedeemConstants.ALREADY_REDEEMED,
            "BusinessError from validate function in RedeemApiUseCase"
        );
    }

    isKeyExpired(date) {
        var now = new Date();
        var keyValidDate = new Date(date);
        if (keyValidDate < now) {
            return true;
        } else {
            return false;
        }
    }

}
