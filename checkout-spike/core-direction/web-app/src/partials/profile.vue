<template>
  <div v-click-outside-parent-element="setIsProfileDialogHidden">
    <div class="profile account-controls-item rounded-full">
      <div class="header-nav-link btn-user-profile cursor-pointer" @click.stop="toggleProfileDialog">
        <img :src="`${getProfileImageUrl()}`" alt="user-profile-image" class="user-profile-image" height="36"
             width="36"
        >
      </div>
      <div :class="`profile-window ${isProfileDialogHidden ? 'hidden': ''} `">
        <div class="profile-header flex items-center">
          <div class="profile-img rounded-full header-image">
            <img :src="`${getProfileImageUrl()}`" alt="user-profile-image">
          </div>
          <profile-header :email="`${ userProfile().email }`"
                          :profile-link="profileLink"
                          :username="`${ userProfile().firstname } ${userProfile().lastname}`"
          />
        </div>
        <profile-divider />
        <profile-dialog @onClickDropdownLink="setIsProfileDialogHidden" />
      </div>
    </div>
  </div>
</template>

<script>
import ProfileDialog from "./dialog/profile-dialog";
import ProfileDivider from "./profile-divider";
import ProfileHeader from "./profile-header";
import {mapGetters} from "vuex";
import emitter from "tiny-emitter/instance";
import constants from "@/constants/constants";
import DefaultImage from "@/assets/images/default_profile_img.png";

export default {
  name: "Profile",
  components: {ProfileHeader, ProfileDivider, ProfileDialog},
  computed: {
    ...mapGetters({
      userProfile: "getStoreUserProfileGetters",
    })
  },
  props: {
    isProfileDialogHidden: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      profileLink: this.constants.updateUserProfile,
    };
  },
  methods: {
    setIsProfileDialogHidden() {
      this.$emit("onHideProfileDialog");
    },
    toggleProfileDialog() {
      this.$emit("onToggleProfileDialog");
    },
    getProfileImageUrl() {
      return this.userProfile().picture
        ? constants.getImageUrl(this.userProfile().picture)  + "?optimizer=image&format=webp&width=96&aspect_ratio=1:1&sharpen=true"
        : DefaultImage;
    },
  },
  created() {
    emitter.on("profile_updated", () => {
      this.$forceUpdate();
    });
  }

};
</script>

<style scoped>
.profile-img.rounded-full.header-image svg {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.header-nav-list .header-nav-link {
  min-width: 36px;
  max-width: 36px;
  min-height: 36px;
  max-height: 36px;
  background: transparent;
  border: none;
  cursor: pointer;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  padding: 0;
}

.profile-img {
  min-width: 47px;
  min-height: 48px;
  max-width: 47px;
  max-height: 48px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
}

.user-profile-image {
  min-width: 36px;
  min-height: 36px;
  max-width: 36px;
  max-height: 36px;
  border-radius: 50%;
  object-fit: fill;
  object-position: center;
}
</style>