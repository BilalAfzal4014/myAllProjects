<template>
  <div id="checkin-modal" class="custom-modal m-auto hidden overflow-y-auto" style="display: block;">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="$parent.isShowUserConfirmation = false">
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
                  Are you sure you want to unfollow {{ username }}?
                </p>
              </div>

              <button class="booking-checkin-mode-btn-inspiring rounded-full capitalize" @click="unfollowUser">
                Unfollow
              </button>
              <button class="booking-checkin-mode-btn-cancel btn-modal-close rounded-full capitalize"
                      @click="$parent.isShowUserConfirmation = false"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CloseIcon from "@/svgs/close-icon";
import CheckinModalInformationIcon from "@/svgs/checkin-modal/checkin-modal-information-icon";

export default {
    name: "UnfollowUserConfirmation",
    components: {CheckinModalInformationIcon, CloseIcon},
    props: {
        username: {
            type: String,
            required: true
        }
    },
    methods: {
        unfollowUser() {
            this.$emit("unfollowUser");
        }

    }
};
</script>

<style>
#checkin-modal .modal-outer-box {
  max-width: calc(460px + 2rem);
  background: #E0E0E0;
}

@media screen and (max-width: 389px) {
  #checkin-modal .modal-body.px-5 {
    padding-left: 15px;
    padding-right: 15px;
  }
}

#checkin-modal .form-container {
  width: 100%;
  max-width: 360px;
}

#checkin-modal .booking-checkin-header {
  background-color: #FFFFFF;
  margin-top: 67px;
  border-radius: 11px 11px 21px 21px;
  position: relative;
  margin-bottom: 5px;
}

#checkin-modal .booking-checkin-header .logo-box {
  position: absolute;
  left: 0;
  right: 0;
  top: -67px;
}

#checkin-modal .booking-checkin-header .logo-box .logo-outer-box {
  width: 150px;
  height: 150px;
  background: #FFFFFF;
  border-radius: 11px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  margin-left: auto;
  margin-right: auto;
  padding: 10px;
  -webkit-box-shadow: 0px 5px 8px rgba(0, 0, 0, 0.45);
  box-shadow: 0px 5px 8px rgba(0, 0, 0, 0.45);
}

#checkin-modal .booking-checkin-header .checkin-detail {
  padding-top: 114px;
}

#checkin-modal .booking-checkin-header .checkin-detail .booking-activity-name {
  color: #000000;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 500;
  line-height: 21.94px;
  margin-bottom: 5px;
}

#checkin-modal .booking-checkin-header .checkin-detail .booking-activity-place {
  color: #000000;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 17.07px;
  margin-bottom: 0px;
}

#checkin-modal .booking-checkin-header .checkin-detail .booking-activity-type {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 17px;
  margin-top: 18px;
  margin-bottom: 1px;
}

#checkin-modal .booking-checkin-header .checkin-detail .booking-activity-instructor {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
}

#checkin-modal .booking-checkin-header .booking-activity-instruction-box {
  border-top: 1px solid #C4C4C4;
  padding: 28px;
  margin-top: 14px;
}

#checkin-modal .booking-checkin-header .booking-activity-instruction-box .booking-activity-instruction {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  max-width: 286px;
  margin-left: auto;
  margin-right: auto;
}

#checkin-modal .booking-cancelation-message-box {
  width: 100%;
  height: 368px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  background-color: #FFFFFF;
  margin-bottom: 30px;
  padding: 49px 15px 68px;
  border-radius: 21px 21px 11px 11px;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
}

#checkin-modal .booking-cancelation-message-box .booking-cancelation-message-title,
#checkin-modal .booking-cancelation-message-box .booking-cancelation-success-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  margin-top: 30px;
  margin-bottom: 15px;
}

#checkin-modal .booking-cancelation-message-box .booking-cancelation-message-desc,
#checkin-modal .booking-cancelation-message-box .booking-cancelation-success-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
}

#checkin-modal .booking-cancelation-message-box .booking-cancelation-message-title,
#checkin-modal .booking-cancelation-message-box .booking-cancelation-message-desc {
  max-width: 300px;
}

#checkin-modal .booking-cancelation-message-box .booking-cancelation-success-title,
#checkin-modal .booking-cancelation-message-box .booking-cancelation-success-desc {
  max-width: 304px;
}

#checkin-modal .booking-checkin-body {
  background-color: #FFFFFF;
  border-radius: 21px;
  margin-bottom: 21px;
}

#checkin-modal .booking-checkin-body .booking-checkin-body-inner-box .activity-detail-left-side-box {
  padding: 19px 20px;
  width: 100%;
  max-width: 325px;
  border-right: 10px dotted #C4C4C4;
}

#checkin-modal .booking-checkin-body .booking-checkin-body-inner-box .activity-detail-right-side-box {
  padding: 19px 0px;
  -webkit-transform: rotate(270deg);
  transform: rotate(270deg);
}

#checkin-modal .booking-checkin-body .activity-detail-address-box {
  max-width: 187px;
}

#checkin-modal .booking-checkin-body .activity-detail-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  margin-bottom: 14px;
}

#checkin-modal .booking-checkin-body .activity-detail-title {
  font-family: 'Mohave', sans-serif;
  font-size: 10px;
  font-weight: 400;
  line-height: 14px;
  text-align: left;
  margin-bottom: 4px;
  color: #000000A6;
}

#checkin-modal .booking-checkin-body .activity-detail-info {
  font-family: 'Mohave', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 17px;
  text-align: left;
  margin-bottom: 0px;
  color: #000000;
}

#checkin-modal .booking-checkin-body .activity-detail-name {
  font-family: 'Mohave', sans-serif;
  font-size: 24px;
  font-weight: 700;
  line-height: 33px;
  width: 144px;
}

#checkin-modal .booking-checkin-body .activity-detail-address {
  font-family: 'Mohave', sans-serif;
  font-size: 10px;
  font-weight: 400;
  line-height: 14px;
}

#checkin-modal .booking-checkin-mode-btn-proceed {
  color: #FFFFFF;
  background: #690FAD;
  font-family: 'Montserrat', sans-serif;
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
  margin-bottom: 10px;
}

@media screen and (max-width: 389px) {
  #checkin-modal .booking-checkin-mode-btn-proceed {
    height: 43px;
  }
}

#checkin-modal .booking-checked-in-mode-btn-proceed {
  color: #FFFFFF;
  background: #690FAD;
  font-family: 'Montserrat', sans-serif;
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
  margin-bottom: 110px;
}

@media screen and (max-width: 389px) {
  #checkin-modal .booking-checked-in-mode-btn-proceed {
    height: 43px;
  }
}

#checkin-modal .booking-cancelation-success {
  color: #FFFFFF;
  background: #690FAD;
  font-family: 'Montserrat', sans-serif;
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
  margin-bottom: 60px;
}

@media screen and (max-width: 389px) {
  #checkin-modal .booking-cancelation-success {
    height: 43px;
  }
}

#checkin-modal .booking-checkin-mode-btn-inspiring {
  width: 100%;
  max-width: 372px;
  height: 48px;
  display: block;
  margin-left: auto;
  margin-right: auto;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 16px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
  background-color: #690FAD;
  margin-bottom: 10px;
}

@media screen and (max-width: 389px) {
  #checkin-modal .booking-checkin-mode-btn-inspiring {
    height: 43px;
  }
}

#checkin-modal .booking-checkin-mode-btn-cancel {
  color: #FFFFFF;
  background: #757575;
  font-family: 'Montserrat', sans-serif;
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
  margin-bottom: 60px;
}

@media screen and (max-width: 389px) {
  #checkin-modal .booking-checkin-mode-btn-cancel {
    height: 43px;
  }
}
</style>