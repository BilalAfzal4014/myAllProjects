<template>
  <p class="addition-info-title">
    Great {{ username }}! Now Let’s create your profile
  </p>
</template>

<script>
export default {
    name: "UserProfileHeader",
    props: {
        username: {
            type: String,
            required: true
        }
    }
};
</script>

<style scoped>

</style>