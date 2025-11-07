<template>
  <nav v-click-outside="navbarHide" :class="`navbar ${isMenuShow ? '' : 'hidden'} lg:block`">
    <ul class="navbar-nav">
      <router-link class="nav-item" exact-active-class="active" title="community" to="/community"
                   @click.native="$emit('onToggleNavbar')"
      >
        <a class="nav-link" href="#">
          <community-icon />
        </a>
      </router-link>
      <router-link class="nav-item inside-navbar" exact-active-class="active" title="Location Listing" to="/listing"
                   @click.native="$emit('onToggleNavbar')"
      >
        <a class="nav-link py-2" href="#">
          <home-icon />
        </a>
      </router-link>
      <router-link class="nav-item inside-navbar" exact-active-class="active" title="Activity Listing"
                   to="/activity-listing" @click.native="$emit('onToggleNavbar')"
      >
        <a class="nav-link py-2" href="#">
          <yoga-icon />
        </a>
      </router-link>
      <router-link class="nav-item inside-navbar hidden" exact-active-class="active" title="Calendar" to="/calendar"
                   @click.native="$emit('onToggleNavbar')"
      >
        <a class="nav-link py-2" href="#">
          <calendar-icon />
        </a>
      </router-link>
      <router-link class="nav-item inside-navbar hidden" exact-active-class="active" title="Wallet" to="/wallet"
                   @click.native="$emit('onToggleNavbar')"
      >
        <a class="nav-link">
          <wallet-icon />
        </a>
      </router-link>
      <router-link class="nav-item" exact-active-class="active" title="Video-On-Demand" to="/on-demand"
                   @click.native="$emit('onToggleNavbar')"
      >
        <a class="nav-link">
          <VODIcon />
          <!-- <img alt="as" class="vod-image" src="../assets/images/navlink-04.png"> -->
        </a>
      </router-link>
    </ul>
  </nav>
</template>

<script>
import HomeIcon from "../svgs/home-icon";
import VODIcon from "../svgs/VODIcon";
import YogaIcon from "../svgs/yoga-icon";
import CalendarIcon from "../svgs/calendar-icon";
import WalletIcon from "../svgs/navs/wallet-icon";
import CommunityIcon from "@/svgs/profile/community-icon";

export default {
    name: "NavbarLeft",
    components: {CommunityIcon, WalletIcon, CalendarIcon, YogaIcon, VODIcon, HomeIcon},
    props: {
        isMenuShow: {
            type: Boolean,
            required: true
        }
    },
    methods: {
        navbarHide() {
            this.$emit("onToggleNavbar");
        }
    }
};
</script>

<style scoped>
#app-header .navbar .navbar-nav .nav-item:first-child {
  padding-top: 22px;
}

.vod-image {
  max-width: max-content;
}
</style>