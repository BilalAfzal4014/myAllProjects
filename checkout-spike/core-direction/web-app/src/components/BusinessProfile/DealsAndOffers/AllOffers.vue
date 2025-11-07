<template>
  <div class="custom-container">
    <div v-if="offerList.length > 0" class="profile-packages-box">
      <OffersCard v-for="(offer,index) in offerList" :key="index" :availableAt="offer.available_at ?? null" :discountPercentage="offer.discount_percentage ?? null"
                  :isCode="offer.is_redemption_code_required" :offerDescription="offer.description ?? ``"
                  :offerId="offer.id ?? null" :offerName="offer.name ?? ``"
                  :offerRenewal="offer.offer_renew_date ?? ``" :offerType="offer.offer_type ?? ``"
                  :termsAndConditions="offer.terms_conditions ?? ``" :offerProvider="offer?.Facility?.company_name"
                  :totalRedemptions="offer.allowed_number_of_redemption ?? null"
                  :userRedemptions="offer.user_redemptions ?? null"
                  :isUnlimited="offer.is_unlimited_redemptions"
                  @offerId="increamentRedemption($event,index)"
      />
    </div>
  </div>
</template>

<script>
import OffersCard from "./OffersCard.vue";
import {getOffers} from "@/apiManager/offers";

export default {
  name: "AllCards",
  components: {
    OffersCard
  },
  data() {
    return {
      offerList: [],
      totalOffers: null,
      offSet: this.offerOffset,
      limit: this.offerLimit,
    };
  },
  props: {
    offerOffset: {
      type: Number,
      default: 0,
    },
    offerLimit: {
      type: Number,
      default: 0,
    },
    companyId: {
      type: Number,
      required: false,
      default: null,
    }

  },
  watch:{
    companyId(newValue){
      if(newValue){
        this.getAllOffers();
      }
    }
  },
  computed: {
    companySlug() {
      return this.$route.params.slug;
    }
  },
  methods: {
    async loadMore() {
      if (this.offerList.length < this.totalOffers) {
        const res = await getOffers(this.companyId, this.limit, this.offSet);
        if (res.statusCode === 200 && res.data && res.data.length > 0) {
          this.offerList = this.offerList.concat(res.data);
          this.offSet += this.limit;
        }
      }
    },
    increamentRedemption(offerId,index){
      this.offerList.find((offer)=>{
        if(!offer?.is_unlimited_redemptions  && offerId === offer.id && offer?.user_redemptions < offer?.allowed_number_of_redemption){
          return ++this.offerList[index].user_redemptions;
        }
      });
    },
    async getAllOffers(){
      if(this.companyId){
        const res = await getOffers(this.companyId, this.limit, this.offSet);
        if (res.statusCode === 200 && res.data && res.data.length > 0) {
          this.offerList = res.data;
          this.totalOffers = res?.meta?.item_count;
          this.offSet = this.limit;
        }
      }
    },
    getNextOffers() {
      window.onscroll = () => {
        const {scrollHeight, scrollTop, clientHeight} = document.documentElement;
        if (scrollTop + clientHeight >= scrollHeight) {
          this.loadMore();
        }
        ;
      };
    }
  },
  async beforeMount() {
    this.getAllOffers();
  },
  created() {
    if (this.companyId) {
      this.getNextOffers();
    }

  }
};
</script>

<style lang="scss" scoped>
.custom-container {
  width: 100%;
  max-width: calc(1240px + 40px);
  padding: 0 20px;
  margin-left: auto;
  margin-right: auto;

  .profile-packages-box {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    -webkit-column-gap: 20px;
    column-gap: 20px;
    row-gap: 32px;
    padding-bottom: 102px;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    @media (max-width:480px) {
      grid-template-columns: 1fr;
      padding-top: 40px;
      padding-bottom: 40px;
    }
  }
}
</style>

