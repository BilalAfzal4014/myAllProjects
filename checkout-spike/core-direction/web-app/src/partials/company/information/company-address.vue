<template>
  <div class="business-profile-address-box">
    <div class="business-profile-address-box-wrap">
      <p class="business-profile-title">
        Address
      </p>
      <button class="btn-business-address rounded-full flex items-center" @click="redirectGoogleMap">
        <company-address-icon />
        {{ address }}
      </button>
    </div>
  </div>
</template>

<script>
import CompanyAddressIcon from "../../../svgs/company/company-address-icon";
export default {
  name: "CompanyAddress",
  components: {CompanyAddressIcon},
  props:{
    address: {
      type: String,
      default: "",
    },
    googlemap: {
      type: String,
      default: "",
    },
  },
  methods:{
    redirectGoogleMap(){
      window.open(
        this.googlemap,
        "_blank"
      );
    }

  }
};
</script>

<style lang="scss" scoped>
.business-profile-address-box-wrap {
  width: 100%;
  max-width: 1160px;
  margin-left: auto;
  margin-right: auto;
  @media screen and (max-width: 480px) {
    padding: 0px 14px;
  }
}
#business-profile .business-profile-info-box .business-profile-address-box{
  margin-bottom: 0;
  padding-top: 16px;
  border-top: 1px solid gainsboro;
}
</style>

  