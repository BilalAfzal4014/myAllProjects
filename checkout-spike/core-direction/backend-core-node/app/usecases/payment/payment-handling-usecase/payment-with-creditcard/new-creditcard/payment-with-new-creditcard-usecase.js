const PaymentWithCreditCardUseCase = require("../payment-with-creditcard-usecase");

module.exports = class PaymentWithNewCreditCardUseCase extends PaymentWithCreditCardUseCase {
    constructor(payment) {
        super(payment);
    }

    chargePayment() {
        return this.chargePaymentWithTokenizeCreditCard()
            .then(() => {
                return this.performAfterChargePaymentActions();
            }).then(() => {
                return this.paymentResponse;
            });
    }

    chargePaymentWithTokenizeCreditCard() {
        return this.paymentProviderInteractor.chargePaymentWithTokenizeCreditCard()
            .then((paymentResponse) => {
                return this.paymentResponse = paymentResponse;
            });
    }

    performAfterChargePaymentActions() {
        return Promise.all([
            this.savePaymentResponseData(),
            this.saveCardIfRequired()
        ]);
    }

    saveCardIfRequired() {
        if (this.payment.save_card && this.paymentResponse.status === "success") {
            return this.saveUserCard();
        }
        return Promise.resolve("Either user choose to not to save his card or payment results in failure");
    }
}