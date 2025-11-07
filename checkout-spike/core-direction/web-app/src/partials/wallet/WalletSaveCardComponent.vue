<template>
  <div class="payment-detail-content-box">
    <div class="wallet-table-header">
      <p class="wallet-selected-tab-heading">
        Payment Details
      </p>
      <p class="lg-screen-title">
        My Cards
      </p>
    </div>
    <save-card-listing />
  </div>
</template>

<script>
import SaveCardListing from "./WalletCard/SaveCardListing";
export default {
    name: "WalletSaveCardComponent",
    components: {SaveCardListing}
};
</script>

<style scoped>

</style>