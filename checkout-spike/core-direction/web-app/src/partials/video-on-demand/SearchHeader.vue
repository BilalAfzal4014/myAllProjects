<template>
  <section id="listing-filter" class="on-demand-filter">
    <div
      class="custom-container filter-box flex mx-auto justify-between items-center rounded"
    >
      <div class="input-field-search-activity-box mr-4">
        <input
          v-model="search"
          v-debounce="() => setKeyword(search)"
          class="location rounded-full"
          placeholder="Search keywords . ."
          type="text"
        >
        <SearchIcon />
      </div>
      <vod-category-dropdown :categories="categoryList"
                             :selected-category="selectedCategory"
                             optionHeading="Select Category"
                             @onSelectCategory="setCategory"
      />
      <vod-sub-category-dropdown :checkedSubCategories="checkedSubCategories"
                                 :isAllSubCategoriesChecked="isAllSubCategoriesChecked"
                                 :selectedSubCategories="selectedSubCategories"
                                 :subCategoryList="subCategoryList"
                                 optionHeading="Select Sub Category"
                                 @handleAllCheckSubCategories="handleAllCheckSubCategories"
                                 @handleCheckSubCategory="handleCheckSubCategory"
      />
      <div class="search-btn-box ml-4 lg:mb-0 mb-4">
        <button class="rounded-full" @click="resetFilters">
          Clear Filter
        </button>
      </div>
    </div>
  </section>
</template>

<script>
import SearchIcon from "@/svgs/video-on-demand/SearchIcon";
import {getVideoSubCategories} from "@/apiManager/video-on-demand";
import * as toastr from "toastr";
import VodCategoryDropdown from "@/components/video-on-demand/vod-category-dropdown";
import VodSubCategoryDropdown from "@/components/video-on-demand/vod-subcategory-dropdown";

export default {
  name: "SearchHeader",
  components: {VodSubCategoryDropdown, VodCategoryDropdown, SearchIcon},
  props: {
    selectedCategory: {
      type: Object,
      default: null,
    },
    selectedSubCategories: {
      type: Array,
      default: () => []
    },
    categoryList: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      search: "",
      subCategoryList: [],
      checkedSubCategories: [],
      isAllSubCategoriesChecked: false,
    };
  },
  watch: {
    selectedCategory: {
      handler: "handleSelectedCategoryChange",
      deep: true
    },
  },
  methods: {
    handleSelectedCategoryChange(selectedCategory) {
      if (selectedCategory.id === null) {
        this.subCategoryList = [];
      } else {
        this.getSubCategories(selectedCategory.id);
      }
    },
    handleAllCheckSubCategories(event) {
      const isChecked = event.target.checked;
      this.isAllSubCategoriesChecked = isChecked;
      this.setSubCategory(isChecked ? [...this.subCategoryList] : []);
    },
    resetFilters() {
      this.$emit("resetFilters");
      this.setCategory({name: "Select Category", id: null});
      this.search = "";
      this.showCategoryDropdown = false;
      this.$emit("getSubCategoriesLength", 0);
      this.removeSlug();
    },
    removeSlug() {
      let pathArray = this.$route.path.split("/");
      pathArray.pop();
      let newPath = pathArray.join("/");
      this.$router.push({path: newPath});
    },
    setKeyword(value) {
      this.$emit("setKeyword", value);
    },
    setCategory(value) {
      this.$emit("setCategory", value);
      this.checkedSubCategories = this.subCategoryList.map(() => false);
      this.isAllSubCategoriesChecked = false;
    },
    handleCheckSubCategory({event, index}) {
      this.checkedSubCategories[index] = event.target.checked;
      const checkedCount = this.checkedSubCategories.filter(Boolean).length;
      this.isAllSubCategoriesChecked = this.subCategoryList.length === checkedCount;
      let selectedSubCategories = this.subCategoryList.filter((subCategory, idx) => this.checkedSubCategories[idx]);
      this.$emit("setSubCategory", [...selectedSubCategories]);
    },
    setSubCategory(value) {
      const isChecked = Array.isArray(value) && value.length > 0;
      this.checkedSubCategories = this.subCategoryList.map(() => isChecked);
      this.$emit("setSubCategory", value);
    },
    getSubCategories(categoryId) {
      getVideoSubCategories(categoryId)
        .then((response) => {
          this.subCategoryList = response.data;
          this.$emit("getSubCategoriesLength", this.subCategoryList.length);
        })
        .catch((error) => {
          this.subCategoryList = [];
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
  },
};
</script>

<style scoped>
#listing-filter.on-demand-filter input[type=checkbox] {
  display: none;
}


</style>