<template>
  <div>
    <input ref="file" style="display: none" type="file">
    <button @click="$refs.file.click()">
      open file dialog
    </button>
  </div>
</template>

<script>

export default {
    name: "TestComponent",
    data() {
        return {
            file: null
        };
    }

};
</script>

<style scoped>

</style>