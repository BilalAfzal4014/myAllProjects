<template>
  <div class="welcome-message-box mx-auto mb-20">
    <div class="signup-form-container mx-auto">
      <p class="form-title">
        Hello!
      </p>
      <p class="form-title" style="font-weight: 400;">
        {{ firstName }} {{ lastName }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
    name: "HelloUsername",
    props:{
        firstName: {
            type: String,
            default: "",
        },
        lastName: {
            type: String,
            default: "",
        },
    }
};
</script>

<style scoped>

</style>