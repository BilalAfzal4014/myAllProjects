<template>
  <Transition v-if="isModal && offerData" name="modal">
    <div id="modal" @click="close($event)">
      <div id="redeem-offer" class="custom-modal m-auto">
        <div ref="modal" class="modal-center">
          <div class="modal-outer-box">
            <div class="modal-inner-box">
              <div class="modal-header">
                <div class="btn-modal-close ml-auto" @click="$store.commit('setCloseModalRedeem')">
                  <CloseIcon />
                </div>
              </div>
              <div class="modal-body">
                <div class="offer-details-box">
                  <p class="title">
                    Offer Details
                  </p>
                  <p class="desc">
                    {{ offerData.offerDescription }}
                  </p>
                  <p class="title">
                    Terms & Conditions
                  </p>
                  <div class="terms-listing-wrap" v-html="offerData.termsAndConditions" />
                </div>
                <div class="redeem-details-box">
                  <img src="@/assets/images/core-premium.svg" alt="Core Premium" class="core-premium-icon">
                  <p class="title">
                    <strong>Core</strong>Premium
                  </p>
                  <p class="subtitle">
                    Exclusive Offer
                  </p>
                  <p v-if="offerType !== 'Buy 1 Get 1 Free'" class="discount">
                    {{ offerData.discountPercentage ? `${offerData.discountPercentage}% Off` : "100% Off" }}
                  </p>
                  <p v-else class="discount">
                    {{ offerData.offerType }}
                  </p>
                  <p class="discount-info">
                    {{ offerData.offerName }}
                  </p>
                  <p class="redemption">
                    No. of Redemption(s)
                  </p>
                
                  <p v-if="offerData.isUnlimited" class="redemption-info">
                    Unlimited Before {{ offerData.offerRenewal ?? '' }}
                  </p>
                  <p v-else-if="!offerData.isUnlimited && offerData.userRedemptions < offerData.totalRedemptions" class="redemption-info">
                    {{ offerData.totalRedemptions ?? '' }} Before {{ offerData.offerRenewal ?? '' }}
                  </p>
                  <p v-else-if="!offerData.isUnlimited && offerData.userRedemptions >= offerData.totalRedemptions" class="redemption-info">
                    Availed All Before {{ offerData.offerRenewal ?? '' }}
                  </p>
                  
                 
                  <button v-if="!offerData.isUnlimited && offerData.userRedemptions < offerData.totalRedemptions" class="btn-redeem" @click="getRedeemOffer">
                    Redeem Offer
                  </button>
                  <button v-else-if="!offerData.isUnlimited && offerData.userRedemptions >= offerData.totalRedemptions" disabled class="btn-redeem" @click="getRedeemOffer">
                    Redeem Offer
                  </button>
                  <button v-else class="btn-redeem" @click="getRedeemOffer">
                    Redeem Offer
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script>
import CloseIcon from "@/svgs/close-icon.vue";
import { getRedeemOffer } from "@/apiManager/offers";
import * as toastr from "toastr";

export default {
  name: "ModalRedeem",
  components: {
    CloseIcon,
  },
  computed:{
    offerData(){
      return this.$store.state.modalRedeemData;
    },
    isModal(){
      return this.$store.state.modalRedeem;
    },
	userRedemptions(){
		return this.$store.state.modalRedeemData?.userRedemptions;
	}
  },
  methods: {
    async getRedeemOffer(){
      if(!this.$store.state.token){
        this.$router.push("/corepremium");
      }
      else if(!this.$store.state.userProfile.isPremiumUser){

        this.$store.commit("setCorePremiumModal", true);
      }
      else if(this.offerData.isCode){
        this.$store.commit("setModalRedeemRequiredCode");
      }
      else {
        try{
          const res = await getRedeemOffer(this.offerData.offerId);
          if(res.statusCode === 200  && res.data){
			this.$store.commit("setModalRedeemCongrats",res.data);
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
        this.$store.commit("setCloseModalRedeem");
      }
    },

  }
};
</script>

<style lang="scss">
	#modal {
		position: fixed;
		z-index: 100;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;

		background-color: rgba(0, 0, 0, 0.4);
		display: flex;
		#redeem-offer {
			.modal-header {
				position: absolute;
				top: 16px;
				right: 16px;
				padding: 0;
			}
			.modal-outer-box {
				position: relative;
				max-width: 848px;
				border-radius: 16px;
				background: #FFFFFA;
				box-shadow: 0px 32px 64px 0px rgba(28, 4, 47, 0.14);
				pointer-events: auto;
			}
			.modal-body{
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
				justify-content: space-between;
				align-items: self-start;
				column-gap: 64px;
				row-gap: 64px;
				@media (min-width:768px) {
					padding: 72px 64px;
				}
				@media (max-width:767px) {
					display: flex;
					flex-direction: column-reverse;
					padding: 64px 32px;
				}
			}
			.offer-details-box{
				padding-left: 8px;
				padding-right: 8px;
				@media (max-width:767px) {
					width: 100%;
				}
				.title, .desc {
					color: #06070E;
					font-family: 'Montserrat', sans-serif;
					font-size: 16px;
					font-weight: 700;
					line-height: 20px;
					letter-spacing: 0em;
					text-align: left;
					margin-bottom: 20px;
					@media (max-width:767px) {
						font-size: 14px;
						line-height: 17px;
					}
				}
				.desc{
					margin-bottom: 24px;
					font-weight: 400;
					max-height: 180px;
					overflow-y: auto;
					&::-webkit-scrollbar {
  					width: 4px;
					}
					&::-webkit-scrollbar-track {
						background: #F1F1F1;
						border-radius: 11px;
					}
					&::-webkit-scrollbar-thumb {
						background: rgba(0, 0, 0, 0.3);
						border-radius: 11px;
					}
				}
				.terms-listing-wrap{
					max-height: 180px;
					overflow-y: auto;
					&::-webkit-scrollbar {
  					width: 4px;
					}
					&::-webkit-scrollbar-track {
						background: #F1F1F1;
						border-radius: 11px;
					}
					&::-webkit-scrollbar-thumb {
						background: rgba(0, 0, 0, 0.3);
						border-radius: 11px;
					}

				}
				ul{
					display: flex;
					flex-direction: column;
					row-gap: 10px;
					list-style: outside;
					li{
						color: #06070E;
						font-family: 'Montserrat', sans-serif;
						font-size: 16px;
						font-weight: 400;
						line-height: 20px;
						letter-spacing: 0em;
						margin-left: 24px;
						text-align: left;
						@media (max-width:767px) {
							font-size: 12px;
							line-height: 15px;
						}
					}
					
				}
			}
			.redeem-details-box{
				padding-left: 8px;
				padding-right: 8px;
				@media (max-width:767px) {
					width: 100%;
				}
				.core-premium-icon{
					width: 72px;
					height: 72px;
					margin-left: auto;
					margin-right: auto;
					@media (max-width:767px) {
						width: 54px;
						height: 54px;
					}
				}
				.title{
					color: #06070E;
					font-family: 'Montserrat', sans-serif;
					font-size: 24px;
					font-weight: 500;
					line-height: 29px;
					letter-spacing: 0em;
					text-align: center;
					margin-top: 8px;
					margin-bottom: 8px;
					text-transform: uppercase;
					@media (max-width:767px) {
						font-size: 16px;
						line-height: 20px;
					}
					strong{
						font-weight: 700;
					}
				}
				.subtitle{
					color: #06070E;
					font-family: 'Montserrat', sans-serif;
					font-size: 12px;
					font-weight: 500;
					line-height: 15px;
					letter-spacing: 0em;
					text-align: center;
					@media (max-width:767px) {
						font-size: 10px;
						line-height: 12px;
					}
				}
				.discount{
					color: #690FAD;
					font-family: 'Montserrat', sans-serif;
					font-size: 40px;
					font-weight: 700;
					line-height: 49px;
					letter-spacing: 0em;
					text-align: center;
					margin: 55px 0 20px;
					@media (max-width:767px) {
						margin: 16px 0 20px;
						font-size: 24px;
						line-height: 29px;
					}
				}
				.discount-info, .redemption, .redemption-info{
					font-family: 'Montserrat', sans-serif;
					font-size: 16px;
					line-height: 20px;
					letter-spacing: 0em;
					text-align: center;
					@media (max-width:767px) {
						font-size: 14px;
						line-height: 17px;
					}
				}
				.discount-info{
					color: #690FAD;
					font-weight: 500;
				}
				.redemption{
					color: #06070E;
					font-weight: 700;
					margin: 32px 0 16px;

				}
				.redemption-info{
					color: #06070E;
					font-weight: 500;

				}
				.redemption-info-total{
				  font-weight: 600;
				}
				.btn-redeem{
					display: block;
					color: #FFFFFA;
					font-family: 'Montserrat', sans-serif;
					font-size: 14px;
					font-weight: 600;
					line-height: 17px;
					letter-spacing: 0em;
					text-align: center;
					padding: 12px 24px;
					border-radius: 30px;
					-webkit-border-radius: 30px;
					-moz-border-radius: 30px;
					-ms-border-radius: 30px;
					-o-border-radius: 30px;
					border-radius: 24px;
					background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);
					margin: 55px auto 0;
					&:hover{
						background: linear-gradient(270.17deg, rgba(93, 13, 153, 0.65) 15.31%, #7812C6 99.74%);
					}
					&:active,
					&.active{
						opacity: 0.65;
						background: linear-gradient(272.52deg, #5D0D99 16.63%, rgba(120, 18, 198, 0.65) 49.42%, #5D0D99 83.46%);
					}
					&:disabled {
						background: $accent-white;
						color: $primary-dark;
						cursor: not-allowed;
					}
				}
			}
		}
	}
</style>
