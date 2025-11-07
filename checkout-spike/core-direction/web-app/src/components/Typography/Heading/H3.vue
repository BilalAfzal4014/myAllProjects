<template>
  <h3>
    <slot />
  </h3>
</template>

<script>
export default {
  name: "H3",
};
</script>

<style lang="scss" scoped>
  h3 {
    color: $cd-black;
    font-size: 3.5rem;
  }
</style>
