<template>
  <BaseDropdown :isOpen="isOpen" :selectedOption="selectedOptionName"
                @onToggleDropdown="toggleDropdown"
  >
    <div class="category-field-header">
      <select class="rounded-full" disabled>
        <option value="">
          Select Category
        </option>
      </select>
      <UpArrow />
    </div>
    <div class="category-field-body ">
      <ul class="category-list">
        <li v-for="category in categories" :key="category.id" class="category-item"
            @click.stop="selectCategory(category)"
        >
          {{ category.name }}
        </li>
      </ul>
    </div>
  </BaseDropdown>
</template>

<script>
import BaseDropdown from "@/components/form/base-dropdown";
import UpArrow from "@/svgs/video-on-demand/UpArrow";
import {eventEmitter} from "@/eventEmitter.js";

export default {
  name: "VodCategoryDropdown",
  components: {UpArrow, BaseDropdown},
  data() {
    return {
      isOpen: false
    };
  },
  computed: {
    selectedOptionName() {
      return this.selectedCategory.name || this.optionHeading;
    }
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
  props: {
    categories: {
      type: Array,
      default: () => []
    },
    selectedCategory: {
      type: Object,
      required: false
    },
    optionHeading: {
      type: String,
      required: true,
    },
  },

  methods: {
    selectCategory(category) {
      this.$emit("onSelectCategory", category);
      this.isOpen = false;
    },
    closeDropdown(e) {
      if (!this.$el.contains(e.target)) {
        this.isOpen = false;
      }
    },
    toggleDropdown() {
      this.isOpen = !this.isOpen;
      if (this.isOpen) {
        eventEmitter.emit("dropdown-opened", this.optionHeading);
      }
    }

  },

};
</script>
