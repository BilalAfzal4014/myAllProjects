<template>
  <div class="parent-popup">
    <slot />
  </div>
</template>

<script>
export default {
  name: "Popup",
};
</script>

<style lang="scss" scoped>
  .parent-popup {
		position: relative;
	}
</style>
