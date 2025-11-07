<template>
  <div class="package-header">
    <p class="package-name">
      {{ pack.name }}
    </p>
    <div class="package-header-info-box">
      <p class="package-current-price">
        {{ pack.discounted_price ? pack.discounted_price : pack.price }} AED
      </p>
      <p class="package-prev-price">
        {{ pack.discounted_price ? pack.price + "AED" : "" }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
    name: "PackageHeader",
    props:{
        pack: {
            type: Object,
            required: true,
        },
    }
};
</script>

<style scoped>

</style>