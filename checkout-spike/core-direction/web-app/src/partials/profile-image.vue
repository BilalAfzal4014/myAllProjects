<template>
  <!--  <img src="/assets/images/user-profile.jpeg" class="rounded-full" alt="">-->
  <img :src="path"
       class="rounded-full"
  >
</template>

<script>
export default {
    name: "ProfileImage",
    props: {
        path: {
            type: String,
            required: true
        }
    },

};
</script>

<style scoped>

</style>