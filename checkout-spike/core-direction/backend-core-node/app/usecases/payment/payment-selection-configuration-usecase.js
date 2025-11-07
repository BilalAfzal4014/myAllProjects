const BaseUseCase = require("../base/base-usecase");
const PaymentWithNewCreditCardUseCase = require("./payment-handling-usecase/payment-with-creditcard/new-creditcard/payment-with-new-creditcard-usecase");
const PaymentWithExistingCreditCardUseCase = require("./payment-handling-usecase/payment-with-creditcard/existing-creditcard/payment-with-existing-creditcard-usecase");
const ErrorTypes = require("../../errors/error-types");

module.exports = class PaymentSelectionConfigurationUseCase extends BaseUseCase {
    constructor(payment) {
        super();
        this.payment = payment;
        this.paymentInteractor = null;
    }

    chargePayment() {
        this.choosePaymentInteractor()
        if (!this.paymentInteractor) {
            return this.handleErrorIncorrectPaymentInformation();
        }
        return this.paymentInteractor.chargePayment();
    }

    choosePaymentInteractor() {
        if (this.payment.token) {
            this.paymentInteractor = new PaymentWithNewCreditCardUseCase(this.payment);
        } else if (this.payment.exisiting_card_id) {
            this.paymentInteractor = new PaymentWithExistingCreditCardUseCase(this.payment);
        }
    }

    handleErrorIncorrectPaymentInformation() {
        return this.handleErrorForValidations([{
            field: "unknown",
            value: "Please provide correct payment information"
        }], "Incorrect paymentInformation provided")
    }

    handleErrorForValidations(errorList, message) {
        this.handleErrorIfExist(
            errorList,
            ErrorTypes.MISSING_ATTRIBUTES,
            message,
            "BusinessError from validate function in PaymentSelectionConfigurationUseCase"
        );
    }
}