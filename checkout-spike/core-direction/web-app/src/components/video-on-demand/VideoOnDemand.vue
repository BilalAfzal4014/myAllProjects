<template>
  <div>
    <SearchHeader
      :category-list="categoryList"
      :keyword="keyword"
      :selected-category="selectedCategory"
      :selected-sub-categories="selectedSubCategories"
      @getSubCategoriesLength="categoriesLength"
      @resetFilters="resetFilters"
      @setCategory="setCategory" @setKeyword="setKeyword" @setSubCategory="setSubCategory"
    />
    <AdvanceFilters
      v-if="showAdvanceFilters && showAllVideos"
      :selected-durations="selectedDurations"
      :selected-equipments="selectedEquipments"
      :selected-types="selectedTypes"
      @setDurations="setDurations"
      @setEquipments="setEquipments"
      @setTypes="setTypes"
    />
    <FavoriteListing v-if="(Object.keys(selectedCategory).length === 0 || selectedCategory.id === null) &&
      keyword.trim().length === 0 && !selectedDurations.length && !selectedEquipments.length && !selectedTypes.length"
    />
    <div v-if="showAllVideos">
      <VideoListing
        v-for="(items, index) in videoContentListing"
        :key="`${index}-video-listing-${items}`"
        :content="items"
        :number-of-sub-categories="numberOfSubCategories"
        :selected-category="selectedCategory"
        :selected-sub-categories="selectedSubCategories"
        :title="index"
        @seeAllSubCategory="seeAllSubCategory"
        @setCategory="setCategory"
      />
    </div>
  </div>
</template>

<script>
import SearchHeader from "@/partials/video-on-demand/SearchHeader";
import AdvanceFilters from "@/partials/video-on-demand/AdvanceFilters";
import VideoListing from "@/partials/video-on-demand/VideoListing";
import {setUniqueElement, updateMetaInformation} from "@/utils";
import {getVideoCategories, getVideoContent} from "@/apiManager/video-on-demand";
import * as toastr from "toastr";
import FavoriteListing from "@/partials/video-on-demand/FavoriteListing";

export default {
  name: "VideoOnDemand",
  components: {
    FavoriteListing,
    VideoListing,
    AdvanceFilters,
    SearchHeader,
  },
  data() {
    return {
      keyword: "",
      selectedCategory: {},
      selectedSubCategories: [],
      selectedDurations: [],
      selectedEquipments: [],
      selectedTypes: [],
      initialPayload: {
        keyword: "",
        categoryId: null,
        subCategoryId: [],
        durations: [],
        equipments: [],
        types: [],
      },
      categoryList: [],
      showAdvanceFilters: true,
      videoContentListing: {},
      showAllVideos: true,
      showFavoriteTileView: false,
      numberOfSubCategories: 0
    };
  },
  mounted() {
    updateMetaInformation("On-Demand Video | Core Direction", "wellness videos, wellness podcasts, On-demand video library", "Browse our On-Demand video library: Hand picked and curated wellness content.", "On-Demand Video | Core Direction", "Browse our On-Demand video library: Hand picked and curated wellness content", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/on-demand");
  },
  async created() {
    await this.getCategories();

    const categoryName = this.$route.params.category;
    if (categoryName) {
      const categoryObject = this.findCategoryByName(categoryName);
      if (categoryObject) {
        this.setCategory(categoryObject);
      }
    }

    await this.getVideoContent(this.initialPayload);
  },
  methods: {
    resetAdvanceFilter() {
      this.selectedDurations = this.initialPayload.durations;
      this.selectedEquipments = this.initialPayload.equipments;
      this.selectedTypes = this.initialPayload.types;
    },
    setShowAdvanceFilter() {
      this.showAdvanceFilters = !(
        this.keyword ||
          this.selectedCategory.id ||
          this.selectedSubCategories.length > 0
      );
    },
    resetFilters() {
      this.keyword = this.initialPayload.keyword;
      this.selectedCategory = this.initialPayload.categoryId;
      this.selectedSubCategories = this.initialPayload.subCategoryId;
      this.selectedDurations = this.initialPayload.durations;
      this.selectedEquipments = this.initialPayload.equipments;
      this.selectedTypes = this.initialPayload.types;
      this.showAllVideos = true;
      this.showFavoriteTileView = false;
    },
    setKeyword(value) {
      this.keyword = value;
      this.getVideoContent(this.getVideoContentPayload());
      this.setShowAdvanceFilter();
      this.resetAdvanceFilter();
    },
    findCategoryByName(categoryName) {
      return this.categoryList.find(category => category.name === categoryName);
    },
    setCategory(value) {
      this.selectedCategory = value;
      this.selectedSubCategories = [];
      this.getVideoContent(this.getVideoContentPayload());
      this.setShowAdvanceFilter();
      this.resetAdvanceFilter();
    },
    setSubCategory(value) {
      if (Array.isArray(value)) {
        this.selectedSubCategories = value;
      } else {
        this.selectedSubCategories = setUniqueElement(this.selectedSubCategories, value);
      }
      this.getVideoContent(this.getVideoContentPayload());
    },
    seeAllSubCategory(val) {
      this.selectedSubCategories = [];
      this.setSubCategory(val);
    },
    setDurations(value) {
      this.selectedDurations = setUniqueElement(this.selectedDurations, value);
      this.getVideoContent(this.getVideoContentPayload());
    },
    setEquipments(value) {
      this.selectedEquipments = setUniqueElement(this.selectedEquipments, value);
      this.getVideoContent(this.getVideoContentPayload());
    },
    setTypes(value) {
      this.selectedTypes = setUniqueElement(this.selectedTypes, value);
      this.getVideoContent(this.getVideoContentPayload());
    },
    getVideoContentPayload() {
      return {
        keyword: this.keyword,
        categoryId: this.selectedCategory.id,
        subCategoryId:
            this.selectedSubCategories.length > 0
              ? this.selectedSubCategories.map((subCategory) => subCategory.id)
              : this.initialPayload.subCategoryId,
        durations:
            this.selectedDurations.length > 0
              ? this.selectedDurations.map((duration) => duration.id)
              : this.initialPayload.durations,
        equipments:
            this.selectedEquipments.length > 0
              ? this.selectedEquipments.map((equipment) => equipment.id)
              : this.initialPayload.equipments,
        types:
            this.selectedTypes.length > 0
              ? this.selectedTypes.map((type) => type.id)
              : this.initialPayload.types,
      };
    },
    getVideoContent(payload) {
      getVideoContent(payload)
        .then((response) => {
          this.videoContentListing = response.data;
          for (let category in this.videoContentListing) {
            this.videoContentListing[category] = this.shuffleCategoryVideos(this.videoContentListing[category]);
          }
        })
        .catch((error) => {
          this.showToastrError(error);
        });
    },
    shuffleCategoryVideos(categoryVideos) {
      for (let currentVideoIndex = categoryVideos.length - 1; currentVideoIndex > 0; currentVideoIndex--) {
        let randomVideoIndex = Math.floor(Math.random() * (currentVideoIndex + 1));
        [categoryVideos[currentVideoIndex], categoryVideos[randomVideoIndex]] = [categoryVideos[randomVideoIndex], categoryVideos[currentVideoIndex]];
      }
      return categoryVideos;
    },
    categoriesLength(length) {
      this.numberOfSubCategories = length;
    },
    async getCategories() {
      try {
        const response = await getVideoCategories();
        this.categoryList = response.data;
      } catch (error) {
        this.showToastrError(error);
      }
    },
    showToastrError(error) {
      const errorMessage = error?.response?.data?.errors?.[0]?.error || "Unknown error occurred";
      toastr.error(errorMessage);
    }
  },
};
</script>

<style scoped></style>