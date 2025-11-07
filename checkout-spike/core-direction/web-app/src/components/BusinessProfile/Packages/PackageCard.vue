<template>
  <div :class="`package-card ${isBgWhite ? '' : 'bg-not-white'}`">
    <div class="package-header">
      <p class="package-name">
        {{ packageName }}
      </p>
      <div class="package-header-info-box">
        <p class="package-current-price">
          {{ packagePrice }} AED
        </p>
        <p v-if=" packageDiscountedPrice " class="package-prev-price">
          {{ packageDiscountedPrice }} AED
        </p>
      </div>
    </div>
    <button class="btn-package-detail" @click="getPackageDescription(pack)">
      <InfoIconCurl />
      View Package Details
    </button>
    <div class="package-footer">
      <div class="package-footer-info-box">
        <p class="class-pass">
          {{ packageVisits }} Sessions
        </p>
        <p class="package-validity">
          Validity: {{ packageValidityDays }} days
        </p>
      </div>
      <button class="btn-package-buy" @click="buyPackage(pack.package_id)">
        Buy
      </button>
    </div>
  </div>
</template>

<script>
import InfoIconCurl from "@/svgs/InfoIconCurl.vue";
import Emitter from "tiny-emitter/instance";


export default {
  name: "PackageCard",
  components: {
    InfoIconCurl
  },
  props: {
    pack: {
      type: Object,
      required: true
    },

    packageName: {
      type: String,
      default: "",
    },
    packagePrice: {
      type: String,
      default: "",
    },
    packageDiscountedPrice: {
      type: String,
      default: "",
    },
    packageVisits: {
      type: Number,
      default: null,
    },
    packageValidityDays: {
      type: Number,
      default: null,
    },
    isBgWhite: {
      type: Boolean,
      default: true,
    },


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
    },
    getPackageDescription(description) {
      this.$emit("sendPackageDescription", description);
    }
  }

};
</script>

<style lang="scss" scoped>
.package-card {
  display: flex;
  flex-direction: column;
  row-gap: 16px;
  background-color: #ffff;
  padding: 32px;
  box-shadow: 0px 12px 16px rgba(28, 4, 47, 0.08), 0px 4px 6px rgba(28, 4, 47, 0.03);
  border-radius: 8px;
  @media (max-width: 767px) {
    padding: 20px;
    row-gap: 8px;
  }

  &.bg-not-white {
    background: #FFFFFA;
  }

  .package-header {
    column-gap: 8px;

    .package-name {
      font-family: 'Montserrat', sans-serif;
      font-size: 14px;
      font-style: normal;
      font-weight: 600;
      line-height: 17px;
      letter-spacing: 0em;
      text-align: left;
      margin-bottom: 0;
      text-transform: uppercase;
      color: #06070E;
      width: 100%;
      max-width: 232px;
      height: 51px;
      @media screen and (max-width: 767px) {
        font-size: 12px;
        line-height: 15px;
      }
    }

    .package-current-price {
      font-family: 'Montserrat', sans-serif;
      font-size: 14px;
      font-style: normal;
      font-weight: 700;
      line-height: 17px;
      letter-spacing: 0em;
      text-align: right;
      @media screen and (max-width: 767px) {
        font-size: 12px;
        line-height: 15px;
      }
    }

    .package-prev-price {
      font-family: 'Montserrat', sans-serif;
      font-size: 14px;
      font-style: normal;
      font-weight: 600;
      line-height: 17px;
      letter-spacing: 0em;
      text-align: right;
      margin-top: 4px;
      margin-bottom: 0;
      color: #06070E;
      opacity: .45;
      @media screen and (max-width: 767px) {
        font-size: 12px;
        line-height: 15px;
      }
    }
  }

  .btn-package-detail {
    display: flex;
    align-items: center;
    font-family: 'Montserrat', sans-serif;
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: 15px;
    letter-spacing: 0em;
    text-align: left;
    text-decoration: underline;
    padding: 6px 4px;
    color: #06070E;
    width: max-content;
    margin-top: 0;
    margin-bottom: 0;

    &:hover {
      color: #690FAD;
      font-weight: 600;

      svg, path {
        fill: #690FAD;
      }
    }

    @media screen and (max-width: 767px) {
      font-size: 10px;
      line-height: 12px;
    }
  }

  .session-available {
    font-family: 'Montserrat', sans-serif;
    font-size: 10px;
    font-style: normal;
    font-weight: 400;
    line-height: 12px;
    letter-spacing: 0em;
    text-align: left;
  }

  .class-pass {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 17px;
    letter-spacing: 0em;
    text-align: left;
    margin-top: 3px;
    margin-bottom: 4px;
    color: #06070E;
    @media screen and (max-width: 767px) {
      font-size: 12px;
      line-height: 15px;
    }
  }

  .package-validity {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 17px;
    letter-spacing: 0em;
    text-align: left;
    margin-bottom: 0;
    color: #06070E;
    @media screen and (max-width: 767px) {
      font-size: 12px;
      line-height: 15px;
    }
  }

  .btn-package-buy {
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 20px;
    letter-spacing: 0em;
    text-align: center;
    padding: 12px 20px;
    color: #fff;
    background-color: #690FAD;
    border-radius: 4px;
    margin-bottom: 0;

    &:hover {
      background-color: #8C14E6;
    }

    &:active,
    &.active {
      color: #690FAD;
      background-color: #CAA8F5;
    }

    @media screen and (max-width: 767px) {
      font-size: 12px;
      line-height: 15px;
    }
  }

  .package-header,
  .package-footer {
    display: grid;
    grid-template-columns: 5fr 2fr;
    column-gap: 10px;
  }
}

</style>