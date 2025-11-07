<template>
  <BaseDropdown :isOpen="isOpen" :optionHeading="optionHeading" :selectedOption="selectedOption"
                @onToggleDropdown="toggleDropdown"
  >
    <div class="category-field-header">
      <select class="rounded-full" disabled>
        <option value="">
          Sub-Category
        </option>
      </select>
      <UpArrow />
    </div>
    <div class="category-field-body " @click.stop>
      <ul class="category-list">
        <li v-if="subCategoryList.length > 0" class="category-item">
          <CustomCheckbox
            :inputId="'subcategory-all'"
            :inputName="'selectAll'"
            :inputStyle="'display: none !important;'"
            :inputValue="'all'"
            :isChecked.sync="isAllSubCategoriesChecked"
            @checked-value="handleAllCheckSubCategories"
          >
            Select All
          </CustomCheckbox>
        </li>
        <li
          v-for="(subCategory, index) in subCategoryList"
          :key="`${index}-category-item${subCategory.name}`"
          class="category-item"
        >
          <CustomCheckbox
            :inputId="`subcategory-${index}`"
            :inputName="'subCategoryCheckbox'"
            :inputStyle="'display: none !important;'"
            :inputValue="subCategory.name"
            :isChecked.sync="checkedSubCategories[index]"
            @checked-value="(event) => handleCheckSubCategory(event, index)"
          >
            {{ subCategory.name }}
          </CustomCheckbox>
        </li>
      </ul>
    </div>
  </BaseDropdown>
</template>

<script>
import BaseDropdown from "@/components/form/base-dropdown";
import UpArrow from "@/svgs/video-on-demand/UpArrow";
import CustomCheckbox from "@/components/form/custom-checkbox";
import {eventEmitter} from "@/eventEmitter.js";

export default {
  name: "VodSubCategoryDropdown",
  components: {CustomCheckbox, UpArrow, BaseDropdown},
  data() {
    return {
      isOpen: false,
    };
  },
  watch: {
    toggleType(newToggleType) {
      this.isOpen = newToggleType !== "subcategory";
    },
  },
  props: {
    subCategoryList: {
      type: Array,
      default: () => []
    },
    selectedSubCategories: {
      type: Array,
      default: () => []
    },
    optionHeading: {
      type: String,
      required: true,
    },
    checkedSubCategories: {
      type: Array,
      default: () => []
    },
    toggleType: {
      type: [String, null],
      default: null
    },
    isAllSubCategoriesChecked: {
      type: Boolean,
      default: false
    }

  },
  computed: {
    selectedOption() {
      return this.selectedSubCategories.length > 0
        ? this.selectedSubCategories[0].name
        : "Sub-Category";
    },
  },
  created() {
    eventEmitter.on("dropdown-opened", (openedDropdown) => {
      if (openedDropdown !== this.optionHeading) {
        this.isOpen = false;
      }
    });
    document.addEventListener("click", this.closeDropdown);
  },
  beforeDestroy() {
    eventEmitter.off("dropdown-opened");
    document.removeEventListener("click", this.closeDropdown);
  },
  methods: {
    closeDropdown(e) {
      if (!this.$el.contains(e.target)) {
        this.isOpen = false;
      }
    },
    selectCategory(category) {
      this.$emit("onSelectCategory", category);
    },
    toggleDropdown() {
      this.isOpen = !this.isOpen;
      if (this.isOpen) {
        eventEmitter.emit("dropdown-opened", this.optionHeading);
      }
    },
    handleAllCheckSubCategories(event) {
      this.$emit("handleAllCheckSubCategories", event);
    },
    handleCheckSubCategory(event, index) {
      this.$emit("handleCheckSubCategory", {event, index});
    }
  },
};
</script>
