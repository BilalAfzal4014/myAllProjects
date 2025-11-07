const MemberKeyRepo = require("../../repositories/memberKeyRepo");
module.exports = class PriceModification {
    static async isDiscountExistForUser(packages, user_id) {
        let records = await MemberKeyRepo.isDiscountExistForUser(user_id).then((exist) => {
            if (exist) {
                return packages.map((item) => {
                    return {...item, 'discounted_price': item.corporate_rate}
                });
            } else {
                return packages.map((item) => {
                    return {...item, 'discounted_price': null}
                });
            }
        });
        return await PriceModification.returnWithConsumed(records,user_id)
    }

    static async returnWithConsumed(records,member_id) {
        let new_records = [];
        for (const item of records) {
            if (item.member_package_id) {
                let count = await MemberKeyRepo.countConsumed(item.member_package_id,member_id);
                new_records.push({...item, consumed: count})
            } else {
                new_records.push({...item, consumed: 0, member_package_id: null})
            }

        }
        return new_records;
    }
}
