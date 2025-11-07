<template>
  <div class="cart-item-box">
    <div class="card-item">
      <div class="cart-item-header">
        <p>selected</p>
        <p>price</p>
      </div>
      <div class="cart-item-body">
        <ul class="selected-packages-list">
          <li v-for="(user, userIndex) in users" :key="userIndex" class="selected-package-item">
            <button class="btn-remove" @click="updateTotalUsers('decrement', userIndex)">
              <svg fill="none" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M14.4967 2.25612L2.24902 14.5064C1.65748 15.0981 0.936611 15.3587 0.652111 15.0741L0.514656 14.9366C0.233354 14.6553 0.48399 13.9378 1.08226 13.3394L13.3366 1.08238C13.9282 0.49071 14.649 0.230099 14.9335 0.51466L15.0742 0.655341C15.3488 0.943427 15.0949 1.65773 14.4967 2.25612Z"
                  fill="black"
                />
                <path
                  d="M1.08904 2.25612L13.3367 14.5064C13.9282 15.0981 14.6491 15.3587 14.9336 15.0741L15.071 14.9366C15.3523 14.6553 15.1017 13.9378 14.5034 13.3394L2.24908 1.08238C1.65754 0.49071 0.936669 0.230099 0.652169 0.51466L0.511518 0.655341C0.236936 0.943427 0.49077 1.65773 1.08904 2.25612Z"
                  fill="black"
                />
              </svg>
            </button>
            <div class="package-info">
              <p class="package-holder-name">
                {{ user.firstName }} {{ user.lastName }}
              </p>
              <p class="package-name">
                {{ user.package.name }}
              </p>
            </div>
            <p class="package-price">
              {{ user.package.discounted_price ? user.package.discounted_price : user.package.price }} AED
            </p>
          </li>
        </ul>
      </div>
      <div class="cart-item-footer flex justify-between	">
        <p class="text-info-bold">
          Vat Percentage
        </p>
        <p class="text-info">
          {{ getVatValue() }}%
        </p>
      </div>
      <div class="cart-item-footer flex justify-between	">
        <p class="text-info-bold">
          Vat
        </p>
        <p class="text-info">
          AED {{ getVatPercentage(calculateTotalPrice()).toFixed(2) }}
        </p>
      </div>
      <div class="cart-item-footer">
        <p class="cart-item-footer-title">
          Total
        </p>
        <p class="cart-item-footer-price">
          {{
            getTotalPrice()
          }} AED
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import userActivitiesTotal from "@/mixin/userActivitiesTotal";

export default {
  mixins: [userActivitiesTotal],
  props: {
    users: {
      type: Array,
      required: true
    },
    updateTotalUsers: {
      type: Function,
      required: true
    }
  },
  created() {
    this.getTotalPrice();
  },

};
</script>

<style scoped>
.text-info-bold {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-style: normal;
  font-weight: 600;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  text-transform: uppercase;
}

.text-info {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-style: normal;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  text-transform: uppercase;
}

</style>