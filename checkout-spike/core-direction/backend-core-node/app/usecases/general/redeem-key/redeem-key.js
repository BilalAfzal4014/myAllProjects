const BaseUseCase = require("../../base/base-usecase");
const MemberKeyRepo = require("../../../repositories/memberKeyRepo");
const GeneralHelper = require("../../../helpers/general-helper");
const CorporateKey = require("../../../constants/corporate-key");
const PackageRepo = require("../../../repositories/packageRepo");
const MemberPackageRepo = require("../../../repositories/memberPackageRepo");
const CorporateKeyPackageRepo = require("../../../repositories/corporateKeyPackageRepo");
const RedeemConstants = require('../../../constants/redeem')
module.exports = class RedeemKeyUseCase extends BaseUseCase {

    constructor(corporateKey) {
        super();
        this.user = null;
        this.corporateKey = corporateKey;
    }

    redeemProcess(user, transactionInstance) {
        this.user = user;
        this.transactionInstance = transactionInstance;
        return Promise.all([
            this.entryInMemberKeyTable(),
            this.redeemPackagesAgainstKey()
        ]);
    }


    entryInMemberKeyTable() {
        return MemberKeyRepo.save(
            {
                member_id: this.user,
                key_id: this.corporateKey.id,
                created: GeneralHelper.addXMinutesToCurrentDateTime(1)
            }, this.transactionInstance
        )

    }

    redeemPackagesAgainstKey() {
        if (this.corporateKey.type === CorporateKey.PACKAGE || this.corporateKey.type === CorporateKey.COREPASS) {

            return this.getPackagesAgainstKey()
                .then((packages) => {
                    return this.addPackagesToUserWallet(packages)
                });
        } else {
            return Promise.resolve([]);
        }
    }

    getPackagesAgainstKey() {
        return PackageRepo.findByFacilityId(this.corporateKey.corporate_id);
    }

    addPackagesToUserWallet(packages) {
        let memberPackageArr = [];
        packages.forEach((packageToRedeem) => {
            let memberPackage = this.addPackageToUserWallet(packageToRedeem.id);
            memberPackageArr.push(memberPackage)
        })
        return Promise.all(memberPackageArr)
    }


    addPackageToUserWallet(packageId) {
        return MemberPackageRepo.save({
            package_id: packageId,
            checkin: 0,
            status: 'active',
            is_promotion: false,
            modifiedby: this.user,
            is_deleted:0,
            member_id: this.user
        }, this.transactionInstance)
    }

    async insertInMemberKeyTable(payload) {
        return await MemberKeyRepo.save({
            member_id: payload.member_id,
            key_id: payload.key_id,
            created: GeneralHelper.addXMinutesToCurrentDateTime(1)
        }, this.transactionInstance);

    }

    async insertInMemberPackageTable(packages) {
        for (let i = 0; i < packages.length; i++) {
            await MemberPackageRepo.save({
                package_id: packages[i].package_id,
                member_id: this.user,
                checkin: 0,
                status: 'active',
                modifiedby: this.user,
                is_deleted:0,
                created_date: GeneralHelper.addXMinutesToCurrentDateTime(1),
                updated_date: GeneralHelper.addXMinutesToCurrentDateTime(1),
                is_promotion: 1
            }, this.transactionInstance);
        }
        return true;
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

    async checkIsAlreadyRedeemed(payload) {
        return MemberKeyRepo.isKeyRedeemed(payload).then((record) => {
            return (record.length > 0) ? true : false;
        });
    }

    async redeemKey(user, transactionInstance) {
        this.transactionInstance = transactionInstance;
        this.user = user
        let keyInfo = this.corporateKey;

        if (keyInfo.type == RedeemConstants.PACKAGE_TYPE_DEFAULT) {
            return true;
        } else {
            if (this.isKeyExpired(keyInfo.validate_date)) {
                return true;
            } else {
                if (await this.checkIsAlreadyRedeemed({key_id: keyInfo.id, member_id: user})) {
                    return true;
                } else {
                    if (keyInfo.type == RedeemConstants.PACKAGE_TYPE_PACKAGE || keyInfo.type == RedeemConstants.PACKAGE_TYPE_COREPASS) {
                        return CorporateKeyPackageRepo.getCorporatePacakges(keyInfo.id).then(async (packages) => {
                            if (packages.length > 0) {
                                await this.insertInMemberPackageTable(packages);
                                await this.insertInMemberKeyTable({
                                    member_id: user,
                                    key_id: keyInfo.id
                                });
                                return true;
                            } else {
                                return true;
                            }
                        })

                    }
                    if (keyInfo.type == RedeemConstants.PACKAGE_TYPE_DISCOUNT) {
                        await this.insertInMemberKeyTable({member_id: user, key_id: keyInfo.id});
                        return true;
                    } else {
                        return true;
                    }
                }
            }
        }
    }

}
