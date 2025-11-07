<template>
  <div>
    <main id="main">
      <section id="cart">
        <div class="custom-container">
          <div class="cart-header mx-auto">
            <div class="cart-header-inner-box">
              <div class="progress-bar">
                <div
                  :class="`package-detail-tab progress-status-box ${activeTab === tabsValues.DETAIL ? 'active' : ''} ${tabsValuesCollection.indexOf(activeTab) > tabsValuesCollection.indexOf(tabsValues.DETAIL) ? 'done': ''}`"
                >
                  <div class="progress-status" />
                  <button class="progress-status-label">
                    <span class="first-word">Enter</span> Details
                  </button>
                </div>
                <div
                  :class="`package-detail-tab progress-status-box ${activeTab === tabsValues.PACKAGE ? 'active' : ''} ${tabsValuesCollection.indexOf(activeTab) > tabsValuesCollection.indexOf(tabsValues.PACKAGE) ? 'done': ''}`"
                >
                  <div class="progress-status" />
                  <button class="progress-status-label">
                    <span class="first-word">Select</span> Packages
                  </button>
                </div>
                <div
                  :class="`package-detail-tab progress-status-box ${activeTab === tabsValues.PAYMENT ? 'active' : ''} ${tabsValuesCollection.indexOf(activeTab) > tabsValuesCollection.indexOf(tabsValues.PAYMENT) ? 'done': ''}`"
                >
                  <div class="progress-status" />
                  <button class="progress-status-label">
                    Payment
                  </button>
                </div>
                <div
                  :class="`package-detail-tab progress-status-box ${activeTab === tabsValues.CONFIRMATION ? 'active' : ''} ${tabsValuesCollection.indexOf(activeTab) === tabsValuesCollection.indexOf(tabsValues.CONFIRMATION) ? 'done': ''}`"
                >
                  <div class="progress-status" />
                  <button class="progress-status-label">
                    Confirmation
                  </button>
                </div>
              </div>
            </div>
          </div>
          <keep-alive>
            <component
              :is="activeTab"
              :confirmation="confirmationMessage"
              :make-payment-info-getter="informAboutPaymentCounter"
              :total-quantity="users.length"
              :update-total-users="updateTotalUsers"
              :update-user-type="updateUserType"
              :users="users"
              :validate-user-details="validateDetailTab"
              @chargePayment="performPurchaseAction"
              @updateUserPackage="updateUserPackage"
            />
          </keep-alive>

          <div class="cart-footer mx-auto">
            <div class="cart-footer-inner-box">
              <button v-if="activeTab !== tabsValuesCollection[tabsValuesCollection.length - 1]"
                      class="btn-back btn-cancel"
                      @click="goBackToPrevTab"
              >
                {{ tabsValuesCollection.indexOf(activeTab) === 0 ? 'Cancel' : 'Go Back' }}
              </button>

              <button v-if="activeTab !== tabsValuesCollection[tabsValuesCollection.length - 1]"
                      :disabled="disableButton"
                      class="btn-next btn-proceed rounded-full ml-auto continue_to_payment"
                      @click="proceed"
              >
                Proceed
              </button>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script>
import detail from "../../../partials/booking/multiple-booking/steps/detail";
import selectPackage from "../../../partials/booking/multiple-booking/steps/select-package";
import payment from "../../../partials/booking/single-booking/steps/payment";
import confirmation from "../../../partials/booking/single-booking/steps/confirmation";
import toastr from "toastr";
import {updateBookingCheckInCorePoints} from "@/apiManager/gamification";
import moment from "moment";
import {scrollToTopOnSameRoute} from "@/utils";
import userActivitiesTotal from "@/mixin/userActivitiesTotal";
import {createPurchaseEvent} from "@/apiManager/facebookPixel";

export default {
  name: "Booking",
  mixins: [userActivitiesTotal],
  components: {
    detail,
    package: selectPackage,
    payment,
    confirmation
  },
  data() {
    return {
      loggedInUser: this.$store.getters.getStoreUserProfileGetters(),
      confirmationMessage: {
        header: "Thank you for your booking",
        description: "This confirms your booking of the selected activity. Please feel free to share your booking with your network by selecting \"Share Your Activity\" or \"See Calendar\" to view all future bookings."
      },
      tabsValuesCollection: ["detail", "package", "payment", "confirmation"],
      tabsValues: {
        DETAIL: "detail",
        PACKAGE: "package",
        PAYMENT: "payment",
        CONFIRMATION: "confirmation"
      },
      activeTab: "details",
      singleUser: {
        type: "",
        typeError: "",
        firstName: "",
        firstNameError: "",
        lastName: "",
        lastNameError: "",
        email: "",
        emailError: "",
        package: {}
      },
      users: [{
        type: "",
        typeError: "",
        firstName: "",
        firstNameError: "",
        lastName: "",
        lastNameError: "",
        email: "",
        emailError: "",
        package: {}
      }],
      informAboutPaymentCounter: 0,
      isValidationOpenForDetailTab: false,
      disableButton: false,
      isHitReserveActivity: false
    };
  },
  created() {
    window.addEventListener("beforeunload", this.unReserveActivityForUsers);
  },
  beforeDestroy() {
    window.removeEventListener("beforeunload", () => {
    });
    this.unReserveActivityForUsers();
  },
  mounted() {
    this.activeTab = this.tabsValues.DETAIL;
  },
  methods: {
    updateTotalUsers(operation, index) {
      if (operation === "increment") {
        this.users.push({...this.singleUser});
      } else if (operation === "decrement" && this.users.length > 1) {
        const user = this.users[index];
        this.unReserveActivityForUser(user);
        this.users.splice(index, 1);
      }
    },
    updateUserPackage(packageDetails, index) {
      const users = [...this.users];
      this.users[index].package = packageDetails;
      this.users = [...users];
    },
    proceed() {
      if (this.activeTab === this.tabsValues.DETAIL) {
        this.isValidationOpenForDetailTab = true;
        if (this.validateDetailTab())
          this.nextTab();
      } else if (this.activeTab === this.tabsValues.PACKAGE) {
        if (this.validateUserPackagesTab())
          this.reserveActivity();
      } else if (this.activeTab === this.tabsValues.PAYMENT) {
        this.informAboutPayment();
      }
      scrollToTopOnSameRoute();
    },
    nextTab() {
      const currentTabIndex = this.tabsValuesCollection.indexOf(this.activeTab);
      if (currentTabIndex < this.tabsValuesCollection.length - 1)
        this.activeTab = this.tabsValuesCollection[currentTabIndex + 1];
      scrollToTopOnSameRoute();
    },
    goBackToPrevTab() {
      const currentTabIndex = this.tabsValuesCollection.indexOf(this.activeTab);
      if (currentTabIndex > 0) {
        this.activeTab = this.tabsValuesCollection[currentTabIndex - 1];
      } else {
        window.history.back();
      }
      scrollToTopOnSameRoute();
    },
    informAboutPayment() {
      this.disableButton = true;
      this.informAboutPaymentCounter++;
    },
    performPurchaseAction(payLoad) {
      this.purchaseMultiplePackages(payLoad)
        .then(async (data) => {
          this.collectMemberPackageIds(data.data, this.users);
          await createPurchaseEvent({
            "event_name": "Purchase",
            "custom_data": {
              "value": this.getTotalPrice()
            }
          });
          return this.bookActivity();
        }).finally(() => {
          this.disableButton = false;
        });
    },
    purchaseMultiplePackages(payLoad) {
      if (payLoad.validation === "success") {
        return this.oldApi("post",
          this.constants.getUrl("purchasePackages"), {
            ...payLoad,
            packages: this.users.map((user) => ({
              user_id: user.userId,
              package_id: user.package.package_id
            }))
          },
          true
        ).catch((error) => {
          this.displayErrors(error);
          return Promise.reject(false);
        });
      }
      return Promise.reject(false);
    },
    bookActivity() {
      this.isHitReserveActivity = false;
      const payLoad = this.getBookActivityPayLoad();
      return this.createActivity(payLoad)
        .then(async () => {
          let {users} = this.getBookActivityPayLoad();
          let emails = users.map(function (el) {
            return el.email;
          });
          await updateBookingCheckInCorePoints(
            this.getBookingPointsPayload(emails, moment(this.$route.query.classDate).format("YYYY-MM-DD"))
          );
          this.nextTab();

        }).catch((error) => {
          this.displayErrors(error);
        });
    },
    reserveActivity() {
      this.isHitReserveActivity = true;
      const payLoad = this.getBookActivityPayLoad();
      this.disableButton = true;
      return this.createActivity(payLoad)
        .then((users) => {
          this.fillUpUserIds(users);
          this.nextTab();
        }).catch((error) => {
          this.handleFailureScenariosForReserveActivity(error);
        }).finally(() => {
          this.disableButton = false;
        });
    },
    createActivity(payLoad) {
      return this.oldApi("post",
        this.constants.getUrl("bookActivityForUsers"),
        payLoad
      ).then((users) => {
        return users.data;
      }).catch((error) => {
        toastr.error(error.errors[0].error);
      });
    },
    getBookActivityPayLoad() {
      return {
        users: this.users.map((user) => ({
          ...(user.userId && {user_id: user.userId}),
          type: user.type.replace(/ /g, ""),
          first_name: user.firstName,
          last_name: user.lastName,
          email: user.email ? user.email.trim() : user.email,
          package_id: user.package.package_id,
          member_package_id: user.memberPackageId ? user.memberPackageId : "N/A",
        })),
        schedule_detail_id: this.$route.params.scheduleId
      };
    },
    fillUpUserIds(users) {
      let index = 0;
      for (const user of users) {
        this.users[index].userId = user.user_id;
        index++;
      }
    },
    collectMemberPackageIds(data, users) {
      this.collectField({
        sourceData: data,
        sourceKey: "user_id",
        targetKey: "member_package_id"
      }, {
        sourceData: users,
        sourceKey: "userId",
        targetKey: "memberPackageId"
      });
    },
    collectField(source, destination) {
      const hash = {};
      for (let element of source.sourceData) {
        hash[element[source.sourceKey]] = element[source.targetKey];
      }

      for (let element of destination.sourceData) {
        element[destination.targetKey] = hash[element[destination.sourceKey]];
      }
    },
    handleFailureScenariosForReserveActivity(error) {
      this.displayErrors(error);
    },
    displayErrors(error) {
      if (error?.errors[0].error) {
        toastr.error(error.errors[0].error);
        return false;
      }
      if (error[0]?.response?.data) {
        for (let errorElement of error[0].response.data.errors) {
          toastr.error(errorElement.error);
        }
      }
    },
    validateDetailTab() {
      let validated = true;
      if (this.isValidationOpenForDetailTab) {
        for (const user of this.users) {
          if (user.type === "") {
            user.typeError = "Type is required";
            validated = false;
          } else {
            user.typeError = "";
          }

          if (user.firstName === "") {
            user.firstNameError = "First name is required";
            validated = false;
          } else {
            user.firstNameError = "";
          }

          if (user.lastName === "") {
            user.lastNameError = "Last name is required";
            validated = false;
          } else {
            user.lastNameError = "";
          }

          const emailRegularExpression = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegularExpression.test(user.email.trim())) {
            user.emailError = "Email is not valid";
            validated = false;
          } else {
            user.emailError = "";
          }
        }
      }
      return validated;
    },
    validateUserPackagesTab() {
      let validated = true;
      for (const user of this.users) {
        if (!Object.keys(user.package).length) {
          toastr.error(`Please choose the package for user ${user.email}`);
          validated = false;
        }
      }
      return validated;
    },
    updateUserType({type, index}) {
      this.users[index].type = type;
      if (type === "For Me") {
        this.users[index].email = this.loggedInUser.email;
        this.users[index].firstName = this.loggedInUser.firstname;
        this.users[index].lastName = this.loggedInUser.lastname;
      } else if (type === "For Under 18") {
        this.users[index].email = this.loggedInUser.email;
        this.users[index].firstName = "";
        this.users[index].lastName = "";
      }
    },
    unReserveActivityForUser(user) {
      if (user.userId) {
        this.oldApi("patch", this.constants.getUrl("cancelReserveActivity"), {
          member_ids: [user.userId]
        });
      }
    },
    unReserveActivityForUsers() {
      if (this.isHitReserveActivity) {
        this.oldApi("patch", this.constants.getUrl("cancelReserveActivity"), {
          member_ids: this.users.map((user) => user.userId)
        });
      }
    },
    getBookingPointsPayload(emails, startDate) {
      return {
        userEmails: emails,
        type: "BOOK_ACTIVITY",
        startDate: startDate,
      };
    },
  },
};
</script>

<style src="../../../common/css/purhcase-package-and-booking.css"></style>
<style scoped>
#cart .cart-footer {
  margin-top: 60px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  width: 100%;
  max-width: 844px;
}

@media screen and (max-width: 767px) {
  #cart .cart-footer {
    margin-top: 71px;
  }
}

#cart .cart-footer .cart-footer-inner-box {
  width: 100%;
  max-width: 444px;
  margin: auto;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

@media screen and (max-width: 374px) {
  #cart .cart-footer .cart-footer-inner-box {
    justify-content: center;
  }
}

#cart .cart-footer .btn-cancel,
#cart .cart-footer .btn-proceed {
  display: inline-block;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  padding: 11px 61px;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  -webkit-box-shadow: 0px 2px 4px 0px #00000040;
  box-shadow: 0px 2px 4px 0px #00000040;
}

@media screen and (max-width: 767px) {
  #cart .cart-footer .btn-cancel,
  #cart .cart-footer .btn-proceed {
    padding: 11px 50px;
  }
}

@media screen and (max-width: 374px) {
  #cart .cart-footer .btn-cancel,
  #cart .cart-footer .btn-proceed {
    margin: 0;
  }
}

#cart .cart-footer .btn-cancel {
  color: #000000;
  background-color: #FFFFFF;
}

#cart .cart-footer .btn-proceed {
  color: #FFFFFF;
  background-color: #690FAD;
}

</style>
