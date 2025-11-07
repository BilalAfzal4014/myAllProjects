<template>
  <div class="grid grid-cols-2 gap-4">
    <button class="btn-cancel rounded-full mb-5 ml-auto" @click="$router.go(-1)">
      Cancel
    </button>
    <button class="btn-update-profile bg-gradient rounded-full mb-5 mr-5" @click="$emit('updateProfile')">
      Update Profile
    </button>
  </div>
</template>

<script>
export default {
    name: "ActionButton"
};
</script>

<style scoped>

</style>