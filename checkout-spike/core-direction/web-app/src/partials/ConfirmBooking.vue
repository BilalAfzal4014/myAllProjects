<template>
  <div class="wrapper">
    <!-- booking-confirmed-modal -->
    <div id="booking-confirmed-modal" class="custom-modal m-auto ">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="closeConfirmModal">
                <svg
                  width="36"
                  height="36"
                  viewBox="0 0 36 36"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M30.7336 5.26685C23.7103 -1.75497 12.2885 -1.75497 5.2652 5.26685C-1.75507 12.2887 -1.75507 23.7135 5.2652 30.7353C8.77685 34.2455 13.3885 35.9997 18.0002 35.9997C22.6119 35.9997 27.222 34.2454 30.7336 30.7353C37.7555 23.7135 37.7555 12.2887 30.7336 5.26685ZM25.4271 23.3068C26.0139 23.8936 26.0139 24.842 25.4271 25.4288C25.1345 25.7214 24.7503 25.8685 24.3661 25.8685C23.982 25.8685 23.5977 25.7214 23.3051 25.4288L18.0001 20.1223L12.6966 25.4272C12.4025 25.7199 12.0183 25.867 11.6356 25.867C11.2515 25.867 10.8672 25.7199 10.5746 25.4272C9.98785 24.8405 9.98785 23.8905 10.5746 23.3053L15.8781 18.0003L10.5731 12.6953C9.98637 12.1086 9.98637 11.1586 10.5731 10.5734C11.1584 9.98661 12.1083 9.98661 12.6951 10.5734L18.0001 15.8783L23.305 10.5734C23.8918 9.98661 24.8402 9.98661 25.427 10.5734C26.0137 11.1586 26.0137 12.1086 25.427 12.6953L20.122 18.0003L25.4271 23.3068Z"
                    fill="black"
                  />
                </svg>
              </div>
            </div>
            <div class="modal-body px-5">
              <div class="form-container mx-auto">
                <h3 class="modal-title text-center">
                  Booking Confirmation
                </h3>
                <div class="booking-confirmed-success-icon">
                  <svg
                    class="mx-auto"
                    width="84"
                    height="84"
                    viewBox="0 0 84 84"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <g filter="url(#filter0_d)">
                      <path
                        d="M42 1C19.9164 1 2 18.9164 2 41C2 63.0836 19.9164 81 42 81C64.0836 81 82 63.0836 82 41C82 18.9164 64.0836 1 42 1Z"
                        fill="#690fad"
                      />
                      <path
                        d="M2.5 41C2.5 19.1925 20.1925 1.5 42 1.5C63.8075 1.5 81.5 19.1925 81.5 41C81.5 62.8075 63.8075 80.5 42 80.5C20.1925 80.5 2.5 62.8075 2.5 41Z"
                        stroke="#690fad"
                      />
                    </g>
                    <path
                      d="M31.5101 55.7983L22.1082 46.0099C20.3907 44.2212 20.3969 41.3339 22.1227 39.5537C23.8504 37.7704 26.6507 37.7715 28.3775 39.5559L35.3787 46.7908L55.6308 25.8627C57.3575 24.0783 60.1568 24.0783 61.8825 25.8627C63.6061 27.6439 63.6092 30.5312 61.8887 32.3167L39.2535 55.8048C37.1168 58.0213 33.6426 58.0192 31.5101 55.7983Z"
                      fill="white"
                    />
                    <defs>
                      <filter
                        id="filter0_d"
                        x="0"
                        y="0"
                        width="84"
                        height="84"
                        filterUnits="userSpaceOnUse"
                        color-interpolation-filters="sRGB"
                      >
                        <feFlood
                          flood-opacity="0"
                          result="BackgroundImageFix"
                        />
                        <feColorMatrix
                          in="SourceAlpha"
                          type="matrix"
                          values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                          result="hardAlpha"
                        />
                        <feOffset dy="1" />
                        <feGaussianBlur stdDeviation="1" />
                        <feColorMatrix
                          type="matrix"
                          values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.45 0"
                        />
                        <feBlend
                          mode="normal"
                          in2="BackgroundImageFix"
                          result="effect1_dropShadow"
                        />
                        <feBlend
                          mode="normal"
                          in="SourceGraphic"
                          in2="effect1_dropShadow"
                          result="shape"
                        />
                      </filter>
                    </defs>
                  </svg>
                </div>
                <p class="modal-subtitle text-center">
                  Coredirection Offers
                </p>
                <p class="booking-confirmation-modal-desc text-center">
                  Book the selected activity for yourself or book multiple slots
                  for your friends and family to join the session with you
                </p>
                <button
                  class="btn-booking-confirmation rounded-full block mx-auto"
                >
                  Share your activity
                </button>
                <button
                  class="btn-modal-close btn-cancel rounded-full block mx-auto"
                >
                  Browse More
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
export default {
    name: "ConfirmBooking",
    methods: {
        closeConfirmModal() {
            this.$emit("clicked", "close");
        },
    },
};
</script>

<style scoped></style>
