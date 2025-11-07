<template>
  <div class="accordion-tab">
    <div class="info-content-box">
      <div v-if="biography?.length > BIOGRAPHY_LENGTH && isMore" class="desc"
           v-html="biography.substr(0,BIOGRAPHY_LENGTH) + '...'"
      />
      <div v-else class="desc-wrap">
        <div v-if="biography" class="desc" v-html="biography" />
        <CompanyAddress :address="address" :googlemap="googlemap" />
      </div>

      <button v-if="biography?.length > BIOGRAPHY_LENGTH" class="btn-toggle" @click="isMore = !isMore">
        {{ isMore ? "Show More" : "Show Less" }}
        <svg :class="!isMore ? 'rotate' : ''" fill="none" height="9" viewBox="0 0 15 9" width="15"
             xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M14.8844 1.38435C14.891 1.63792 14.7906 1.84351 14.6164 2.01484C12.4464 4.23528 10.283 6.45573 8.11297 8.66931C7.67761 9.11477 7.21547 9.10792 6.77342 8.66246C4.61006 6.45572 2.4534 4.24899 0.296739 2.0354C-0.0850308 1.64477 -0.0984262 1.13763 0.256552 0.72644C0.430693 0.527698 0.631624 0.342661 0.839253 0.17133C1.14735 -0.0753851 1.5693 -0.0479718 1.8774 0.21245C1.94438 0.267276 2.00465 0.335807 2.06493 0.397486C3.75276 2.11764 5.44058 3.83094 7.1351 5.5511C7.44989 5.8732 7.44989 5.8732 7.77808 5.54425C9.43241 3.8515 11.0867 2.15876 12.7411 0.472871C12.8683 0.349513 13.0023 0.23301 13.1496 0.130211C13.2769 0.0411199 13.4309 -5.87085e-07 13.5917 -5.94112e-07C14.1007 0.0137056 14.891 0.842943 14.8844 1.38435Z"
            fill="#690FAD"
          />
        </svg>
      </button>
    </div>
  </div>
</template>

<script>
import CompanyAddress from "@/partials/company/information/company-address.vue";

export default {
  name: "ProfileInfo",
  components: {
    CompanyAddress
  },
  props: {
    googlemap: {
      type: String,
      default: "",
    },
    biography: {
      type: String,
      default: "",
    },
    address: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      isMore: true,
      BIOGRAPHY_LENGTH: 500,
    };
  }
};
</script>

<style lang="scss" scoped>
.info-content-box {

  margin-left: auto;
  margin-right: auto;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  row-gap: 8px;
  padding-top: 24px;
}

.desc {
  width: 100%;
  max-width: 1160px;
  margin-left: auto;
  margin-right: auto;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 0px;
  @media screen and (max-width: 480px) {
    padding: 0px 14px;
  }

}

.desc-wrap {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.rotate {
  transform: rotate(-180deg);
}

.btn-toggle {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  margin-top: 30px;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-column-gap: 8px;
  column-gap: 8px;
  width: -webkit-max-content;
  width: -moz-max-content;
  width: max-content;
  color: #690FAD;
  background-color: #F1F1F1;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  padding: 8px 16px;
  text-align: center;
  font-size: 16px;
  font-family: 'Montserrat', sans-serif;
  font-weight: 600;
  text-transform: capitalize;
  margin-left: auto;
  margin-right: auto;
}
</style>