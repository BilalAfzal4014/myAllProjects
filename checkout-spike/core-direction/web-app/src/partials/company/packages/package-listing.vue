<template>
  <div class="custom-container">
    <package-card :packages="packages" />
  </div>
</template>

<script>
import PackageCard from "./package-card";
import {getCompanyDetail} from "@/apiManager/company-detail";

export default {
  name: "PackageListing",
  components: {PackageCard},
  data() {
    return {
      packages: []
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
        const response = await getCompanyDetail({slug: this.companySlug, type: "packages"});
        this.packages = response.data;
      } catch (error) {
        toastr.error("An error occurred while fetching the packages");
      }
    }
  }
};
</script>

<style scoped>

</style>