<template>
  <div class="business-profile-info-container">
    <p class="challenge-title">
      {{ challengeData.title }}
    </p>
    <p class="challenge-subtitle">
      {{ challengeData.type }}
    </p>
  </div>
</template>

<script>
export default {
  name: "ChallengeInformation",
  props: {
    challengeData: {
      type: Object,
      required: true
    }
  },
};
</script>

