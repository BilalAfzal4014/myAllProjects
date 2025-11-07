<template>
  <ShareNetwork network="facebook" :url="link" title="Coredirection">
    <li class="social-icon-item">
      <button class="social-icon-link">
        <svg
          width="27"
          height="26"
          viewBox="0 0 27 26"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M13.2962 0C5.95338 0 0 5.85074 0 13.067C0 19.6179 4.91073 25.0276 11.3084 25.9728V16.5297H8.01872V13.0953H11.3084V10.8096C11.3084 7.02567 13.1843 5.36508 16.3843 5.36508C17.9166 5.36508 18.7277 5.47724 19.1111 5.52733V8.52511H16.9283C15.5699 8.52511 15.0956 9.79152 15.0956 11.218V13.0953H19.0767L18.5371 16.5297H15.0956V26C21.5853 25.1354 26.5924 19.6821 26.5924 13.067C26.5924 5.85074 20.639 0 13.2962 0Z"
            fill="white"
          />
        </svg>
      </button>
    </li>
  </ShareNetwork>
</template>

<script>
export default {
    name: "Facebook",
    props: {
        link: {
            type:String,
            default: "",
        },
    },
};
</script>

<style scoped></style>
