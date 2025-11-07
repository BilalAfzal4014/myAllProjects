<template>
  <div class="challenge-info-tab-box">
    <p class="title">
      Information About the Challenge
    </p>
    <p class="desc" v-html="challengeDetail.description" />
  </div>
</template>

<script>
export default {
    name: "ChallengeInfo",
    props: {
        challengeDetail: {
            type: Object,
            default: null
        },
    },
};
</script>

<style scoped>

</style>