<template>
  <p>
    <slot />
  </p>
</template>

<script>
export default {
  name: "Error",
};
</script>

<style lang="scss" scoped>
  p {
    color: $danger;
    font-size: 1.4rem;
    margin: 1rem 0;
  }
</style>
