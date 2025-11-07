<template>
  <li
    class="category-item"
    @click="selectOption"
  >
    {{ option.name }}
  </li>
</template>

<script>
export default {
  name: "DropdownOptions",
  props: {
    option: {
      type: Object,
      required: true,
    },
  },
  methods: {
    selectOption() {
      this.$emit("selectOption", this.option);
    },
  },
};
</script>

<style scoped>

</style>