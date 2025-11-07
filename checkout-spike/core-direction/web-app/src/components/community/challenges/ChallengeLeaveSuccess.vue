<template>
  <div id="checkin-modal" class="custom-modal m-auto hidden overflow-y-auto" style="display: block;">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="$parent.showLeaveSuccess = false">
              <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                    <path d="M22.2879 38.6737L15.8241 31.9441C14.6434 30.7144 14.6477 28.7293 15.8341 27.5055C17.0219 26.2795 18.9471 26.2802 20.1343 27.507L24.9476 32.481L38.8709 18.0929C40.0581 16.8662 41.9825 16.8662 43.169 18.0929C44.354 19.3175 44.3561 21.3025 43.1732 22.53L27.6116 38.6781C26.1426 40.202 23.7541 40.2005 22.2879 38.6737Z" fill="white" />
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
                <p class="booking-cancelation-success-title mx-auto text-center">
                  You’ve left challenge successfully
                </p>
                <p class="booking-cancelation-success-desc mx-auto text-center">
                  You have been removed from this challenge and will no longer receive points towards this leaderboard.
                </p>
              </div>

              <button class="booking-cancelation-success btn-modal-close rounded-full capitalize" @click="$router.push('/community')">
                Go to Dashboard
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
    name: "ChallengeLeaveSuccess"
};
</script>

<style scoped>

</style>