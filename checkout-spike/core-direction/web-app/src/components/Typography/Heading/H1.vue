<template>
  <h1>
    <slot />
  </h1>
</template>

<script>
export default {
  name: "H1",
};
</script>

<style lang="scss" scoped>
  h1 {
    color: $cd-black;
    font-size: 4.9rem;
  }
</style>
