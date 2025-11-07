const PaymentUseCase = require("../payment-usecase");
const SaveUserCardDetailUseCase = require("../../../user-payment-cards/save-user-card-detail-usecase");

module.exports = class PaymentWithCreditCardUseCase extends PaymentUseCase {
    constructor(payment) {
        super(payment);
    }

    saveUserCard() {
        return (new SaveUserCardDetailUseCase(this.prepareDataForSaveCard()))
            .saveUserCardDetail()
            .then((saveCardDetail) => {
                return this.paymentResponse.save_card_detail_id = saveCardDetail.id;
            });
    }

    prepareDataForSaveCard() {
        return {
            save_card_tracking_id: this.paymentResponse.save_card_track_id,
            member_id: this.paymentResponse.user_id,
            last_four: this.paymentResponse.last_four,
            payment_gateway_used: this.paymentResponse.payment_gateway_used,
            modifiedby: this.paymentResponse.user_id,
            card_holder_name: this.paymentResponse.card_holder_name ? this.paymentResponse.card_holder_name : "Anonymous",
            card_type: this.paymentResponse.card_type,
            is_deleted: 0
        }
    }
}