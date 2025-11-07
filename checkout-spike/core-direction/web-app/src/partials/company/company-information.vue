<template>
  <div class="custom-container">
    <div class="business-profile-info-box flex items-center flex-wrap">
      <div class="business-profile-short-info-box flex mx-auto">
        <div class="business-profile-logo-box rounded-full">
          <img :src="companyLogo" alt="" class="rounded-full">
        </div>
        <div class="business-profile-name-box">
          <p class="business-profile-brand-title">
            {{ companyName }}
          </p>
          <p class="business-profile-brand-subtitle">
            {{ address }}
          </p>
        </div>
      </div>
      <div class="business-profile-contact-info-box flex items-center">
        <button v-if="isCorporate" class="btn-brand-number rounded-full flex items-center"
                @click="showUnsubModal = true"
        >
          Leave Corporate
        </button>
        <button v-else class="btn-brand-number rounded-full flex items-center" @click="openPhone($event, phone)">
          <telephone />
          {{ phone }}
        </button>
        <a :href="website" class="btn-brand-map rounded-full" target="_blank">Visit Our Website</a>
        <a :href="googlemap" class="btn-business-map rounded-full" target="_blank">
          <location-pointer />
        </a>
      </div>
    </div>
    <unsubsribe-confirmation v-if="showUnsubModal" @close="showUnsubModal = false" :companyId="companyId" />
  </div>
</template>

<script>
import Telephone from "../../svgs/company/telephone";
import LocationPointer from "../../svgs/company/location-pointer";
import UnsubsribeConfirmation from "@/partials/company/UnsubsribeConfirmation.vue";

export default {
  name: "CompanyInformation",
  components: {UnsubsribeConfirmation, LocationPointer, Telephone},
  props: {
    website: {
      type: String,
      default: "",
    },
    phone: {
      type: String,
      default: "",
    },
    companyName: {
      type: String,
      default: "",
    },
    companyLogo: {
      type: String,
      default: "",
    },
    address: {
      type: String,
      default: "",
    },
    googlemap: {
      type: String,
      default: "",
    },
    isCorporate: {
      type: Boolean,
      default: false
    },
    companyId: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      showUnsubModal: false
    };
  },
  methods: {
    openPhone(event, phone) {
      window.open(`tel:${phone}`);
    },
  },
};
</script>

<style scoped>
.business-profile-name-box {
  max-width: 350px;
  width: 100%;

}

@media (min-width: 992px) {
  .business-profile-short-info-box {
    width: 100%;
    max-width: 474px;
  }
}

@media (max-width: 991px) {
  .business-profile-short-info-box {
    margin-left: 0;
  }
  #business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-title,#business-profile .business-profile-info-box .business-profile-contact-info-box{
    margin-top: 20px;
    padding-top: 0px;
    padding-bottom: 0px;
    margin-bottom: 3px;
  }
  #business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-subtitle{
    margin-top: -6px;
  }
  #business-profile .business-profile-info-box{
    padding-bottom: 22px;
  }
}
@media (max-width: 480px) {
  .business-profile-short-info-box {
    margin-left: 0;
  }
#business-profile .business-profile-info-box .business-profile-contact-info-box{
    margin-top: 10px;
    padding-top: 0px;
    padding-bottom: 0px;
    margin-bottom: 3px;
  }
  #business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-subtitle{
    margin-top: -6px;
  }
  #business-profile .business-profile-info-box{
    padding-bottom: 0px;
    margin-bottom: 12px;
  }
  #business-profile .business-profile-info-box .business-profile-short-info-box{
    padding-bottom: 0px;
    margin-bottom: 12px;
    gap: 4px;
  }
  #business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-subtitle{
    font-size: 14px !important;
  }
  #business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-title{
    margin-top: 22px;
  }
}
</style>
