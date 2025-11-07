<template>
  <div :class="isTileView ? '' : 'swiper-container gyms-health-swiper-container'">
    <div :class="isTileView ? 'filter-items-inner-box three-columns' : 'swiper-wrapper'">
      <div v-for="(cardDetail, index) in cardsDetail" :key="`${index}-video-card-${cardDetail.id}`"
           class="swiper-slide"
      >
        <video-on-demand-card :card-details="cardDetail" />
      </div>
    </div>
  </div>
</template>

<script>
import VideoOnDemandCard from "@/components/video-on-demand/video-on-demand-card";

export default {
    name: "VideoCardListing",
    components: {VideoOnDemandCard},
    props: {
        cardsDetail: {
            type: Array,
            default: null
        },
        isTileView: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            selectedCard: {},
            showVideoPopUp: false,
        };
    },
    methods: {
        setShowVideoPop(value) {
            this.showVideoPopUp = value;
        },
        setSelectedCard(value) {
            this.selectedCard = value;
            this.setShowVideoPop(true);
        }
    }
};
</script>

<style scoped>
.gyms-health-swiper-container {
  z-index: auto;
}
</style>