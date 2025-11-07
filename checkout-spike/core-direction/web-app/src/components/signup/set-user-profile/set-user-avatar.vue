<template>
  <div class="avatar-section">
    <view-image-modal v-if="isShowAvatarViewModal" :user-avatar="userAvatar"
                      @cropImage="showCropImageModal" @onClickOutsideViewImageModal="isShowAvatarViewModal = false"
    />
    <crop-profile-image v-if="isShowCropImageModal" :user-avatar="userAvatar"
                        @onClickOutsideCropImageModal="isShowCropImageModal = false"
                        @onUploadImage="uploadImageS3Bucket"
    />
    <input ref="file" class="file-upload hidden"
           type="file" @change="uploadUserAvatar"
    >
    <p class="section-title">
      Add an avatar
    </p>
    <div class="avatar-box flex items-center">
      <div :class="avatarImgBoxClasses"
           @click="$refs.file.click()"
      >
        <img :src="previewImage?.includes('blob') ? previewImage : constants.getImageUrl(userProfile().picture)" alt="">
      </div>
      <div class="other-img-box">
        <button class="btn-upload-img" @click="$refs.file.click()">
          Choose image
        </button>
        <p class="avatar-text my-4">
          or choose an Avatar
        </p>
        <ul class="img-list flex items-center mt-4">
          <li v-for="image in defaultImages" :key="image.name" class="img-item mx-1"
              @click="convertImagePathToFileObject(image.gender,true)"
          >
            <img :alt="image.name" :src="image.path" class="build-in-img" style="width: 50px">
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import ViewImageModal from "@/components/user-profile/view-image-modal";
import CropProfileImage from "@/components/user-profile/crop-profile-image";
import {mapGetters} from "vuex";
import uploadMediaMixin, {DEFAULT_IMAGES} from "@/mixin/uploadMediaMixin";

export default {
    name: "UserAvatarProfile",
    components: {CropProfileImage, ViewImageModal},
    mixins: [uploadMediaMixin],
    data() {
        return {
            userAvatar: "",
            previewImage: null,
            isShowAvatarViewModal: false,
            isShowCropImageModal: false,
        };

    },
    computed: {
        ...mapGetters({
            userProfile: "getStoreUserProfileGetters",
        }),
        avatarImgBoxClasses() {
            const hasBackgroundImage = !this.userProfile().picture;
            return ["avatar-img-box", {"background-image": hasBackgroundImage}];
        },
        defaultImages() {
            return DEFAULT_IMAGES;
        }
    },
    methods: {
        convertUrlIntoFile(base64, imageName) {
            const convertUrlToFile = async (url, filename, mimeType) => {
                const res = await fetch(url);
                const buf = await res.arrayBuffer();
                return new File([buf], filename, {type: mimeType});
            };

            (async () => {
                const file = await convertUrlToFile(
                    base64,
                    imageName,
                    "image/png"
                );
                await this.uploadImageFileToS3Bucket(file, true);
            })();
        },
        showCropImageModal() {
            this.isShowAvatarViewModal = false;
            this.isShowCropImageModal = true;
        },
        uploadImageS3Bucket(base64Image) {
            this.convertUrlIntoFile(base64Image);
        },
    }
};
</script>

<style scoped>
.background-image {
  background-image: url("/assets/images/profile-build-camera.png");
  background-repeat: no-repeat;
  background-position: center;
  background-size: 50%;
}

.avatar-text {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 400;
  font-size: 12px;
  line-height: 15px;
  color: #000000;
}
</style>