<template>
  <div class="cart-item-box">
    <div class="card-item">
      <div class="cart-item-header">
        <p>selected</p>
        <p>price</p>
      </div>
      <div class="cart-item-body">
        <p class="cart-item-title">
          {{ $attrs.selectedPackage && $attrs.selectedPackage.name }}
        </p>
        <div class="grid grid-cols-2 gap-4">
          <div class="cart-item-info-box">
            <p class="cart-item-info-label">
              Package Type
            </p>
            <p class="cart-item-info-title">
              {{ $attrs.selectedPackage && $attrs.selectedPackage.package_name }}
            </p>
          </div>
          <div class="cart-item-info-box">
            <p class="cart-item-info-label">
              No. of Sessions
            </p>
            <p class="cart-item-info-title">
              {{ $attrs.selectedPackage && $attrs.selectedPackage.visits }}
            </p>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="cart-item-info-box">
            <p class="cart-item-info-label">
              Validity
            </p>
            <p class="cart-item-info-title">
              {{ $attrs.selectedPackage && $attrs.selectedPackage.validity_days }}
              Days
            </p>
          </div>
          <div class="cart-item-info-box">
            <p class="cart-item-info-label">
              Price
            </p>
            <p class="cart-item-info-title">
              AED {{ getPackagePrice() }}
            </p>
          </div>
          <div class="cart-item-info-box">
            <p class="cart-item-info-label">
              Vat
            </p>
            <p class="cart-item-info-title">
              AED {{ parseFloat(getExtraValueAfterCalculatingPercentage()).toFixed(2) }}
            </p>
          </div>
        </div>
        <p class="cart-item-package-title">
          Package Description
        </p>
        <p class="cart-item-package-desc" v-html="$attrs.selectedPackage && $attrs.selectedPackage.description" />
      </div>
      <div class="cart-item-footer">
        <p class="cart-item-footer-title">
          Total
        </p>
        <p class="cart-item-footer-price">
          AED
          {{
            getPackagePriceWithVat()
          }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    getPackagePrice() {
      if (this.$attrs.selectedPackage) {
        return this.$attrs.selectedPackage.discounted_price ? this.$attrs.selectedPackage.discounted_price : this.$attrs.selectedPackage.price;
      }
      return null;
    },
    getPackagePriceWithVat() {
      let totalPrice = this.getPackagePrice();
      if (totalPrice)
        return (totalPrice + this.getExtraValueAfterCalculatingPercentage()).toFixed(2);

      return null;
    },
    getValValue() {
      return parseInt(process.env.VUE_APP_VAT_VALUE);
    },
    getExtraValueAfterCalculatingPercentage() {
      let totalPrice = this.getPackagePrice();
      if (totalPrice)
        return ((this.getValValue() / 100) * totalPrice);

      return null;
    }
  }
};
</script>