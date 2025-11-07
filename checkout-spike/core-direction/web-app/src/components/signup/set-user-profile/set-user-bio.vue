<template>
  <div class="bio-box">
    <p class="section-title">
      Add Bio
    </p>
    <div class="bio-field-box">
      <input class="bio-input-filed" placeholder="Tell us about yourself" type="text">
      <span class="counter">{{ bioWordsCount }}/350</span>
    </div>
  </div>
</template>

<script>
export default {
    name: "SetUserBio"
};
</script>

<style scoped>

</style>