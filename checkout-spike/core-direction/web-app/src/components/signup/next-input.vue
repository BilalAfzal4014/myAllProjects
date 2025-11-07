<template>
  <div :class="`${isShowTextOnButton ?'password-screen-btn-box':'btn-box'} flex items-center`">
    <button v-if="isShowTextOnButton" class="btn-back flex items-center" @click="$emit('previousStep')">
      <svg fill="none" height="24" viewBox="0 0 14 24" width="14" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0.93934 10.9393C0.353553 11.5251 0.353553 12.4749 0.93934 13.0607L10.4853 22.6066C11.0711 23.1924 12.0208 23.1924 12.6066 22.6066C13.1924 22.0208 13.1924 21.0711 12.6066 20.4853L4.12132 12L12.6066 3.51472C13.1924 2.92893 13.1924 1.97919 12.6066 1.3934C12.0208 0.807611 11.0711 0.807611 10.4853 1.3934L0.93934 10.9393ZM3 10.5H2L2 13.5H3V10.5Z"
          fill="black"
        />
      </svg>
      Go Back
    </button>
    <button :class="`${isShowTextOnButton ? 'btn-register':'btn-submit'}`" @click="$emit('nextStep')">
      <svg v-if="!isShowTextOnButton" fill="none" height="16" viewBox="0 0 21 16" width="21"
           xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M1 7C0.447715 7 4.82823e-08 7.44772 0 8C-4.82823e-08 8.55228 0.447715 9 1 9L1 7ZM20.7071 8.70711C21.0976 8.31658 21.0976 7.68342 20.7071 7.29289L14.3431 0.928933C13.9526 0.538409 13.3195 0.538409 12.9289 0.928933C12.5384 1.31946 12.5384 1.95262 12.9289 2.34315L18.5858 8L12.9289 13.6569C12.5384 14.0474 12.5384 14.6805 12.9289 15.0711C13.3195 15.4616 13.9526 15.4616 14.3431 15.0711L20.7071 8.70711ZM1 9L20 9L20 7L1 7L1 9Z"
          fill="#F2F5EA"
        />
      </svg>
      <span v-if="isShowTextOnButton">Register my info</span>
    </button>
    <span v-if="isHidePressEnter" class="note">Press Enter</span>
  </div>
</template>

<script>
export default {
    name: "NextInput",
    props: {
        isHidePressEnter: {
            type: Boolean,
            required: false,
            default: true
        },
        isShowTextOnButton: {
            type: Boolean,
            required: false,
            default: false
        }
    }
};
</script>

<style scoped>
.password-screen-btn-box {
  -webkit-column-gap: 60px;
  column-gap: 60px;
  row-gap: 30px;
}
</style>