<template>
  <button :class="btnClass" @click="handleClick">
    {{ text }}
  </button>
</template>

<script>
export default {
    name: "CustomButton",
    props: ["btnClass", "text", "action"],
    methods: {
        handleClick() {
            this.$emit("click");
        }
    },
};
</script>

<style scoped>

</style>