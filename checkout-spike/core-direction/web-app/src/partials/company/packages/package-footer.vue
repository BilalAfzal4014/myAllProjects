<template>
  <div class="package-footer">
    <div class="package-footer-info-box">
      <p class="class-pass">
        {{ pack.visits }} Sessions
      </p>
      <p class="package-validity">
        Validity: {{ pack.validity_days }} days
      </p>
    </div>
    <button class="btn-package-buy" @click="buyPackage(pack.package_id)">
      Buy
    </button>
  </div>
</template>

<script>
import Emitter from "tiny-emitter/instance";

export default {
    name: "PackageFooter",
    props: {
        pack: {
            type: Object,
            required: true
        }
    },
    methods: {
        buyPackage(packageId) {
            if (!!this.$store.getters.getStoreTokenGetters) {
                this.$router.push(`/purchase-package/${packageId}`);
            } else {
                const body = document.querySelector("body");
                body.classList.add("overflow-y-hidden");
                Emitter.emit("sign_in_modal", "show sign in modal");
            }
        }

    }

};
</script>

<style scoped>
.btn-package-buy:hover {
  color: #fff;
  background-color: #690FAD;
}
</style>