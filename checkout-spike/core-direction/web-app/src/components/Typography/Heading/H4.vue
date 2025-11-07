<template>
  <h4>
    <slot />
  </h4>
</template>

<script>
export default {
  name: "H4",
};
</script>

<style lang="scss" scoped>
  h4 {
    color: $cd-black;
    font-size: 2.8rem;
  }
</style>
