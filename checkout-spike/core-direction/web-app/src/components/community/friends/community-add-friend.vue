<template>
  <button class="btn-add-friend" @click="$router.push('community/friend')">
    <svg fill="none" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd"
            d="M10.8906 1C10.8906 0.447715 10.4429 0 9.89062 0C9.33834 0 8.89062 0.447715 8.89062 1V8.88867L1 8.88867C0.447716 8.88867 0 9.33639 0 9.88867C0 10.441 0.447716 10.8887 1 10.8887L8.89062 10.8887V19C8.89062 19.5523 9.33834 20 9.89062 20C10.4429 20 10.8906 19.5523 10.8906 19V10.8887L19 10.8887C19.5523 10.8887 20 10.441 20 9.88867C20 9.33639 19.5523 8.88867 19 8.88867L10.8906 8.88867V1Z"
            fill="#06070E"
            fill-rule="evenodd"
      />
    </svg>
    <span class="add-friend-title">
      Search &amp; Add
    </span>
  </button>
</template>

<script>
export default {
    name: "CommunityAddFriend"
};
</script>

<style scoped>
#friends .btn-add-friend {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  background-color: #ffffff;
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
  -webkit-box-shadow: 0px 22px 40px 0px #0000001a;
  box-shadow: 0px 22px 40px 0px #0000001a;
}

#friends .add-friend-title {
  font-family: "Montserrat", sans-serif;
  font-size: 10px;
  font-weight: 500;
  line-height: 12px;
  letter-spacing: 0em;
  text-align: center;
  margin-top: 10px;
}
</style>