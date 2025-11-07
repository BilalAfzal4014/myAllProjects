<template>
  <Transition name="congrats">
    <div v-if="isModal && offerData" id="congrats" class="custom-modal m-auto" @click="close($event)">
      <div ref="modal" class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="$store.commit('setCloseModalRedeemCongrats')">
                <CloseIcon />
              </div>
            </div>
            <div class="modal-body">
              <div class="pincode-success-box">
                <img src="@/assets/images/pincode-success.svg" class="pincode-success-icon" width="36" height="36" alt="Pincode Success">
                <p class="pincode-success-title">
                  Congratulations
                </p>
                <p class="unique-id">
                  <span>Unique ID:</span>
                  <strong>{{congratsData?.unique_code ?? ''}}</strong>
                </p>
                 <p class="unique-id unique-promocode" v-if="offerData?.offerType === 'Online'">
                  <span>Discount Code:</span>
                  <strong>{{congratsData?.promo_code ?? ''}}</strong>
                </p>
                <a :href="congratsData?.online_deal_url ?? '/'" target="_blank" v-if="offerData?.offerType === 'Online'" class="unique-partner-link">Click here to go to Partner Website</a>
                <p class="pincode-success-desc">
                  You’ve successfully redemed a discount from “{{ offerData?.offerName ?? '' }}”.
                </p>
              </div>
              <button class="btn-close" @click="$store.commit('setCloseModalRedeemCongrats')">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script>
import CloseIcon from "@/svgs/close-icon.vue";
export default {  
  name: "ModalCongrats",
  components: {
    CloseIcon,
  },
  props: {
    show: {
      type: Boolean,
      default: false,
    },
    offerName: {
      type: String,
      default: "",
    },
    offerId: {
      type: Number,
      default: null,
    },
  },
  computed:{
    offerData(){
      return this.$store.state.modalRedeemData;
    },
    isModal(){
      return this.$store.state.modalRedeemCongrats;
    },
    congratsData(){
      return this.$store.state.modalRedeemCongratsData;
    }
  },
  methods: {
    close(e){
      if(e.target === this.$refs.modal){
        this.$store.commit("setCloseModalRedeemCongrats");
      }
    },
  }
};
</script>

<style lang="scss" scoped>
	#congrats {
		position: fixed;
		z-index: 9998;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.6);
		transition: opacity 0.3s ease;
    .modal-header {
        position: absolute;
        top: 16px;
        right: 16px;
        padding: 0;
    }
    .modal-outer-box {
      position: relative;
      max-width: 568px;
      border-radius: 16px;
      background: #FFFFFA;
      box-shadow: 0px 32px 64px 0px rgba(28, 4, 47, 0.14);
    }
    .modal-body{
      @media (min-width:768px) {
        padding: 72px 64px;
      }
      @media (max-width:767px) {
        padding: 64px 32px;
      }
    }
    .pincode-success-box{
      width: 100%;
      max-width: 313px;
      margin-left: auto;
      margin-right: auto;
      .pincode-success-icon{
        width: 36px;
        height: 36px;
        margin-left: auto;
        margin-right: auto;
      }
      .pincode-success-title{
        color: #06070E;
        font-family: 'Montserrat', sans-serif;
        font-size: 20px;
        font-weight: 600;
        line-height: 24px;
        letter-spacing: 0em;
        text-align: center;
        margin: 28px 0 15px;
      }
      .pincode-success-desc{
        color: #06070E;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 400;
        line-height: 17px;
        letter-spacing: 0em;
        text-align: center;
        margin: 15px 0 63px;
      }
      .unique-id{
        background-color: #F2F5EA;
        border-radius: 30px;
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        -ms-border-radius: 30px;
        -o-border-radius: 30px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-column-gap: 10px;
        column-gap: 10px;
        padding: 16px;
        color: #06070E;
        font-family: 'Montserrat', sans-serif;
        font-size: 12px;
        font-weight: 500;
        line-height: 15px;
        letter-spacing: 0em;
        text-align: center;
        justify-content: center;
        strong {
          font-weight: 700;
        }
      }
      .unique-promocode{
        margin: 8px auto 0 auto;
        width: 90%;
        justify-items: center;
      }
      .unique-partner-link{
        font-family: 'Montserrat', sans-serif;
        display: block;
        color: #690FAD;
        font-weight: 300;
        text-align: center;
        text-decoration: underline;
        width: 100%;
        margin-top: 16px;
      }
    }
    .btn-close{
      display: block;
      font-family: 'Montserrat', sans-serif;
      font-size: 16px;
      font-weight: 600;
      line-height: 20px;
      letter-spacing: 0em;
      text-align: center;
      padding: 12px 24px;
      border-radius: 30px;
      -webkit-border-radius: 30px;
      -moz-border-radius: 30px;
      -ms-border-radius: 30px;
      -o-border-radius: 30px;
      border-radius: 30px;
      color: #FFFFFA;
      background: #690FAD;
      margin-left: auto;
      margin-right: auto;
      min-width: 194px;
      @media (max-width:767px) {
        font-size: 14px;
        line-height: 17px;
      }
      &:hover{
        background: #8C14E6;
      }
      &:active,
      &.active{
        opacity: 0.65;
        color: #690FAD;
        background: #CAA8F5;
      }
    }
  }
</style>