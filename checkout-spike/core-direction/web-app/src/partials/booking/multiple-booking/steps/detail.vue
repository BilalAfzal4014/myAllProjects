<template>
  <div class="cart-body mx-auto">
    <div class="package-detail-box">
      <quantity
        :total-quantity="totalQuantity"
        @updateTotalQuantity="updateTotalUsers"
      />
      <detailBoxKeeper
        :update-total-users="updateTotalUsers"
        :update-user-type="updateUserType"
        :users="users"
        :validate-user-details="validateUserDetails"
      />
    </div>
  </div>
</template>

<script>
import quantity from "@/partials/booking/single-booking/steps/partials/quantity";
import detailBoxKeeper from "@/partials/booking/single-booking/steps/partials/detail-box-keeper";

export default {
  name: "UserDetails",
  props: {
    users: {
      type: Array,
      required: true,
    },
    totalQuantity: {
      type: Number,
      required: true
    },
    updateTotalUsers: {
      type: Function,
      required: true
    },
    validateUserDetails: {
      type: Function,
      required: true
    },
    updateUserType: {
      type: Function,
      required: true
    }
  },
  components: {
    quantity,
    detailBoxKeeper
  }
};
</script>

<style>
#cart .package-detail-box .package-detail-title {
  color: #000000;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-style: normal;
  font-weight: 700;
  line-height: 21px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 30px;
}

@media (max-width: 767px) {
  #cart .package-detail-box .package-detail-title {
    font-size: 14px;
    line-height: 16px;
  }
}
</style>