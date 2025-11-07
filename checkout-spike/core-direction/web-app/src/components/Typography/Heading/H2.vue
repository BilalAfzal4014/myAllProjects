<template>
  <h2>
    <slot />
  </h2>
</template>

<script>
export default {
  name: "H2",
};
</script>

<style lang="scss" scoped>
  h2 {
    color: $cd-black;
    font-size: 4.2rem;
  }
</style>
