<template>
  <div class="custom-container">
    <ul class="business-profile-gallery-list">
      <li v-for="(gallery,index) in galleries" :key="index" class="business-profile-gallery-item">
        <img :src="gallery.image_name" alt="">
      </li>
    </ul>
  </div>
</template>

<script>
import * as toastr from "toastr";
import {getCompanyDetail} from "@/apiManager/company-detail";

export default {
  name: "BookingGallery",
  data() {
    return {
      galleries: []
    };
  },
  computed: {
    companySlug() {
      return this.$route.params.slug;
    }
  },
  created() {
    this.fetchPackages();
  },
  methods: {
    async fetchPackages() {
      try {
        const response = await getCompanyDetail({slug: this.companySlug, type: "gallery"});
        this.galleries = response.data.map(gallery => {
          gallery.image_name = this.constants.getImageUrl(`facility_gallery/${gallery.image_name}`);
          return gallery;
        });
      } catch (error) {
        toastr.error("An error occurred while fetching the Galleries");
      }
    }
  }
};
</script>

<style scoped>
@media screen and (max-width: 767px) {
  #business-profile .business-profile-gallery-list {
    margin-top: 50px !important;
  }
}
</style>