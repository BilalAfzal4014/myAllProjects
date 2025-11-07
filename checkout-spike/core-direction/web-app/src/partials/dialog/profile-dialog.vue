<template>
  <div class="profile-body">
    <ul class="profile-menu-list" role="list">
      <router-link class="profile-menu-item" to="/calendar">
        <button class="profile-menu-link my-calendar-item" @click="$emit('onClickDropdownLink')">
          <span class="profile-menu-icon">
            <calendar-icon />
          </span>
          <span class="profile-menu-name">My Calendar</span>
        </button>
      </router-link>
      <router-link class="profile-menu-item" to="/wallet">
        <button class="profile-menu-link" @click="$emit('onClickDropdownLink')">
          <span class="profile-menu-icon">
            <img alt="" src="/assets/images/icons/profile-menu-01.svg">
          </span>
          <span class="profile-menu-name">My Wallet</span>
        </button>
      </router-link>
      <router-link class="profile-menu-item" to="/settings">
        <button class="profile-menu-link" @click="$emit('onClickDropdownLink')">
          <span class="profile-menu-icon">
            <img alt="" src="/assets/images/icons/profile-menu-02.svg">
          </span>
          <span class="profile-menu-name">My Profile</span>
        </button>
      </router-link>
      <li class="profile-menu-item" @click="logout">
        <button class="profile-menu-link">
          <span class="profile-menu-icon">
            <img alt="" src="/assets/images/icons/profile-menu-03.svg">
          </span>
          <span class="profile-menu-name">Log out</span>
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
import CalendarIcon from "@/svgs/calendar-icon";

export default {
  name: "ProfileDialog",
  components: {CalendarIcon},
  methods: {
    logout() {
      Promise.all([
        this.$store.dispatch("removeStoreTokenAction"),
        this.$store.dispatch("removeUserProfileInformationAction"),
        this.$store.commit("resetRefreshToken"),
      ]).then(() => {
        localStorage.removeItem("hasVisitedPaymentPage");
        this.$router.push("/listing");
      });
    }
  }
};
</script>

<style scoped>
.profile-item button,
.profile-item a {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 18.75px;
}

.profile-item button:hover,
.profile-item a:hover {
  opacity: .8;
}

.profile-menu-list {
  padding: 0;
}

.profile-menu-link {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  width: 100%;
  padding: 12px;
  margin-bottom: 7px;
}

.profile-menu-link svg {
  width: fit-content;
  width: -moz-fit-content;
  width: fit-content;
}

.profile-menu-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 400;
  line-height: 19px;
  letter-spacing: 0;
  text-align: left;
  margin-left: 15px;
}

.profile-menu-icon img,
.profile-menu-icon svg {
  max-width: 20px;
}

.my-calendar-item {
  padding-top: 9px;
  padding-bottom: 9px;
}
</style>
