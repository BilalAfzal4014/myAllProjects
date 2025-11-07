<template>
  <div>
    <QRCodeModal v-if="showQRCodeModal" @hide="hideQRCode" />
    <header id="app-header">
      <div class="container-fluid px-5 flex items-center">
        <logo />
        <navbar-left :is-menu-show="isNavbarShow" @onToggleNavbar="hideNavbar" />
        <div :class="`search-box ${isShowSearchIcon ? '':'search-box-show'}`">
          <search-input-field />
        </div>
        <div class="sm-btn-search" @click="showSearchField">
          <img
            :src="`${isShowSearchIcon ? '/assets/images/icons/btn-Search.png' : '/assets/images/icons/btn-search-box-close.png'}`"
            alt="Search Icon"
            class="btn-search"
          >
        </div>
        <ul class="account-controlls flex items-center">
          <Notification :is-notification-shown="isNotificationShown" @onHideNotification="hideNotification"
                        @onToggleNotification="toggleNotification"
          />
          <QrCode class="mr-2" @clicked="showQrCode" />
          <profile :is-profile-dialog-hidden="isProfileDialogHidden" @onHideProfileDialog="hideProfileDialog"
                   @onToggleProfileDialog="toggleDropdown"
          />
        </ul>
        <div
          id="toggle"
          class="cursor-pointer ml-5 hamburger-menu-wrapper"
          @click="toggleNavbar"
        >
          <toggler-icon-open v-if="!isNavbarShow" />
          <toggler-close-icon v-if="isNavbarShow" />
        </div>
      </div>
    </header>
  </div>
</template>

<script>
import Profile from "./profile";
import TogglerCloseIcon from "../svgs/toggler-close-icon";
import TogglerIconOpen from "../svgs/toggler-icon-open";
import SearchInputField from "./inputs/search-input-field";
import NavbarLeft from "./navbar-left";
import Logo from "./logo";
import QrCode from "@/svgs/QrCode";
import QRCodeModal from "../partials/QRCodeModal";
import Notification from "@/components/notification/Notification";

export default {
    name: "Navbar",
    components: {
        Notification,
        QRCodeModal,
        QrCode,
        Logo,
        NavbarLeft,
        SearchInputField,
        TogglerIconOpen,
        TogglerCloseIcon,
        Profile,
    },
    data() {
        return {
            showQRCodeModal: false,
            keyword: "",
            isNavbarShow: false,
            isShowSearchIcon: true,
            isProfileDialogHidden: true,
            isNotificationShown: false,
        };
    },
    methods: {
        showQrCode() {
            const body = document.querySelector("body");
            body.classList.add("overflow-y-hidden");
            this.showQRCodeModal = true;
        },
        hideQRCode() {
            this.showQRCodeModal = false;
        },
        toggleNavbar() {
            this.isNavbarShow = !this.isNavbarShow;
            this.isProfileDialogHidden = true;
            this.isNotificationShown = false;
        },
        hideNavbar() {
            this.isNavbarShow = false;
        },
        showSearchField() {
            this.isShowSearchIcon = !this.isShowSearchIcon;
        },
        toggleDropdown() {
            this.isProfileDialogHidden = !this.isProfileDialogHidden;
            this.isNavbarShow = false;
            this.isNotificationShown = false;
        },
        toggleNotification() {
            this.isNotificationShown = !this.isNotificationShown;
            this.isNavbarShow = false;
            this.isProfileDialogHidden = true;
        },
        hideNotification() {
            if (this.isNotificationShown) this.isNotificationShown = false;
        },
        hideProfileDialog() {
            this.isProfileDialogHidden = true;
        }

    },
};
</script>

<style>
@media (min-width: 992px) {
  .sm-btn-search {
    display: none;
  }
}
@media (max-width: 767px) {
  .sm-btn-search {
    display: none;
  }
}
@media (min-width: 768px) {
  .sm-btn-search {
    display: none;
  }
}
</style>