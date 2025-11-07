<template>
  <header id="header">
    <div class="container-fluid px-5 flex items-center">
      <router-link class="brand-link mr-auto" to="/">
        <img alt="Core Direction Logo" class="brand-logo" height="44" src="/assets/images/logo-white.svg" width="200">
      </router-link>

      <div class="navbar flex items-center">
        <div v-click-outside-parent-element="hideDropdown"
             :class="`btn-dropdown flex items-center dropdown-button cursor-pointer ${isDropdownShow ? 'active':''}`"
             @click.stop.prevent="showDropdown"
        >
          <div :class="`content-box flex items-center nav-link dropdown-button ${isDropdownShow ? 'active':''}`">
            Core Direction
            <dropdown-white-arrow />
          </div>
          <span class="icon-box">
            <hamburger-icon />
          </span>
          <div
            v-if="isDropdownShow"
            class="dropdown-menu"
          >
            <ul class="dropdown-menu-list" @click.stop>
              <li v-for="menu in dropdownMenu" :key="menu.code" class="dropdown-menu-item">
                <router-link v-if="!menu.isExternal" :to="menu.link" class="dropdown-menu-link">
                  {{ menu.name }}
                </router-link>
                <a v-else :href="menu.link" class="dropdown-menu-link" target="_blank">
                  {{ menu.name }}
                </a>
              </li>
            </ul>
          </div>
        </div>

        <nav class="navbar-nav">
          <ul class="nav-list flex items-center">
            <li class="nav-item">
              <router-link class="nav-link flex items-center" exact-active-class="active" to="/listing">
                <location-listing-icon />
                Location Listing
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link flex items-center" exact-active-class="active" to="/activity-listing">
                <activity-listing-icon />
                Activity Listing
              </router-link>
            </li>
          </ul>
        </nav>

        <ul class="login-list flex items-center">
          <li class="login-item">
            <button class="py-2 btn-signin" @click="showSignInModal">
              Sign In
            </button>
          </li>
          <li class="login-item">
            <router-link class="btn-signup rounded-full" to="/signup">
              Sign Up
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </header>
</template>

<script>
import Emitter from "tiny-emitter/instance";
import DropdownWhiteArrow from "@/svgs/arrows/dropdown-white-arrow";
import HamburgerIcon from "@/svgs/header/hamburger-icon";
import LocationListingIcon from "@/svgs/menu/location-listing-icon";
import ActivityListingIcon from "@/svgs/menu/activity-listing-icon";
import {NON_AUTH_DROPDOWN_MENU} from "@/common/constants/constants";


export default {
  components: {
    ActivityListingIcon,
    LocationListingIcon,
    HamburgerIcon,
    DropdownWhiteArrow
  },
  name: "HeaderComponent",

  data() {
    return {
      isNavbarShow: true,
      isDropdownShow: false,
      dropdownMenu: NON_AUTH_DROPDOWN_MENU
    };
  },
  mounted() {
    this.$emit("showed", "showAllModal");
  },
  methods: {
    showSignInModal: function () {
      const body = document.querySelector("body");
      body.classList.add("overflow-y-hidden");
      Emitter.emit("sign_in_modal", "show sign in modal");
    },
    hideNavbar() {
      document.querySelector(".navbar").style.display = "none";
      this.isNavbarShow = true;
    },
    showNavbar() {
      document.querySelector(".navbar").style.display = "block";
      this.isNavbarShow = false;
    },
    showDropdown() {
      this.isDropdownShow = !this.isDropdownShow;
    },

    hideDropdown() {
      this.isDropdownShow = false;
    },


  },
};
</script>

<style scoped>

#header {
  min-height: 70px;
  position: relative;
  z-index: 3;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  background-color: #690FAD;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
}

#header .navbar-brand {
  float: left;
}

#header .navbar-brand .navbar-brand-logo {
  max-width: 200px;
}

@media screen and (max-width: 991px) {
  #header .navbar-brand .navbar-brand-logo {
    max-width: 150px;
  }
}

@media (max-width: 991px) {
  #header .navbar {
    -webkit-box-orient: horizontal;
    -webkit-box-direction: reverse;
    -ms-flex-direction: row-reverse;
    flex-direction: row-reverse;
  }
}

@media (min-width: 992px) {
  #header .nav-list {
    -webkit-column-gap: 33px;
    column-gap: 33px;
    margin-left: 33px;
    margin-right: 75px;
  }
}

@media (min-width: 992px) and (max-width: 1006px) {
  #header .nav-list {
    margin-right: 33px;
  }
}

#header .btn-dropdown {
  position: relative;
}

@media (max-width: 991px) {
  #header .btn-dropdown {
    margin-left: 15px;
  }
}

@media (max-width: 991px) {
  #header .btn-dropdown .content-box {
    display: none;
  }
}

#header .btn-dropdown .content-box svg {
  margin-left: 5px;
}

@media (min-width: 992px) {
  #header .btn-dropdown .icon-box {
    display: none;
  }
}

#header .dropdown-menu {
  display: none;
  position: absolute;
  background-color: #690FAD;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 0px 0px 11px 11px;
  z-index: 999;
}

@media (min-width: 992px) {
  #header .dropdown-menu {
    left: 0;
    padding: 10px 21px 16px;
    width: 300px;
    top: 70px;
  }
}

@media (max-width: 991px) {
  #header .dropdown-menu {
    right: 0;
    padding: 0px 16px 16px;
    width: 207px;
    top: 45px;
  }
}

#header .dropdown-menu-link {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  margin-bottom: 10px;
}

#header .dropdown-menu-link {
  width: 100%;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  color: #FFFFFF;
  padding-top: 10px;
  padding-bottom: 10px;
  border-bottom: 1px solid #F2F5EA;
}

#header .btn-dropdown.active .dropdown-menu {
  display: block;
}

#header .nav-link {
  position: relative;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  color: #FFFFFF;
  padding: 24px 11px 25px;
}

#header .nav-link:hover::after {
  position: absolute;
  content: "";
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  height: 4px;
  background: #690FAD;
  border-radius: 3px 3px 0px 0px;
}

@media (min-width: 992px) {
  #header .nav-link:hover::after {
    background: #F2F5EA;
  }
}

@media (min-width: 992px) {
  #header .nav-link .svg {
    display: none;
  }
}

#header .nav-link.active::after {
  position: absolute;
  content: "";
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  height: 4px;
  background: #690FAD;
  border-radius: 3px 3px 0px 0px;
}

@media (min-width: 992px) {
  #header .nav-link.active::after {
    background: #F2F5EA;
  }
}

#header .btn-signup {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 500;
  line-height: 21px;
  color: #690FAD;
  background-color: #FFFFFF;
  margin-left: 21px;
  padding: 7px 19px 8px;
}

@media screen and (max-width: 991px) {
  #header .btn-signup {
    font-size: 14px;
    line-height: 18px;
    font-weight: 700;
    margin-left: 15px;
    padding: 7px 15px 6px;
  }
}

@media (max-width: 991px) {
  #header .navbar-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 9;
    background-color: #FFFFFA;
    -webkit-box-shadow: 0px -22px 40px rgba(0, 0, 0, 0.1);
    box-shadow: 0px -22px 40px rgba(0, 0, 0, 0.1);
  }

  #header .navbar-nav .nav-list {
    overflow-x: auto;
    scrollbar-width: 0;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }

  #header .navbar-nav .nav-link {
    color: #000000;
  }

  #header .navbar-nav .nav-link svg {
    margin-right: 13px;
  }
}

#header .btn-signin {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: right;
  color: #FFFFFF;
}

@media (max-width: 991px) {
  #header .btn-signin {
    font-size: 14px;
    font-weight: 600;
    line-height: 17px;
  }

  .brand-logo {
    width: 180px;
  }
}

@media (max-width: 500px) {
  #header .container-fluid.px-5 {
    padding-left: 15px;
    padding-right: 15px;
  }

  #header .brand-logo {
    width: 100%;
    max-width: 137px;
  }

  #header .btn-signup {
    margin-left: 10px;
    font-size: 12px;
    padding: 7px 15px 6px;
  }

  #header .btn-signin {
    font-size: 12px;
  }

  #header .btn-dropdown {
    margin-left: 10px;
  }
}

@media (max-width: 374px) {
  #header .container-fluid.px-5 {
    padding-left: 15px;
    padding-right: 15px;
  }

  #header .brand-logo {
    width: 100%;
    max-width: 100px;
  }

  #header .btn-signup {
    margin-left: 10px;
    font-size: 12px;
    padding: 7px 15px 6px;
  }

  #header .btn-signin {
    font-size: 12px;
  }

  #header .btn-dropdown {
    margin-left: 10px;
  }

  #header .nav-link {
    font-size: 12px;
    padding: 17px 8px 19px;
  }

  #header .nav-link svg,
  #header .nav-link img {
    margin-right: 10px !important;
    max-height: 15px;
  }
}

</style>
