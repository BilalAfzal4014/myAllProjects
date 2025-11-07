<template>
  <h3 class="text-center">
    {{ message }}
  </h3>
</template>

<script>
export default {
    name: "DataNotFound",
    props:{
        message: {
            type:String,
            required:true,
        }
    }
};
</script>

<style scoped>

</style>