const PurchaseUseCase = require("../purchase/purchase-usecase");
const PurchasePackageUseCase = require("./purchase-package-usecase");
const PurchaseMultiplePackagesEntity = require("../../entities/purchase-package/purchase-multiple-packages/purchase-multiple-packages-entity");
const Validator = require("../../entity-validations/validator");
const ChainedDataHelper = require("../../helpers/chained-data-helper");
const ErrorTypes = require("../../errors/error-types");

module.exports = class PurchaseMultiplePackageUseCase extends PurchaseUseCase {
    constructor(payLoad) {
        super();
        this.purchaseSinglePackageInteractors = [];
        this.payLoad = payLoad;
        this.purchaseMultiplePackagesEntityInstance = new PurchaseMultiplePackagesEntity();
    }

    purchaseMultiplePackages() {
        return this.validate()
            .then(() => {
                return this.calculateTotalPaymentForAllPackages();
            }).then(() => {
                return this.fetchVatValueFromConfigurations();
            }).then(() => {
                return this.fetchUserDetails(this.payLoad.user_id);
            }).then(() => {
                console.trace({
                    ...this.payLoad.packages, ...(this.payLoad.token && {token: this.payLoad.token}), ...(this.payLoad.exisiting_card_id && {exisiting_card_id: this.payLoad.exisiting_card_id}),
                    product: "package",
                    product_id: this.payLoad.packages.map((thisPackage) => thisPackage.package_id)
                });
                return this.initializePaymentSelectionConfiguration({
                    ...this.payLoad.packages,
                    ...(this.payLoad.token && {token: this.payLoad.token}),
                    ...(this.payLoad.save_card && {save_card: this.payLoad.save_card}),
                    ...(this.payLoad.exisiting_card_id && {exisiting_card_id: this.payLoad.exisiting_card_id}),
                    product: "package",
                    product_id: this.payLoad.packages.map((thisPackage) => thisPackage.package_id)
                });
            }).then(() => {
                return this.performPurchaseAction();
            }).then(() => {
                return this.performAfterPurchaseActions();
            });
    }

    validate() {
        return this.validateWithoutThrowingErrors()
            .then((errorList) => {
                this.handleErrorIfExist(errorList, ErrorTypes.MISSING_ATTRIBUTES, "Purchase multiple packages Validation Failed", "BusinessError from validate function in PurchaseMultiplePackageUseCase");
            });
    }

    validateWithoutThrowingErrors() {
        return this.validateUserProvidedFields()
            .then((errorList) => {
                return errorList.length ? errorList : this.validatePurchasePackageUseCase();
            })
    }

    validateUserProvidedFields() {
        this.validatorInstance = new Validator(this.payLoad, null);
        return this.validatorInstance.validate(this.purchaseMultiplePackagesEntityInstance.getValidationRules(), this.purchaseMultiplePackagesEntityInstance.getFieldsForUniqueness());
    }

    validatePurchasePackageUseCase() {
        this.initializePurchasePackageInteractors();
        return this.validatePurchasePackageUseCaseWithoutThrowingErrors();
    }

    initializePurchasePackageInteractors() {
        for (const thePackage of this.payLoad.packages) {
            this.purchaseSinglePackageInteractors.push((new PurchasePackageUseCase(thePackage)));
        }
    }

    validatePurchasePackageUseCaseWithoutThrowingErrors() {
        const validationPromises = [];
        for (const purchasePackageInteractor of this.purchaseSinglePackageInteractors) {
            validationPromises.push(purchasePackageInteractor.validateWithoutThrowingError());
        }
        return Promise.all(validationPromises).then((errorList) => ChainedDataHelper.convertNestedToFlatArray(errorList));
    }

    calculateTotalPaymentForAllPackages() {
        this.totalPayment = 0;
        for (const purchasePackageInteractor of this.purchaseSinglePackageInteractors) {
            this.totalPayment += parseFloat(purchasePackageInteractor.TotalPayment);
        }
    }

    performPurchaseAction() {
        return this.chargePayment()
            .then(() => {
                return this.handleFailPurchasePackageScenarioIfAny();
            })
    }

    handleFailPurchasePackageScenarioIfAny() {
        if (this.paymentResponse.status === "fail") {
            this.handlePaymentFailedScenario("package", "BusinessError from validate function in PurchasePackageUseCase", ErrorTypes.FORBIDDEN, this.paymentResponse.failure_code);
        }
    }

    performAfterPurchaseActions() {
        const afterPurchaseActionPromises = [];
        for (const purchasePackageInteractor of this.purchaseSinglePackageInteractors) {
            purchasePackageInteractor.PaymentResponse = JSON.parse(JSON.stringify(this.paymentResponse));
            afterPurchaseActionPromises.push(this.performSuccessfulPaymentPurchaseAction(purchasePackageInteractor));
            //this.notifyUserViaEmail(purchasePackageInteractor)
        }
        return Promise.all(afterPurchaseActionPromises);
    }

    performSuccessfulPaymentPurchaseAction(purchasePackageInteractor) {
        return purchasePackageInteractor.performSuccessfulPackagePurchaseActions();
    }

    notifyUserViaEmail(purchasePackageInteractor) {
        return purchasePackageInteractor.notifyUserViaEmail();
    }


}