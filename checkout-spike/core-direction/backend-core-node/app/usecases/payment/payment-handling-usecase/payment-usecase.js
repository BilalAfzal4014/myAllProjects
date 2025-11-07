const BaseUseCase = require("../../base/base-usecase");
const {getPaymentProvider} = require("../../../providers/payment-providers/payment-provider-factory");
const SaveMemberBillingUseCase = require("../../member-billing/save-member-billing-usecase");

module.exports = class PaymentUseCase extends BaseUseCase {
    constructor(payment) {
        super();
        this.payment = payment;
        this.paymentProviderInteractor = (new (getPaymentProvider())(this.payment));
        this.paymentResponse = null;
    }

    savePaymentResponseData() {
        this.assignVatValueToPaymentResponse();
        return this.saveResponseInMemberBilling()
            .then(() => {
                return this.paymentResponse;
            });
    }

    assignVatValueToPaymentResponse() {
        this.paymentResponse.vat = this.payment.vatValue;
    }

    saveResponseInMemberBilling() {
        return (new SaveMemberBillingUseCase(this.paymentResponse))
            .saveMemberBilling()
            .then((memberBilling) => {
                this.paymentResponse.member_billing_id = memberBilling?.id
            });
    }
}