<template>
  <div class="business-profile-header">
    <div class="custom-container">
      <div class="business-profile-banner-box">
        <img :src="getImageUrl(companyBanner)"
             :srcset="`
            ${getOptimiserImage('member/'+companyBanner)}?optimizer=image&format=webp&width=480&sharpen=true 480w,
            ${getOptimiserImage('member/'+companyBanner)}?optimizer=image&format=webp&width=768&sharpen=true 768w,
            ${getOptimiserImage('member/'+companyBanner)}?optimizer=image&format=webp&width=992&sharpen=true 992w,
            ${getOptimiserImage('member/'+companyBanner)}?optimizer=image&format=webp&width=1240&sharpen=true 1240w,
            ${getOptimiserImage('member/'+companyBanner)}?optimizer=image&format=webp&width=1440&sharpen=true 1440w,
            ${getOptimiserImage('member/'+companyBanner)}?optimizer=image&format=webp&width=1920&sharpen=true 1920w,`"
             alt="" sizes="(min-width: 1260px) 1173px, calc(92.87vw + 21px)"
        >
      </div>
    </div>
  </div>
</template>

<script>
import DefaultImage from "@/assets/images/default_profile_img.png";

export default {
  name: "CompanyHeader",
  props: {
    companyBanner: {
      type: String,
      default: "",
    },
  },
  methods: {
    getImageUrl(imagePath) {
      return imagePath ? this.constants.getImageUrl("member/" + imagePath) + "?optimizer=image&format=webp" : DefaultImage;
    },
    getOptimiserImage(image) {
      return this.constants.getImageOptimisedUrl(image);
    }
  }
};
</script>

<style lang="scss" scoped>
 .custom-container{
    width: 100%;
    max-width: calc(1240px + 40px);
    padding-left: 20px;
    padding-right: 20px;
    margin-left: auto;
    margin-right: auto;
 }
</style>