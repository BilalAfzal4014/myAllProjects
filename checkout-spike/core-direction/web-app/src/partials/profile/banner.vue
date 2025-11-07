<template>
  <div class="profile-banner mx-auto">
    <img :src="bannerImage" class="profile-banner-img" alt="">

    <div class="custom-container">
      <button class="btn-banner-change flex items-center" type="button" @click="$refs.banner.click()">
        <input id="banner" ref="banner" type="file" style="display:none" @change="uploadBannerImage">
        Change Image
        <img src="/assets/images/profile/change-banner.png" alt="">
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import * as toastr from "toastr";
export default {
    name: "Banner",
    props:{
        banner: {
            type: String,
            default: "",
        },
    },
    data(){
        return {
            bannerImage:this.banner
        };
    },

    methods:{
        uploadBannerImage(e) {
            const file = e.target.files[0];
            this.bannerImage = URL.createObjectURL(file);
            let formData = new FormData();
            let imagefile = document.querySelector("#banner");
            formData.append("image", imagefile.files[0]);
            formData.append("image_type","banner");
            $(".ajax_loader").show();
            axios.post(this.constants.getUrl("updateUserProfile"), formData, {
                auth: {
                    username: this.constants.basicAuth.username,
                    password: this.constants.basicAuth.password
                },
                headers: {
                    auth: localStorage.getItem("token"),
                    "Content-Type": "multipart/form-data"
                }
            }).then((response)=>{
                $(".ajax_loader").hide();
                toastr.success("Banner has been updated successfully.");
                this.$emit("updateUerBannerImage","information update");
            }).catch((...error)=>{
                $(".ajax_loader").hide();
                if(error[0].response.data.status == 401){
                    toastr.error(error[0].response.data.message);
                    localStorage.removeItem("token");
                    localStorage.removeItem("userProfile");
                    window.location.reload();
                    return;
                }
      
                toastr.error(error[0].response.data.error[0]);
            });
        }
    }


};
</script>

<style scoped>

</style>