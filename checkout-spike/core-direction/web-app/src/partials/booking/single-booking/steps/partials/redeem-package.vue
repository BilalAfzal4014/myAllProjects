<template>
  <div class="gift-code-box">
    <p class="select-package-title">
      Redeem with a Gift Code or Corporate Key
    </p>
    <div class="package-card-box select-package-section">
      <div class="package-card">
        <div class="package-header">
          <div class="package-header-info-box">
            <p class="package-name">
              gift code / corporate key
            </p>
            <p class="gift-code-desc">
              Enter your code below to redeem your membership or package .
            </p>
          </div>
        </div>
        <div class="package-footer">
          <input v-model="redeemCode" class="form-control" placeholder="XX-XXX-XXX-XX" type="text">
          <button class="btn-package-buy" @click="redeemPackage">
            Redeem
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import toastr from "toastr";

export default {
    data() {
        return {
            redeemCode: ""
        };
    },
    methods: {
        redeemPackage() {
            if (this.redeemCode === "") {
                toastr.error("Please enter redeem code");
                return;
            }

            this.oldApi("get",
                this.constants.getUrl("redeemPackage") + "/" + this.redeemCode
            ).then(() => {
                this.$emit("fetchPreReqsAgain");
            }).catch((error) => {
                if (error[0]?.response?.data?.message) {
                    toastr.error(error[0].response.data.message);
                }
            });
        }
    }
};
</script>

<style>

</style>