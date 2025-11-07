const BaseUseCase = require("../base/base-usecase");
const PaymentSelectionConfigurationUseCase = require("../payment/payment-selection-configuration-usecase");
const UserProfileUseCase = require("../user/fetch/get-user-profile-usecase");
const FetchConfigurationUseCase = require("../configuration/fetch-configuration-usecase");
// To be implemented -> currency conversion provider which we will use to fetch the currency unit
module.exports = class PurchaseUseCase extends BaseUseCase {
    constructor() {
        super();
        this.vatValue = null;
        this.userDetails = null;
        this.totalPayment = null;
        this.currency = "aed"; // will fetch from currency conversion provider once implemented
        this.PaymentSelectionConfigurationUseCaseInteractor = null;
        this.paymentResponse = null;
    }

    get TotalPayment() {
        return this.totalPayment;
    }

    set PaymentResponse(response){
        this.paymentResponse = response;
    }

    fetchUserDetails(userId = null) {
        return (new UserProfileUseCase(userId ? userId : this.package.user_id))
            .getProfile(true)
            .then(([userDetails]) => {
                this.userDetails = userDetails;
            });
    }

    initializePaymentSelectionConfiguration(data) {
        this.PaymentSelectionConfigurationUseCaseInteractor = new PaymentSelectionConfigurationUseCase({
            ...data, //user_id, package_id, product = 'package', product_id = package_id, package_details
            user_info: this.userDetails,
            total_payment: this.totalPayment,
            currency: this.currency,
            vatValue: this.vatValue
        });
    }

    chargePayment() {
        return this.PaymentSelectionConfigurationUseCaseInteractor.chargePayment()
            .then((paymentResponse) => {
                return this.paymentResponse = paymentResponse;
            });
    }

    fetchPaymentErrorIfAny() {
        return this.totalPayment <= 0 ? [{
            field: "unknown",
            error: "Payment price/amount should be greater than 0"
        }] : []
    }

    handlePaymentFailedScenario(product, location, errorType, error) {
        this.handleError([{
            field: "unknown",
            error
        }], errorType, `${product} purchased failed`, location);
    }

    fetchVatValueFromConfigurations() {
        return FetchConfigurationUseCase.fetchConfigurationsByKey("checkout_vat")
            .then((configurations) => {
                this.vatValue = configurations?.value_data;
            });
    }
}