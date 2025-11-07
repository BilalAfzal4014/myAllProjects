<template>
  <div class="cart-body mx-auto">
    <div class="payment">
      <usersPackageDetails
        v-if="users"
        :update-total-users="updateTotalUsers"
        :users="users"
      />
      <packageDetails v-else
                      v-bind="$attrs"
      />
      <div class="cart-payment-box">
        <ul class="payment-option-list">
          <li class="payment-option-item">
            <input id="my-card" v-model="paymentVia"
                   :value="'saveCards'"
                   name="radio"
                   type="radio"
            >
            <label for="my-card">My card</label>
          </li>
          <li class="payment-option-item">
            <input id="credit-card" v-model="paymentVia"
                   :value="'creditCard'"
                   name="radio"
                   type="radio"
            >
            <label for="credit-card">Credit card</label>
          </li>
        </ul>
        <p class="cart-payment-title">
          Payment information
        </p>
        <keep-alive>
          <component
            :is="paymentVia"
            :make-payment-with-credit-card-info-getter="informCreditCardToReturnValue"
            :make-payment-with-save-card-info-getter="informSaveCardToReturnValue"
            @sendPaymentResponse="getPaymentResponse"
          />
        </keep-alive>
      </div>
    </div>
  </div>
</template>

<script>
import PackageDetails from "./partials/package-details";
import usersPackageDetails from "../../common/users-package-details";
import saveCards from "./partials/save-cards";
import creditCard from "./partials/credit-card";
import toastr from "toastr";

export default {
  components: {
    PackageDetails,
    usersPackageDetails,
    saveCards,
    creditCard
  },
  props: {
    makePaymentInfoGetter: {
      type: Number,
      required: true
    },
    users: {
      type: Array,
      required: false,
      default: () => {
      }
    },
    updateTotalUsers: {
      type: Function,
      required: false,
      default: () => {
      }
    }
  },
  data() {
    return {
      tabsValues: ["saveCards", "creditCard"],
      paymentVia: "creditCard",
      informSaveCardToReturnValue: 0,
      informCreditCardToReturnValue: 0
    };
  },
  methods: {
    handlePayment() {
      switch (this.paymentVia) {
      case "saveCards":
        this.handlePaymentForSaveCards();
        break;
      case "creditCard":
        this.handlePaymentForCreditCards();
        break;
      }
    },
    handlePaymentForSaveCards() {
      this.informSaveCardToReturnValue++;
    },
    handlePaymentForCreditCards() {
      this.informCreditCardToReturnValue++;
    },
    getPaymentResponse(response) {
      if (this.users && this.users.length) {
        return this.$emit("chargePayment", response);
      }

      // in future we need to extract below code and write it down to parent component who is the caller of this one
      if (response.validation === "success") {
        response = {
          ...response,
          package_id: this.$attrs.selectedPackage.package_id
        };
        this.chargePaymentToPurchasePackage(response);
      } else {
        this.$emit("getPostPaymentData", "");
      }

    },

    chargePaymentToPurchasePackage(payLoad) {

      // payLoad["user_id"] = 4329;
      // console.info(payLoad, "PAYLOAD");
      return this.oldApi("post",
        this.constants.getUrl("purchasePackage"),
        payLoad,
        true
      ).then((response) => {
        this.$emit("getPostPaymentData", response.data);
      }).catch((error) => {
        this.$emit("getPostPaymentData", "");
        if (error[0]?.response?.data) {
          for (let errorElement of error[0].response.data.errors) {
            toastr.error(errorElement.error);
          }
        }
      });
    }
  },
  watch: {
    makePaymentInfoGetter() {
      this.handlePayment();
    }
  }
};

</script>

<style>
.add-payment-info-box .form-label {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-style: normal;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 9px;
  text-transform: uppercase;
}

.add-payment-info-box .form-control::-webkit-input-placeholder {
  color: #272727;
}

.add-payment-info-box .form-control:-ms-input-placeholder {
  color: #272727;
}

.add-payment-info-box .form-control::-ms-input-placeholder {
  color: #272727;
}

.add-payment-info-box .form-control::placeholder {
  color: #272727;
}

.add-payment-info-box .form-control {
  font-family: 'Montserrat', sans-serif;
  font-size: 11px;
  font-style: normal;
  font-weight: 400;
  line-height: 13px;
  letter-spacing: 0em;
  text-align: left;
  padding: 12px 13px;
  color: #000000;
  background-color: #F1F1F1;
  border-radius: 11px;
  margin-bottom: 10px;
  width: 100%;
}


</style>