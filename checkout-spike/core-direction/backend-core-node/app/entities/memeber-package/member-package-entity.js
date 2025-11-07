const CreateMemberPackageEntity = require("./create-member-package-entity");
const UpdateMemberPackageEntity = require("./update-member-package-entity");
const validationRules = require("./validation-rules/member-package-validation-rules.json");

module.exports = class MemberPackageEntity {
    constructor(memberPackage) {
        this.memberPackageEntityDesiredInstance = MemberPackageEntity.getDesiredInstance(memberPackage);
    }

    static getDesiredInstance(memberPackage) {
        if (memberPackage.id === undefined) {
            return new CreateMemberPackageEntity();
        }
        return new UpdateMemberPackageEntity();
    }

    getValidationRules = () => ([...validationRules, ...this.memberPackageEntityDesiredInstance.getValidationRules()])

    getUserProvidedFields = () => (["member_id", "package_id", "is_deleted", "modifiedby", "checkin", "card_id", "is_promotion", "status", ...this.memberPackageEntityDesiredInstance.getUserProvidedFields()]);

    getFieldsForUniqueness = () => ([...this.memberPackageEntityDesiredInstance.getFieldsForUniqueness()]);
}