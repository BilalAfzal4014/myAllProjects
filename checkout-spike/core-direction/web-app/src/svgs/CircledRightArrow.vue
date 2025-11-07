<template>
  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none"><circle cx="20" cy="20" r="20" fill="#690FAD" /><path d="M28 14L17 25L12 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
</template>

<script>
export default {
  name: "CircledRightArrow",
};
</script>

<style lang="scss" scoped>
	svg{
		width: 40px;
		height: 40px;
		object-fit: cover;
		object-position: center;
		@media (max-width:767px) {
			width: 32px;
			height: 32px;
		}
	}
</style>
