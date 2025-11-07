<template>
  <section id="banner" :style="{ backgroundImage: `url(${backgroundImage})` }">
    <div class="custom-container mx-auto">
      <div class="hero-content-box">
        <img src="assets/images/dfc-01.png" class="mx-auto" width="150px;" style="margin-bottom:15px;">
        <h3 class="hero-title">
          Join the challenge!
        </h3>
        <router-link to="/signup">
          <button class="hero-btn rounded-full block mx-auto">
            Create Your Profile
          </button>
        </router-link>
        <!-- <img :src=logo alt=""> -->
      </div>
    </div>
  </section>
</template>

<script>
export default {
    name: "BannnerImage",
    data() {
        return {
            logo: "/assets/images/hero_motive_latest.png",
            backgroundImage: "/assets/images/hero-banner.png",
        };
    },
};
</script>

<style scoped></style>
