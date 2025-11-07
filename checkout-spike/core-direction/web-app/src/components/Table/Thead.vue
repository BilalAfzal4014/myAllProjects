<template>
  <div class="wallet-table-header">
    <p v-if="title" class="wallet-selected-tab-heading">
      {{ title }}
    </p>
    <ul class="wallet-table-heading-list" :style="gridStyle">
      <slot />
    </ul>
  </div>
</template>

<script>
export default {
  name: "Thead",
  props: {
    title: {
      type: String,
      default: ""
    },
    columns: {
      type: Number,
      default: 5
    }
  },
  computed: {
    gridStyle() {
      return {
        gridTemplateColumns: `repeat(${this.columns}, 1fr)`
      };
    }
  }
};
</script>

<style lang="scss" scoped>
  .wallet-table-heading-list {
    display: grid;
    width: 100%;
  }
</style>