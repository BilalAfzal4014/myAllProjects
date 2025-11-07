<template>
  <img :src="constants.getImageUrl(`facility_gallery/${galleryImage}`)" alt="" class="gallery-img">
</template>

<script>
import {BASE_URLS} from "@/common/constants/constants";

export default {
  name: "GalleryCard",
  props: {
    galleryImage: {
      type: String,
      default: "",
    }
  },
  computed: {
    facilityGalleryBaseUrl() {
      return BASE_URLS.ASSETS_PREFIX_FACILITY_GALLERY;
    },
  },
};
</script>

<style lang="scss" scoped>
.gallery-img {
  width: 265px;
  height: 265px;
  object-fit: cover;
  object-position: center;
  border-radius: 12px;
  box-shadow: 0px 4px 6px 0px rgba(28, 4, 47, 0.03), 0px 12px 16px 0px rgba(28, 4, 47, 0.08);
  @media (max-width: 767px) {
    width: 183px;
    height: 183px;
  }
}
</style>