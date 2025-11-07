<template>
  <div id="checkin-modal" class="custom-modal m-auto hidden overflow-y-auto" style="display: block;">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto">
              <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" @click="$parent.showLeaveModal = false">
                <path d="M30.7336 5.26636C23.7103 -1.75545 12.2885 -1.75545 5.2652 5.26636C-1.75507 12.2882 -1.75507 23.713 5.2652 30.7348C8.77685 34.245 13.3885 35.9992 18.0002 35.9992C22.6119 35.9992 27.222 34.2449 30.7336 30.7348C37.7555 23.7131 37.7555 12.2882 30.7336 5.26636ZM25.4271 23.3063C26.0139 23.8931 26.0139 24.8415 25.4271 25.4283C25.1345 25.7209 24.7503 25.868 24.3661 25.868C23.982 25.868 23.5977 25.7209 23.3051 25.4283L18.0001 20.1218L12.6966 25.4267C12.4025 25.7194 12.0183 25.8665 11.6356 25.8665C11.2515 25.8665 10.8672 25.7194 10.5746 25.4267C9.98785 24.84 9.98785 23.89 10.5746 23.3048L15.8781 17.9998L10.5731 12.6948C9.98637 12.1081 9.98637 11.1581 10.5731 10.5729C11.1584 9.98612 12.1083 9.98612 12.6951 10.5729L18.0001 15.8778L23.305 10.5729C23.8918 9.98612 24.8402 9.98612 25.427 10.5729C26.0137 11.1581 26.0137 12.1081 25.427 12.6948L20.122 17.9998L25.4271 23.3063Z" fill="#C4C4C4" />
              </svg>
            </div>
          </div>
          <div class="modal-body px-5">
            <div class="form-container mx-auto">
              <!-- <form action=""> -->
              <div class="booking-cancelation-message-box">
                <div class="booking-cancelation-message-icon-box">
                  <svg class="mx-auto" width="59" height="59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g filter="url(#filter0_d)">
                      <path d="M29.5 1C14.3175 1 2 13.3175 2 28.5C2 43.6825 14.3175 56 29.5 56C44.6825 56 57 43.6825 57 28.5C57 13.3175 44.6825 1 29.5 1Z" fill="#690FAD" />
                      <path d="M2.5 28.5C2.5 13.5937 14.5937 1.5 29.5 1.5C44.4063 1.5 56.5 13.5937 56.5 28.5C56.5 43.4063 44.4063 55.5 29.5 55.5C14.5937 55.5 2.5 43.4063 2.5 28.5Z" stroke="#690FAD" />
                    </g>
                    <path d="M29.9082 33.954V34.458C29.9082 35.522 29.3622 36.054 28.2702 36.054H28.0602C26.9402 36.054 26.3802 35.522 26.3802 34.458V33.954C26.3802 33.142 26.4642 32.554 26.6322 32.19C26.8282 31.798 26.9542 31.504 27.0102 31.308C27.0942 31.112 27.2622 30.888 27.5142 30.636C27.7942 30.356 27.9762 30.16 28.0602 30.048C28.1722 29.908 28.4102 29.698 28.7742 29.418C29.1662 29.138 29.4042 28.956 29.4882 28.872C29.7962 28.676 30.3422 28.312 31.1262 27.78C31.9382 27.22 32.5262 26.8 32.8902 26.52C33.2822 26.24 33.6462 25.848 33.9822 25.344C34.3182 24.84 34.4862 24.322 34.4862 23.79C34.4862 22.446 33.9962 21.368 33.0162 20.556C32.0642 19.744 30.7622 19.338 29.1102 19.338C27.4582 19.338 26.2122 19.646 25.3722 20.262C24.5322 20.85 23.8882 21.732 23.4402 22.908C22.7962 22.908 22.2502 22.74 21.8022 22.404C21.3822 22.068 21.1722 21.634 21.1722 21.102C21.1722 19.814 21.9142 18.68 23.3982 17.7C24.8822 16.72 26.9262 16.23 29.5302 16.23C32.1622 16.23 34.2062 16.93 35.6622 18.33C37.1182 19.73 37.8462 21.508 37.8462 23.664C37.8462 24.756 37.5662 25.75 37.0062 26.646C36.4742 27.542 35.8162 28.284 35.0322 28.872C34.2482 29.46 33.4642 30.02 32.6802 30.552C30.8322 31.784 29.9082 32.918 29.9082 33.954ZM25.3722 42.606C25.3722 41.794 25.6382 41.136 26.1702 40.632C26.7022 40.128 27.3462 39.876 28.1022 39.876C28.8862 39.876 29.5442 40.128 30.0762 40.632C30.6082 41.136 30.8742 41.794 30.8742 42.606C30.8742 43.418 30.6082 44.076 30.0762 44.58C29.5442 45.084 28.8862 45.336 28.1022 45.336C27.3462 45.336 26.7022 45.084 26.1702 44.58C25.6382 44.076 25.3722 43.418 25.3722 42.606Z" fill="white" />
                    <defs>
                      <filter id="filter0_d" x="0" y="0" width="59" height="59" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                        <feOffset dy="1" />
                        <feGaussianBlur stdDeviation="1" />
                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.45 0" />
                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
                      </filter>
                    </defs>
                  </svg>
                </div>
                <p class="booking-cancelation-message-title mx-auto text-center">
                  Are you sure you want to leave this challenge?
                </p>
                <p class="booking-cancelation-message-desc mx-auto text-center">
                  By removing yourself from a challenge, you will remove yourself from the associated leaderboard and forfeit all points towards the challenge and any link prizes or giveaway offers.
                </p>
              </div>

              <button class="booking-checkin-mode-btn-inspiring rounded-full capitalize" @click="leave">
                Leave Challenge
              </button>
              <button class="booking-checkin-mode-btn-cancel btn-modal-close rounded-full capitalize" @click="$parent.showLeaveModal = false">
                Cancel
              </button>
              <!-- </form> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: "LeaveChallengeModal",
    methods : {
        leave () {
            this.$emit("leaveChallenge",false);
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