<template>
  <div class="profile-img-box mb-16 flex items-center justify-center">
    <div class="img-box" @click="$refs.profileImage.click()">
      <div class="circle upload-button">
        <img :src="constants.getImageUrl(profileImage)" class="profile-pic">
      </div>
      <div class="upload-pen-box rounded-full  flex items-center justify-center">
        <pencil-icon />
      </div>
      <input id="profile_image" ref="profileImage" accept="image/*" class="file-upload" style="display:none" type="file"
             @change="uploadProfileImage"
      >
    </div>
  </div>
</template>

<script>
import PencilIcon from "../../svgs/pencil-icon";

export default {
    name: "UserProfileImage",
    components: {PencilIcon},
    props: {
        profile: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            profileImage: this.profile
        };
    },
    methods: {
        uploadProfileImage(e) {
            const file = e.target.files[0];
            this.profileImage = URL.createObjectURL(file);
            let formData = new FormData();
            let imagefile = document.querySelector("#profile_image");
            formData.append("image", imagefile.files[0]);
            formData.append("image_type", "logo");
        }
    }
};
</script>

<style scoped>

</style>