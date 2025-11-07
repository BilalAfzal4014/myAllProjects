const CreateMemberScheduleActivityEntity = require("./create-member-schedule-activity-entity");
const UpdateMemberScheduleActivityEntity = require("./update-member-schedule-activity-entity");
const validationRules = require("./validation-rules/member-schedule-activity-validation-rules.json");

module.exports = class MemberScheduleActivityEntity {
    constructor(memberScheduleActivity) {
        this.memberScheduleEntityDesiredInstance = MemberScheduleActivityEntity.getDesiredInstance(memberScheduleActivity);
    }

    static getDesiredInstance(memberScheduleActivity) {
        if (memberScheduleActivity.id === undefined) {
            return new CreateMemberScheduleActivityEntity();
        }
        return new UpdateMemberScheduleActivityEntity();
    }

    getValidationRules = () => ([...validationRules, ...this.memberScheduleEntityDesiredInstance.getValidationRules()])

    getUserProvidedFields = () => (["member_id", "schedule_detail_id", "is_deleted", "modifiedby", "checkin", "package_id", "is_favourite", "STATUS", "member_package_id", "reminder", "qr_code", ...this.memberScheduleEntityDesiredInstance.getUserProvidedFields()]);

    getFieldsForUniqueness = () => ([...this.memberScheduleEntityDesiredInstance.getFieldsForUniqueness()]);
}