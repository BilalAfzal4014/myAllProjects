<template>
  <div class="profile-img-box mt-4 mb-9 flex items-center justify-center">
    <div class="img-box hidden">
      <div class="circle upload-button" @click="$refs.file.click()">
        <!-- User Profile Image -->
        <img v-if="imageUrl" class="profile-pic" :src="constants.getImageUrl(imageUrl)">
        <!-- Default Image -->
        <!-- <i class="fa fa-user fa-5x"></i> -->
      </div>
      <div class="upload-pen-box rounded-full  flex items-center justify-center " @click="$refs.file.click()">
        <pencil-icon />
      </div>
      <input ref="file" class="file-upload" type="file" accept="image/*" @change="uploadImage">
    </div>
  </div>
</template>

<script>
import PencilIcon from "@/svgs/pencil-icon";

export default {
    name: "SignUpImageUpload",
    components: {PencilIcon},
    props:{
        imageUrl: {
            type: String,
            default: "",
        },
    },
    methods: {
        uploadImage(e) {
            const file = e.target.files[0];
            this.encodeImageFileToBase64(file, (myBase64) => {
                this.$emit("changed", {"base64":myBase64,"imageUrl":URL.createObjectURL(file)});
            });
        },
        toDataUrl(url, callback) {
            let xhr = new XMLHttpRequest();
            xhr.onload = function () {
                let reader = new FileReader();
                reader.onloadend = function () {
                    callback(reader.result);
                };
                reader.readAsDataURL(xhr.response);
            };
            xhr.open("GET", url);
            xhr.responseType = "blob";
            xhr.send();
        },
        encodeImageFileToBase64(file, callback) {
            const reader = new FileReader();
            reader.onloadend = function () {
                callback(reader.result);
            };
            reader.readAsDataURL(file);
        }
    }
};
</script>

<style scoped>

</style>