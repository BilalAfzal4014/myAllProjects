<template>
  <div class="related-content-box">
    <p class="descriptive-title">
      Related Content
    </p>
    <div class="related-content-list">
      <div v-for="content in relatedContents" :key="content.id">
        <video-on-demand-card :card-details="content" />
      </div>
    </div>
  </div>
</template>

<script>
import VideoOnDemandCard from "@/components/video-on-demand/video-on-demand-card";

export default {
    name: "VideoSuggestionSection",
    components: {VideoOnDemandCard},
    props: {
        relatedContents: {
            type: Array,
            default: () => []
        }
    }
};
</script>

<style scoped>

</style>