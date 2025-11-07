<template>
  <div class="input-field-category-box mr-2" @click.stop="toggleDropdown">
    <select class="rounded-full" disabled>
      <option value="">
        {{ selectedOption }}
      </option>
    </select>
    <DownArrow />
    <div v-if="isOpen" class="category-field-box d-block">
      <slot />
    </div>
  </div>
</template>

<script>
import DownArrow from "@/svgs/wallet/down-arrow";

export default {
  name: "BaseDropdown",
  components: {DownArrow},
  props: {
    selectedOption: {
      type: String,
      required: true
    },
    isOpen: {
      type: Boolean,
      default: false
    }

  },
  methods: {
    toggleDropdown() {
      this.$emit("onToggleDropdown", !this.isOpen);
    },
  },
};
</script>

<style scoped>

</style>
