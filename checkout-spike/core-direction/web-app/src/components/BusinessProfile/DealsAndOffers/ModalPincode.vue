<template>
  <Transition name="pincode">
    <div v-if="isModal && offerData" id="pincode" class="custom-modal m-auto" @click="close($event)">
      <div ref="modal" class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="$store.commit('setCloseModalRedeemRequiredCode')">
                <CloseIcon />
              </div>
            </div>
            <div class="modal-body">
              <div class="offer-details-box">
                <div class="brand-logo-box">
                  <img src="@/assets/images/core-premium.svg" alt="Core Premium" class="core-premium-icon">
                  <p class="core-premium-title">
                    <strong>Core</strong>Premium
                  </p>
                </div>
                <ul class="offer-detail-list">
                  <li class="offer-detail-item">
                    <span>Provider Info:</span>
                    <strong> {{ offerData.offerProvider ?? ''}} </strong>
                  </li>
                  <li class="offer-detail-item">
                    <span>Discount Offer:</span>  
                    <strong> {{ offerData.offerName ?? '' }} </strong>
                  </li>
                  <li class="offer-detail-item">
                    <span>Users Email:</span>
                    <strong>{{ $store.state.userProfile.email ?? "" }}</strong>
                  </li>
                </ul>
              </div>
              <div class="pincode-box">
                <p class="desc">
                  Please, ask venue representative to enter the pin code
                </p>
                <div class="pincode-list">
                  <PincodeInput
                    v-model="code"
                    placeholder=""
                  />
                </div>
              </div>
              <button class="btn-redeem" @click="getRedeemOffer">
                Redeem Offer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script>
import PincodeInput from "vue-pincode-input";
import CloseIcon from "@/svgs/close-icon.vue";
import { getRedeemOffer } from "@/apiManager/offers";
import * as toastr from "toastr";
export default {
  name: "ModalPincode",
  components: {
    PincodeInput,
    CloseIcon
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
    offerProvider: {
      type: String,
      default: "",
    }
  },
  computed:{
    offerData(){
      return this.$store.state.modalRedeemData;
    },
    isModal(){
      return this.$store.state.modalRedeemRequiredCode;
    },
  },
  data() {
    return {
      code: "",
    };
  },
  methods: {
    async getRedeemOffer(){
      if(this.code.length !== 4){
        toastr.error("Please Enter 4 Digit Code");
      }
      else {
        try{
          const res = await getRedeemOffer(this.offerData.offerId,this.code);
          if(res.statusCode === 200  && res.data){
            this.$store.commit("setModalRedeemCongrats",res.data);
            this.code= "";
						
          }
          else if(res.statusCode === 400){
            toastr.error(res.error);
          }
        }
        catch(e){
          toastr.error(e.message);
        }
      }
    },
    close(e){
      if(e.target === this.$refs.modal){
        this.$store.commit("setCloseModalRedeemRequiredCode");
      }
    },
    
  }
};
</script>

<style lang="scss" scoped>
	#pincode {
    position: fixed;
		z-index: 100;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.6);
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
    .brand-logo-box{
      display: flex;
      align-items: center;
      column-gap: 4px;
      margin-bottom: 16px;
      .core-premium-icon{
        width: 24px;
        height: 24px;
      }
      .core-premium-title{
        color: #06070E;
        font-family: 'Montserrat', sans-serif;
        font-size: 12px;
        font-weight: 500;
        line-height: 15px;
        letter-spacing: 0em;
        text-align: left;
        text-transform: uppercase;
        strong{
          font-weight: 700;
        }
      }
    }
    .offer-detail-list{
      display: flex;
      flex-direction: column;
      row-gap: 8px;
      margin-bottom: 96px;
      .offer-detail-item{
        display: flex;
        align-items: center;
        column-gap: 10px;
        color: #06070E;
        font-family: 'Montserrat', sans-serif;
        font-size: 12px;
        font-weight: 500;
        line-height: 15px;
        letter-spacing: 0em;
        text-align: left;
        strong{
          font-weight: 700;
        }
      }
    }
    .pincode-box{
      .desc{
        color: #06070E;
        font-family: 'Montserrat', sans-serif;
        font-size: 16px;
        font-weight: 500;
        line-height: 20px;
        letter-spacing: 0em;
        text-align: left;
        margin-bottom: 16px;
        @media (max-width:767px) {
          font-size: 14px;
          line-height: 17px;
        }
      }
    }
    .pincode-list{
      display: flex;
      align-items: center;
      column-gap: 8px;
      .pincode-item {
        position: relative;
        overflow: hidden;
        border-radius: 3px;
        &:active::before,
        &.active::before {
          position: absolute;
          content: "";
          box-shadow: 0 0 0 38px #CAA8F5 inset, 0 0 0 60px #fff inset;
          width: 100px;
          height: 100px;
          border-radius: 50%;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
        }
      }

    }
    .btn-redeem{
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
      background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);
      margin: 98px 0 0;
      @media (max-width:767px) {
        margin: 32px 0 0;
      }
      &:hover{
        background: linear-gradient(270.17deg, rgba(93, 13, 153, 0.65) 15.31%, #7812C6 99.74%);
      }
      &:active,
      &.active{
        opacity: 0.65;
        background: linear-gradient(272.52deg, #5D0D99 16.63%, rgba(120, 18, 198, 0.65) 49.42%, #5D0D99 83.46%);
      }
    }
  }
</style>