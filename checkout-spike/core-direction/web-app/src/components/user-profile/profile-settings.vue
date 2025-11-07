<template>
  <main id="main">
    <view-image-modal v-if="isShowAvatarViewModal" :user-avatar="uploadImage" @cropImage="showCropImageModal"
                      @onClickOutsideViewImageModal="isShowAvatarViewModal = false"
    />
    <crop-profile-image v-if="isShowCropImageModal" :user-avatar="uploadImage"
                        @onClickOutsideCropImageModal="isShowCropImageModal = false"
                        @onUploadImage="uploadImageS3Bucket"
    />
    <section id="profile">
      <div class="custom-container mx-auto">
        <div class="profile-img-box mb-16 flex items-center justify-center">
          <div class="img-box" @click="$refs.userAvatar.click()">
            <div class="circle upload-button">
              <PreviewImage :src="user.picture" alt="user profile" classes="profile-pic" type="logo" />
            </div>
            <div class="upload-pen-box rounded-full  flex items-center justify-center">
              <pencil-icon />
            </div>
            <input id="profile_image" ref="userAvatar" accept="image/*" class="file-upload" style="display:none"
                   type="file" @change="uploadUserAvatar"
            >
          </div>
        </div>
        <div class="welcome-message-box mx-auto mb-20">
          <div class="welcome-message-inner-box">
            <div class="signup-form-container mx-auto">
              <p class="form-title">
                Hello!
              </p>
              <p class="form-title" style="font-weight: 400;">
                {{ user.username }}
              </p>
            </div>
            <div class="btn-update-community-profile-box">
              <button
                v-for="button in filteredButtons"
                :key="button.component"
                class="btn-update-community-profile"
                @click="button.onClick"
              >
                {{ button.label }}
                <component :is="button.icon" />
              </button>
            </div>
          </div>
        </div>
      </div>
      <keep-alive>
        <component :is="activeComponent" :user="user" @updateProfile="updateUserInformation" />
      </keep-alive>
    </section>
  </main>
</template>

<script>
import {changeObjectIntoArray, removePrefixFromObject, updateMetaInformation} from "../../utils";
import PersonalInformation from "@/components/user-profile/personal-information";
import UpdateCommunityInformation from "@/components/user-profile/update-community-information";
import CommunityIcon from "@/svgs/profile/community-icon";
import CameraIcon from "@/svgs/profile/camera-icon";
import ViewImageModal from "@/components/user-profile/view-image-modal";
import CropProfileImage from "@/components/user-profile/crop-profile-image";
import PencilIcon from "@/svgs/profile/pencil-icon";
import {updateProfile} from "@/apiManager/user";
import {uploadMedia} from "@/apiManager/media";
import Emitter from "tiny-emitter/instance";
import PreviewImage from "@/components/PreviewImage";
import {cloneDeep} from "lodash";

export default {
  name: "ProfileSettings",
  components: {
    PreviewImage,
    PencilIcon,
    CropProfileImage,
    ViewImageModal, CameraIcon, CommunityIcon, UpdateCommunityInformation, PersonalInformation
  },
  computed: {
    filteredButtons() {
      return this.buttons.filter(button => this.activeComponent !== button.component);
    }
  },
  data() {
    return {
      buttons: [
        {
          label: "Update Community Profile",
          component: "UpdateCommunityInformation",
          onClick: this.next,
          icon: CommunityIcon
        },
        {
          label: "Update Your Profile",
          component: "PersonalInformation",
          onClick: this.previous,
          icon: CameraIcon
        }
      ],
      fileName: "",
      isShowAvatarViewModal: false,
      isShowCropImageModal: false,
      step: 1,
      activeComponent: "PersonalInformation",
      user: this.$store.getters.getStoreUserProfileGetters(),
      uploadImage: "",
      updateUserProfileImage: cloneDeep(this.$store.getters.getStoreUserProfileGetters()),
    };
  },

  methods: {
    updateUserInformation(data, isImageUpload = false) {
      this.updateUserProfile(changeObjectIntoArray(JSON.parse(data)), isImageUpload);
    },
    updateUserProfile(payload, isImageUpload) {
      payload.phone = this.prependPlusSign(payload.phone);
      payload.emergency_phone = this.prependPlusSign(payload.emergency_phone);
      updateProfile(payload).then((response) => {
        const processedPayload = this.processPayload(response.data.user);

        if (isImageUpload) {
          this.handleImageUpload(processedPayload);
        } else {
          this.handleProfileUpdate(processedPayload);
        }
        toastr.success("Profile has been updated successfully.");
        Emitter.emit("profile_updated", "");
      }).catch(error => toastr.error(error));
    },
    prependPlusSign(number) {
      return number.startsWith("+") ? number : `+${number}`;
    },
    processPayload(payloadData) {
      let processedPayload = removePrefixFromObject(payloadData);
      let dob = new Date(processedPayload.birthdate);
      processedPayload["birthdate"] = dob.toISOString().substring(0, 10);
      processedPayload["gender"] = this.getGender(processedPayload["gender"]);

      return processedPayload;
    },

    handleImageUpload(payload) {
      toastr.success("Profile Image has been updated successfully.");
      this.$store.dispatch("removeUserProfileInformationAction");
      this.$store.dispatch("setUserProfileInformationAction", payload);
      this.user.picture = this.$store.getters.getStoreUserProfileGetters().picture;
    },

    handleProfileUpdate(payload) {
      this.$store.dispatch("removeUserProfileInformationAction");
      this.$store.dispatch("setUserProfileInformationAction", payload);
      this.user = this.$store.getters.getStoreUserProfileGetters();
      this.scrollToTop();
    },
    scrollToTop() {
      window.scrollTo(0, 0);
    },
    uploadUserAvatar(e) {
      const file = e.target.files[0];
      this.fileName = file.name;
      this.uploadImage = URL.createObjectURL(file);
      this.isShowAvatarViewModal = true;
      this.$refs.userAvatar.value = null;
    },
    showCropImageModal() {
      this.isShowCropImageModal = true;
    },
    previous() {
      if (this.step === 1) return false;
      this.step--;
      this.activeComponent = this.getCurrentActiveComponent(`step${this.step}`);

    },
    next() {
      if (this.step === 2) return false;
      this.step++;
      this.activeComponent = this.getCurrentActiveComponent(`step${this.step}`);

    },
    getCurrentActiveComponent(step) {
      return {
        step1: "PersonalInformation",
        step2: "UpdateCommunityInformation",
      }[step];
    },
    uploadImageS3Bucket(base64Image) {
      this.convertUrlIntoFile(base64Image);
    },
    convertUrlIntoFile(base64) {
      const convertUrlToFile = async (url, filename, mimeType) => {
        const res = await fetch(url);
        const buf = await res.arrayBuffer();
        return new File([buf], filename, {type: mimeType});
      };

      (async () => {
        const file = await convertUrlToFile(
          base64,
          this.generateRandomString() + this.fileName,
          "image/png"
        );
        this.postMediaToS3Bucket(file);
      })();
    },
    postMediaToS3Bucket(media) {
      let formData = new FormData();
      formData.append("file", media);
      uploadMedia(formData).then((response) => {
        this.updateUserProfileImage.picture = response.data.key;
        this.user.picture = response.data.key;
        this.updateUserInformation(JSON.stringify(this.updateUserProfileImage), true);
        this.isShowCropImageModal = false;
      }).catch(error => toastr.error(error));
    },
    generateRandomString() {
      return Math.random().toString(36).substr(2, 10);
    },
    getGender(gender) {
      return {
        "m": "Male",
        "f": "Female",
        "u": "unlisted"
      }[gender];
    },
  },
  mounted() {
    updateMetaInformation("My Profile | Core Direction", "Core Direction, Coredirection,my user-profile, edit user-profile", "Update & Edit your user-profile information.", "My Profile | Core Direction", "Update & Edit your user-profile information.", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/settings");
  }
};
</script>

<style>

#profile {
  background-color: #FFFFFF;
  -webkit-box-shadow: 0px -22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px -22px 40px rgba(0, 0, 0, 0.1);
  padding-bottom: 145px;
}

@media screen and (min-width: 992px) {
  #profile .profile-img-box {
    margin-top: 226px;
  }
}

#profile .profile-img-box .img-box {
  max-width: 150px;
  max-height: 150px;
  position: relative;
  border-radius: 50%;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  margin-top: 60px;
}

@media screen and (min-width: 992px) {
  #profile .profile-img-box .img-box {
    margin-top: -78px;
  }
}

#profile .profile-img-box .img-box .profile-pic {
  max-width: 150px;
  max-height: 150px;
  margin-left: auto;
  margin-right: auto;
  display: block;
  -o-object-fit: fill;
  object-fit: fill;
}

#profile .profile-img-box .img-box .file-upload {
  display: none;
}

#profile .profile-img-box .img-box .circle {
  border-radius: 50%;
  overflow: hidden;
  width: 150px;
  height: 150px;
  -webkit-transition: all .3s;
  transition: all .3s;
  background-image: url("/assets/images/camera-icon.png");
  background-repeat: no-repeat;
  background-position: center;
  background-color: #FFFFFF;
}

#profile .profile-img-box .img-box img {
  width: 180px;
  height: 180px;
}

#profile .profile-img-box .img-box .upload-pen-box {
  width: 34px;
  height: 34px;
  position: absolute;
  right: 2px;
  bottom: 2px;
  border: 4px solid #F1F1F1;
  background-color: #690FAD;
}

#profile .profile-img-box .img-box .upload-pen-box svg,
#profile .profile-img-box .img-box .upload-pen-box path {
  fill: #FFFFFF;
}

#profile .welcome-message-box {
  width: 100%;
  max-width: 820px;
  border-bottom: 1px solid #000000;
}

@media (max-width: 767px) {
  #profile .welcome-message-box {
    margin-bottom: 60px;
  }
}

#profile .welcome-message-inner-box {
  width: 100%;
  max-width: 650px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  -webkit-box-align: end;
  -ms-flex-align: end;
  align-items: flex-end;
  margin-left: auto;
  margin-right: auto;
}

@media (max-width: 767px) {
  #profile .welcome-message-inner-box {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: reverse;
    -ms-flex-direction: column-reverse;
    flex-direction: column-reverse;
    width: 100%;
  }
}

#profile .btn-update-community-profile {
  margin-bottom: 22px;
  margin-left: auto;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 19px;
  letter-spacing: 0em;
  text-align: center;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  color: #000000;
  background: #FFFFFF;
  padding: 17px 11px 17px 24px;
  -webkit-box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
  border-radius: 7px;
  -webkit-border-radius: 7px;
  -moz-border-radius: 7px;
  -ms-border-radius: 7px;
  -o-border-radius: 7px;
  cursor: pointer;
}

#profile .btn-update-community-profile svg, #profile .btn-update-community-profile img {
  margin-left: 23px;
}

#profile .btn-update-community-profile:hover {
  color: #FFFFFF;
  background: #690FAD;
}

#profile .btn-update-community-profile:hover svg, #profile .btn-update-community-profile:hover path {
  fill: #FFFFFF;
}

@media (max-width: 767px) {
  #profile .btn-update-community-profile {
    font-size: 14px;
    line-height: 16px;
    margin-right: auto;
    margin-bottom: 31px;
  }
}

@media (max-width: 767px) {
  #profile .btn-update-community-profile-box {
    width: 100%;
  }
}

#profile .signup-form-container {
  width: 100%;
  max-width: 550px;
}

@media (max-width: 767px) {
  #profile .grid.grid-cols-2.gap-4 {
    grid-template-columns: 1fr;
    row-gap: 20px;
  }

  #profile .grid.grid-cols-2.gap-4 button {
    margin-bottom: 0;
    margin-left: auto;
    margin-right: auto;
  }
}

#profile .form-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 36px;
  font-style: normal;
  font-weight: 700;
  line-height: 44px;
  letter-spacing: 0em;
  text-align: left;
  color: #000000;
  margin-bottom: 18px;
}

@media screen and (max-width: 991px) {
  #profile .form-title {
    font-size: 24px;
    line-height: 30px;
    margin-bottom: 30px;
  }
}

#profile .form-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17px;
  font-style: normal;
  letter-spacing: 0em;
  text-align: left;
  color: #222222;
  margin-bottom: 40px;
  max-width: 434px;
}

@media screen and (max-width: 767px) {
  #profile .form-desc {
    font-size: 12px;
    line-height: 15px;
    font-weight: 400;
    margin-bottom: 30px;
  }
}

#profile .form-desc strong {
  font-weight: 500;
}

#profile .form-label {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  color: #222222;
  opacity: 0.65;
}

#profile .form-label .field-primary {
  color: red;
}

#profile .form-control::-webkit-input-placeholder {
  font-style: italic;
  font-weight: 200;
}

#profile .form-control:-ms-input-placeholder {
  font-style: italic;
  font-weight: 200;
}

#profile .form-control::-ms-input-placeholder {
  font-style: italic;
  font-weight: 200;
}

#profile .form-control::placeholder {
  font-style: italic;
  font-weight: 200;
}

#profile .form-control {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  border-bottom: 1px solid #000000;
  width: 100%;
  padding: 11px 5px;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}

#profile .field-control-box {
  position: relative;
}

#profile .field-control-box .form-control {
  padding-right: 34px;
}

#profile .mb-9 .field-box .flex {
  position: relative;
}
#profile .field-control-box svg,
#profile .mb-9 .field-box .flex svg {
  position: absolute;
  right: 8px;
  bottom: 12px;
}

#profile .tc-content {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 500;
  line-height: 36px;
  letter-spacing: 0em;
  text-align: left;
  color: #222222;
}

#profile .tc-content a {
  color: #690FAD;
  font-weight: 700;
  text-decoration: underline;
}

@media screen and (max-width: 767px) {
  #profile .checkbox-field-box {
    margin-bottom: 60px;
  }
}

#profile .checkbox-field-box input:checked + label:after {
  top: 1px;
}

@media screen and (max-width: 767px) {
  #profile .checkbox-field-box input:checked + label:after {
    top: 0px;
  }
}

#profile .checkbox-field-box label {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

@media screen and (max-width: 767px) {
  #profile .checkbox-field-box label {
    font-size: 14px;
    line-height: 17px;
  }
}

#profile .btn-cancel {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #000000;
  width: 192px;
  height: 42px;
  -webkit-box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}

@media screen and (max-width: 767px) {
  #profile .btn-cancel {
    width: 250px;
    height: 50px;
  }
}

#profile .btn-update-profile {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
  width: 192px;
  height: 42px;
}

@media screen and (max-width: 767px) {
  #profile .btn-update-profile {
    width: 250px;
    height: 50px;
  }
}

#profile .social-signup-text {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-style: normal;
  font-weight: 500;
  line-height: 21px;
  letter-spacing: 0em;
  text-align: center;
}

@media screen and (max-width: 992px) {
  #profile .social-signup-text {
    font-size: 14px;
  }
}

#profile .signup-options {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-style: normal;
  font-weight: 500;
  line-height: 21px;
  letter-spacing: 0em;
  text-align: center;
  margin-top: 24px;
  margin-bottom: 35px;
}

@media screen and (max-width: 991px) {
  #profile .sm-layout-shift {
    grid-template-columns: 1fr;
    -webkit-column-gap: 0;
    column-gap: 0;
    row-gap: 35px;
  }

  #profile .sm-layout-shift .col-span-3 {
    -webkit-box-ordinal-group: 3;
    -ms-flex-order: 2;
    order: 2;
  }

  #profile .sm-layout-shift .col-span-2 {
    -webkit-box-ordinal-group: 2;
    -ms-flex-order: 1;
    order: 1;
  }
}

#profile .community-detail-box {
  width: 100%;
  max-width: 810px;
}

#profile .community-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 35px;
}

@media screen and (max-width: 767px) {
  #profile .community-title {
    display: block;
    width: 100%;
  }
}

#profile .account-privacy-box {
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 77px;
  column-gap: 77px;
  row-gap: 25px;
}

#profile .account-privacy-box .community-title {
  margin-bottom: 0;
}

#profile .account-privacy-box,
#profile .user-bio-box {
  margin-bottom: 65px;
}

@media screen and (max-width: 767px) {
  #profile .account-privacy-box,
  #profile .user-bio-box {
    margin-bottom: 34px;
  }
}

#profile .account-privacy-checkbox {
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 86px;
  column-gap: 86px;
  row-gap: 25px;
}

#profile .form-group input:checked + label:after {
  top: 1px;
}

#profile input[type='radio'] + label {
  vertical-align: unset;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

#profile .textarea-field-box {
  width: 100%;
  height: 153px;
  border: 1px solid rgba(6, 7, 14, 0.45);
  border-radius: 11px;
  padding: 14px 15px 18px;
  position: relative;
}

@media screen and (max-width: 767px) {
  #profile .textarea-field-box {
    height: 205px;
  }
}

#profile .textarea-field-box textarea {
  resize: none;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  width: 100%;
  height: 121px;
}

@media screen and (max-width: 767px) {
  #profile .textarea-field-box textarea {
    height: 175px;
  }
}

#profile .textarea-field-box .character-limit {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-style: italic;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: right;
  position: absolute;
  color: rgba(0, 0, 0, 0.65);
  right: 25px;
  bottom: 6px;
}

@media screen and (max-width: 767px) {
  #profile .textarea-field-box .character-limit {
    right: 10px;
    bottom: 8px;
  }
}

#profile .user-interest-box {
  margin-bottom: 115px;
}

@media screen and (max-width: 767px) {
  #profile .user-interest-box {
    margin-bottom: 60px;
  }
}

#profile .user-interest-list {
  padding: 29px 35px;
  -webkit-box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25);
  border-radius: 11px;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 16px;
  column-gap: 16px;
  row-gap: 16px;
}

@media screen and (max-width: 767px) {
  #profile .user-interest-list {
    min-height: 232px;
  }
}

#profile .user-interest-item {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25);
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  min-width: 60px;
  min-height: 58px;
  max-width: 60px;
  max-height: 58px;
  position: relative;
}

#profile .user-interest-item img {
  width: 100%;
  max-width: 25px;
}

#profile .user-interest-item .btn-remove {
  position: absolute;
  top: -4px;
  right: -2px;
  cursor: pointer;
}

#profile .btn-add-user-interest {
  cursor: pointer;
}

#profile .btn-add-activity-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 13px;
  column-gap: 13px;
  row-gap: 18px;
  margin-top: 30px;
}

#profile .btn-filter-activity-type {
  -webkit-box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25);
}

#profile.my-profile-community-info {
  background: transparent;
  -webkit-box-shadow: none;
  box-shadow: none;
}

@media screen and (min-width: 992px) {
  #profile.my-profile-community-info .profile-img-box {
    margin-top: 0px;
  }
}

@media screen and (min-width: 992px) {
  #profile.my-profile-community-info .profile-img-box .img-box {
    margin-top: 148px;
  }
}

#profile.my-profile-community-info .btn-update-community-profile {
  padding: 16px 42px 15px;
}

#profile.my-profile-community-info .btn-update-community-profile svg, #profile.my-profile-community-info .btn-update-community-profile img {
  margin-left: 23px;
}

#profile.my-profile-community-info .btn-update-community-profile:hover {
  color: #FFFFFF;
  background: #690FAD;
}

#profile.my-profile-community-info .btn-update-community-profile:hover svg, #profile.my-profile-community-info .btn-update-community-profile:hover path {
  fill: #FFFFFF;
}

@media (max-width: 767px) {
  #profile.my-profile-community-info .btn-update-community-profile {
    font-size: 14px;
    line-height: 16px;
    margin-right: auto;
    margin-bottom: 31px;
  }
}

#profile-img-upload .modal-outer-box,
#profile-img-crop .modal-outer-box {
  background: #FFFFFF;
  border-radius: 11px;
  padding-top: 53px;
  padding-bottom: 47px;
}

#profile-img-upload .form-container,
#profile-img-crop .form-container {
  width: 100%;
}

#profile-img-upload .upload-img-title,
#profile-img-crop .upload-img-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-style: normal;
  font-weight: 700;
  line-height: 21px;
  letter-spacing: 0em;
  text-align: center;
}

#profile-img-upload .upload-img-desc,
#profile-img-crop .upload-img-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
  line-height: 16px;
  letter-spacing: 0em;
  text-align: center;
}

#profile-img-upload .upload-img-desc em,
#profile-img-crop .upload-img-desc em {
  font-weight: 700;
}

#profile-img-upload .profile-img,
#profile-img-crop .profile-img {
  width: 234px;
  max-width: 234px;
  height: 234px;
  max-height: 234px;
  object-fit: fill;
  object-position: center;
}

#profile-img-upload .profile-img {
  object-fit: cover;
}

@media (max-width: 767px) {
  #profile-img-upload .profile-img,
  #profile-img-crop .profile-img {
    width: 200px;
    max-width: 200px;
    height: 200px;
    max-height: 200px;
  }
}

#profile-img-upload .btn-img-upload,
#profile-img-crop .btn-img-upload {
  display: block;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 500;
  line-height: 19px;
  letter-spacing: 0em;
  text-align: center;
  padding: 11px 30px 12px;
  color: #FFFFFF;
  margin-top: 56px;
}

#profile-img-upload .modal-outer-box {
  max-width: calc(396px + 2rem);
}

#profile-img-upload .form-container {
  max-width: 278px;
}

#profile-img-crop .modal-outer-box {
  max-width: calc(632px + 2rem);
}

#profile-img-crop .form-container {
  max-width: 310px;
}

#profile-img-crop .range-filter input[type=range] {
  -webkit-appearance: none;
  margin: 10px 0;
  width: 100%;
}

#profile-img-crop .range-filter input[type=range]:focus {
  outline: none;
}

#profile-img-crop .range-filter input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: #000000;
  border-radius: 25px;
}

#profile-img-crop .range-filter input[type=range]::-webkit-slider-thumb {
  -webkit-box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border: 1px solid #000000;
  height: 20px;
  width: 20px;
  border-radius: 30px;
  background: #FFFFFF;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -9px;
}

#profile-img-crop .range-filter input[type=range]::-moz-range-track {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: #000000;
  border-radius: 25px;
}

#profile-img-crop .range-filter input[type=range]::-moz-range-thumb {
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border: 1px solid #000000;
  height: 20px;
  width: 20px;
  border-radius: 30px;
  background: #FFFFFF;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -9px;
}

#profile-img-crop .range-filter input[type=range]::-ms-track {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: #000000;
  border-radius: 25px;
}

#profile-img-crop .range-filter input[type=range]::-ms-fill-lower {
  background: #ac51b5;
  border: 0px solid #000101;
  border-radius: 50px;
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
}

#profile-img-crop .range-filter input[type=range]::-ms-fill-upper {
  background: #ac51b5;
  border: 0px solid #000101;
  border-radius: 50px;
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
}

#profile-img-crop .range-filter input[type=range]::-ms-thumb {
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border: 1px solid #000000;
  height: 20px;
  width: 20px;
  border-radius: 30px;
  background: #FFFFFF;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -9px;
}

.form-group label::before {
  width: 18px;
  height: 18px;
  content: '';
  -webkit-appearance: none;
  background-color: transparent;
  padding: 10px;
  display: inline-block;
  position: relative;
  vertical-align: middle;
  cursor: pointer;
  margin-right: 10px;
  margin-left: -36px;
  background-image: url("../../assets/images/checkbox-uncheck.png");
  background-repeat: no-repeat;
}

.form-group input {
  padding: 0;
  height: initial;
  width: initial;
  margin-bottom: 0;
  display: none;
  cursor: pointer;
}
</style>