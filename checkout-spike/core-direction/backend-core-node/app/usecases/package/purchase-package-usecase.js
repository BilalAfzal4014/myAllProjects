const PurchaseUseCase = require("../purchase/purchase-usecase");
const ErrorTypes = require("../../errors/error-types");
const PurchasePackageEntity = require("../../entities/purchase-package/purchase-single-package/purchase-package-enitity");
const Validator = require("../../entity-validations/validator");
const PackageRepo = require("../../repositories/packageRepo");
const {getEmailProvider} = require("../../providers/email-providers/email-provider-factory");
const FetchMemberPackageUseCase = require("./get-member-packages-usecase");
const FetchPackageUseCase = require("../package/fetch-package-usecase");
const SaveMemberPackageUseCase = require("../package/save-member-package-usecase");
const SaveMemberBillingDetailUseCase = require("../member-billing/save-member-billing-detail-usecase");
const ChainDateHelper = require("../../helpers/chained-data-helper");
const MemberKeyRepo = require("../../repositories/memberKeyRepo");
const FosUserUserRepo = require("../../repositories/fosUserUserRepo");

module.exports = class PurchasePackageUseCase extends PurchaseUseCase {
    constructor(packageData) {
        super();
        this.emailData = null;
        this.EMAIL_SUBJECT = "Purchase Package | Core Direction";
        this.package = packageData;
        this.PurchasePackageEntityInstance = new PurchasePackageEntity();
    }

    purchase() {
        return this.validate()
            .then(() => {
                return this.initializePaymentSelectionConfiguration({
                    ...this.package,
                    product: "package",
                    product_id: this.package.package_id
                });
            }).then(() => {
                return this.purchasePackage();
            }).then(() => {
                return this.handleFailPurchasePackageScenario();
            }).then(() => {
                return this.performSuccessfulPackagePurchaseActions();
            }).then(() => {
                 this.notifyUserViaEmail();
            }).then(() => {
                return {
                    member_package_id: this.paymentResponse.member_package_id,
                    user_id: this.package.user_id
                };
            });
    }

    notifyUserViaEmail() {
        return this.getDataForPackageEmail()
            .then(() => {
                return this.sendEmailToTheUser()
            })
    }

    getDataForPackageEmail() {

        let packageDetails = PackageRepo.findPackageById(this.package.package_id);
        let userDetail = FosUserUserRepo.findById(this.paymentResponse.user_id)
        return Promise.all([packageDetails, userDetail]).then((data) => {

            console.log(data)
            this.emailData = {
                email: data[1].email,
                userName: data[1].firstname + ' ' + data[1].lastname,
                name: data[0].name,
                price: data[0].public_rate,
                expiry: data[0].repeat_monthly ? 'N/A' : data[0].validity_days
            }
        })
    }

    sendEmailToTheUser() {
        this.emailProviderInstance = this.getEmailProvidedInstance();
        return this.emailProviderInstance.sendEmailsWithHtmlTemplate();
    }

    getEmailProvidedInstance() {
        const EmailProviderClass = getEmailProvider();
        return new EmailProviderClass(
            [this.emailData.email],
            this.EMAIL_SUBJECT,
            "",
            "templates/package/purchase.ejs", this.emailData
        );
    }

    validate() {
        return this.validateWithoutThrowingError()
            .then((errorList) => {
                this.handleErrorForValidations(errorList);
            });
    }

    validateWithoutThrowingError() {
        return this.validateUserProvidedFields()
            .then((errorList) => {
                return errorList.length ? errorList : this.validateIfUserExists();
            }).then((errorList) => {
                return errorList.length ? errorList : this.validateCustom();
            });
    }

    validateUserProvidedFields() {
        this.validatorInstance = new Validator(this.package, PackageRepo, true);
        return this.validatorInstance.validate(
            this.PurchasePackageEntityInstance.getValidationRules(),
            this.PurchasePackageEntityInstance.getFieldsForUniqueness()
        );
    }

    validateIfUserExists() {
        return this.fetchUserDetails()
            .then(() => {
                return this.userDetails ? [] : [{
                    field: "user_id",
                    error: "user doesn't exists"
                }];
            });
    }

    validateCustom() {
        return this.fetchPreReqsBeforeValidationCustom()
            .then(() => {
                return this.performAfterPreReqsFetchingValidations();
            });
    }

    fetchPreReqsBeforeValidationCustom() {
        return Promise.all([
            this.fetchPackage(),
            this.fetchVatValueFromConfigurations()
        ]);
    }

    fetchPackage() {
        return FetchPackageUseCase.fetchPackageById(this.package.package_id)
            .then((packageDetails) => {
                this.package.package_details = packageDetails;
            });
    }

    performAfterPreReqsFetchingValidations() {
        return Promise.all([
            this.validateIfPackageIsNotAssignedToUserAlready(),
            this.checkIfPackageAlreadyExpired(),
            this.validatePayment()
        ]).then((errorList) => {
            errorList = ChainDateHelper.convertNestedToFlatArray(errorList);
            return errorList;
        });
    }

    validateIfPackageIsNotAssignedToUserAlready(tlPrivileges = false) {
        if (!tlPrivileges) {
            return Promise.resolve([]);
        }

        return FetchMemberPackageUseCase.fetchSpecificActivePackageOfAMember(this.package.package_id, this.package.user_id)
            .then((packages) => {
                return packages.length ? [{
                    field: "package_id",
                    error: "This package is already assigned to this user"
                }] : [];
            });
    }

    checkIfPackageAlreadyExpired() {
        const currentDate = new Date();
        return currentDate > (new Date(this.package.package_details.expires_on)) ? [{
            field: "package_id",
            error: "Package has already ended"
        }] : [];
    }

    validatePayment() {
        return this.calculatePayment()
            .then(() => {
                return this.fetchPaymentErrorIfAny();
            });
    }

    calculatePayment() {
        return MemberKeyRepo.isDiscountExistForUser(this.package.user_id)
            .then((exist) => {

                this.totalPayment = (exist && this.package.package_details.corporate_rate) ? this.package.package_details.corporate_rate :
                    this.package.package_details.public_rate;
                this.totalPayment = this.vatValue ? this.totalPayment + ((this.vatValue / 100) * this.totalPayment) : this.totalPayment;
                return this.totalPayment = this.totalPayment.toFixed(2);
            });
    }

    handleErrorForValidations(errorList) {
        this.handleErrorIfExist(
            errorList,
            ErrorTypes.MISSING_ATTRIBUTES,
            "Purchase package Validation Failed",
            "BusinessError from validate function in PurchasePackageUseCase"
        );
    }

    purchasePackage() {
        return this.chargePayment();
    }

    handleFailPurchasePackageScenario() {
        if (this.paymentResponse.status === "fail") {
            this.handlePaymentFailedScenario(
                "package",
                "BusinessError from validate function in PurchasePackageUseCase",
                ErrorTypes.FORBIDDEN,
                this.paymentResponse.failure_code);
        }
    }

    performSuccessfulPackagePurchaseActions() {
        return this.saveMemberPackage()
            .then(() => {
                return this.saveMemberBillingDetail();
            }).then(() => {
                return {
                    member_package_id: this.paymentResponse.member_package_id,
                    user_id: this.package.user_id,
                    package_id: this.package.package_id
                };
            });
    }

    saveMemberPackage() {
        return (new SaveMemberPackageUseCase(this.prepareDateForMemberPackage()))
            .saveMemberPackage()
            .then((memberPackage) => {
                return this.paymentResponse.member_package_id = memberPackage?.id;
            });
    }

    prepareDateForMemberPackage() {
        return {
            //member_id: this.paymentResponse.user_id,
            member_id: this.package.user_id,
            package_id: this.package.package_id,
            is_deleted: 0,
            modifiedby: this.paymentResponse.user_id,
            checkin: 0,
            card_id: this.paymentResponse.card_id,
            is_promotion: 1,
            status: "active"
        }
    }

    saveMemberBillingDetail() {
        return (new SaveMemberBillingDetailUseCase(this.prepareDateForMemberBillingDetail()))
            .saveMemberBillingDetail()
            .then((memberBillingDetail) => {
                return this.paymentResponse.member_billing_detail_id = memberBillingDetail?.id;
            });
    }

    prepareDateForMemberBillingDetail() {
        return {
            member_billing_id: this.paymentResponse.member_billing_id,
            //amount: this.paymentResponse.amount,
            amount: this.totalPayment,
            member_package_id: this.paymentResponse.member_package_id,
        }
    }
}