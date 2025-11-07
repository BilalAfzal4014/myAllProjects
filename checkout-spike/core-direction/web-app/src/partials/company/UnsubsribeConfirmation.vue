<template>
  <div class="wrapper">
    <div id="checkin-modal" class="custom-modal m-auto hidden overflow-y-auto" style="display: block;">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="closeModal">
                <close-icon />
              </div>
            </div>
            <div class="modal-body px-5">
              <div class="form-container mx-auto">
                <div class="booking-cancelation-message-box">
                  <div class="booking-cancelation-message-icon-box">
                    <checkin-modal-information-icon />
                  </div>
                  <p class="booking-cancelation-message-title mx-auto text-center">
                    Are you sure?
                  </p>
                  <p class="booking-cancelation-message-desc mx-auto text-center">
                    Are you sure you want to Unsubscribe?
                  </p>
                </div>
                <button class="booking-cancelation-success btn-modal-close rounded-full capitalize" @click="closeModal">
                  No, I'll keep inspiring
                </button>
                <button class="booking-checkin-mode-btn-cancel btn-modal-close rounded-full capitalize"
                        @click="unSubscribeUser"
                >
                  Leave Corporate
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {unSubscribeCorporateUser, userAttributesSetting} from "@/apiManager/user";
import CloseIcon from "@/svgs/close-icon";
import CheckinModalInformationIcon from "@/svgs/checkin-modal/checkin-modal-information-icon";

export default {
    name: "UnsubsribeConfirmation",
    components: {CheckinModalInformationIcon, CloseIcon},
    props: {
      companyId: {
        type: Number,
        default: null
      }
    },
    data() {
        return {};
    },

    methods: {
      async updateUser(){
        try{  
          const res = await userAttributesSetting();
          if(res.statusCode === 200){
            const userData = JSON.parse(localStorage.getItem("userProfile"));
            localStorage.setItem("userProfile",JSON.stringify({...userData , isPremiumUser : res.data.isPremiumUser}));
            this.$store.commit("setIsCorePremium",res.data.isPremiumUser);
          }
        }
        catch(e){
          return e?.message;
        }
   
      },
        closeModal() {
            this.$emit("close");
            const body = document.querySelector("body");
            body.classList.remove("overflow-y-hidden");
            this.isCancelButtonHide = false;
        },
        unSubscribeUser() {
            unSubscribeCorporateUser(this.companyId).then(response => {
              
                const body = document.querySelector("body");
                body.classList.remove("overflow-y-hidden");
                this.updateUser();
                this.$router.push("/listing");
                
            }).catch(error => {
                toastr.error(error);
            });
        }
    },

};
</script>

<style scoped>
.booking-cancelation-message-box {
  background-color: #ffffff;
  margin-top: 67px;
  margin-bottom: 67px !important;
  padding: 49px 15px 68px;
  border-radius: 11px 11px 21px 21px;
  -webkit-box-shadow: 0px 22px 40px rgb(0 0 0 / 10%);
  box-shadow: 0px 22px 40px rgb(0 0 0 / 10%);
}

#checkin-modal .booking-cancelation-success {
  color: #ffffff;
  background: #690fad;
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 16.41px;
  text-align: center;
  width: 100%;
  max-width: 372px;
  height: 48px;
  display: block;
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 20px;
}

#checkin-modal .booking-checkin-mode-btn-cancel {
  background-color: #757575 !important;
}
</style>
