const PaymentWithCreditCardUseCase = require("../payment-with-creditcard-usecase");
const FetchUserCardUseCase = require("../../../../user-payment-cards/fetch-user-card-usecase");
const ErrorTypes = require("../../../../../errors/error-types");

module.exports = class PaymentWithExistingCreditCardUseCase extends PaymentWithCreditCardUseCase {
    constructor(payment) {
        super(payment);
    }

    chargePayment() {
        return this.validate()
            .then(() => {
                return this.chargePaymentWithCardTrackingId();
            }).then(() => {
                return this.performAfterChargePaymentActions();
            });
    }

    validate() {
        return this.validateWithoutThrowingErrors()
            .then((errorList) => {
                return this.handleErrorForValidations(errorList);
            });
    }

    validateWithoutThrowingErrors() {
        return this.validateCustom();
    }

    validateCustom() {
        return this.checkIfCreditCardExist();
    }

    checkIfCreditCardExist() {
        return this.fetchExistingCreditCard()
            .then((userSavedCard) => {
                return userSavedCard ? [] : [{
                    field: "unknown",
                    message: "Credit card not found"
                }];
            });
    }

    fetchExistingCreditCard() {
        return FetchUserCardUseCase.fetchCardOfSpecificUserById(this.payment.exisiting_card_id, this.payment.user_info.id)
            .then((userSavedCard) => {
                return this.payment.user_card_details = userSavedCard;
            });
    }

    handleErrorForValidations(errorList) {
        this.handleErrorIfExist(
            errorList,
            ErrorTypes.MISSING_ATTRIBUTES,
            "Payment with existing credit card Module Validation Failed",
            "BusinessError from validate function in PaymentWithExistingCreditCardUseCase"
        );
    }

    chargePaymentWithCardTrackingId() {
        return this.paymentProviderInteractor.chargePaymentWithCardTrackingId()
            .then((paymentResponse) => {
                return this.paymentResponse = paymentResponse;
            });
    }

    performAfterChargePaymentActions() {
        return this.savePaymentResponseData();
    }
}