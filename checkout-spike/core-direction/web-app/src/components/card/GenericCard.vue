<template>
  <div class="card challenge-card relative" @click="navigateToDetail">
    <div class="card-body">
      <slot name="card-banner" />
      <slot name="card-info" />
    </div>
  </div>
</template>

<script>
export default {
  name: "GenericCard",
  props: ["slug"],
  methods: {
    getImageUrl(path) {
      return path;
    },
    navigateToDetail() {
      this.$emit("navigateToDetail");
    },
  }
};
</script>
