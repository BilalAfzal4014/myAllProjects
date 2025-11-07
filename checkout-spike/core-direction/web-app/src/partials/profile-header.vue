<template>
  <div class="profile-info-box">
    <p class="user-name">
      {{ username }}
    </p>
    <p class="user-email">
      {{ email }}
    </p>
  </div>
</template>

<script>
export default {
    props: {
        username: {
            type: String,
            required: false,
            default: "",
        },
        email: {
            type: String,
            required: false,
            default: "",
        },
        profileLink: {
            type: String,
            required: false,
            default: "",
        },
    },
    name: "ProfileHeader"
};
</script>

<style scoped>
.btn-user-info {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 14.63px;
  color: #690FAD;
}

.user-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 2px;
}

.user-email {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-style: normal;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
}
</style>