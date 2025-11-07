const UserSaveCardTrackingRepo = require("../../repositories/userSaveCardTrackingRepo");
const GeneralHelper = require("../../helpers/general-helper");

module.exports = class FetchUserCardUseCase {
    static fetchCardOfSpecificUserById(id, user_id) {
        return UserSaveCardTrackingRepo.fetchCardOfSpecificUserById(id, user_id);
    }

    static fetchAllCardsOfAUser(user_id, desired_columns =
        ["id", "last_four", "payment_gateway_used", "card_holder_name", "card_type"]) {
        return UserSaveCardTrackingRepo.fetchAllCardsOfAUser(user_id)
            .then((userSaveCards) => {
                return GeneralHelper.fetchDesiredColumnsFromArrayOfObject(userSaveCards, desired_columns);
            });
    }

    static deleteCardOfAUser(card_id, user_id, softDelete = true) {
        return UserSaveCardTrackingRepo.deleteCardOfAUser(card_id, user_id, softDelete);
    }
}