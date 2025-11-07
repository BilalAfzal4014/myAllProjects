<template>
  <section id="gyms-health" class="on-demand">
    <div class="custom-container">
      <div class="section-header flex items-center justify-between">
        <div class="title-box">
          <h4 class="section-title capitalize">
            {{ title }}
          </h4>
        </div>
        <div v-if="showSeeAll" class="see-all-btn-box">
          <button class="capitalize btn-see-all flex items-center"
                  @click="selectedCategory.id ? setSubCategory(content[0].sub_category) : setCategory({name : content[0].category.name, id: content[0].category.id});"
          >
            View All
            <SeeAllArrow />
          </button>
        </div>
      </div>
    </div>
    <div v-if="!showTileView" class="section-body">
      <div class="custom-container">
        <div class="content-container">
          <VideoCardListing :cards-detail="content" :is-tile-view="showTileView" />
        </div>
      </div>
    </div>

    <!--  Tile view -->
    <section v-else id="filter-items">
      <div class="custom-container">
        <div class="filter-items-outer-box">
          <VideoCardListing :cards-detail="content" :is-tile-view="showTileView" />
        </div>
      </div>
    </section>
    <!--  Tile view -->
  </section>
</template>

<script>
import Swiper from "swiper";
import VideoCardListing from "@/partials/video-on-demand/VideoCardListing";
import SeeAllArrow from "@/svgs/video-on-demand/SeeAllArrow";

export default {
    name: "VideoListing",
    components: {SeeAllArrow, VideoCardListing},
    mounted() {
        this.swiperOptions = new Swiper(".gyms-health-swiper-container", {
            slidesPerView: "auto",
            freeMode: true,
        });
        if ((Object.keys(this.selectedCategory).length !== 0 && this.selectedCategory.id !== null) && this.numberOfSubCategories <= 1) {
            this.showSeeAll = false;
            this.showTileView = true;
        }
    },
    props: {
        content: {
            type: Array,
            default: null
        },
        title: {
            type: String,
            default: null
        },
        selectedCategory: {
            type: Object,
            default: null
        },
        selectedSubCategories: {
            type: Array,
            default: null
        },
        numberOfSubCategories: {
            type: Number,
            default: 0
        }
    },
    data() {
        return {
            showSeeAll: true,
            showTileView: false
        };
    },
    watch: {
        numberOfSubCategories() {
            if (this.numberOfSubCategories <= 1) {
                this.showSeeAll = false;
                this.showTileView = true;
            }
        }
    },
    methods: {
        setCategory(value) {
            this.$emit("setCategory", value);
            this.showSeeAll = true;
        },
        setSubCategory(value) {
            this.$emit("seeAllSubCategory", value);
            this.showSeeAll = false;
            this.showTileView = true;
        },
    }

};
</script>

<style scoped>
.on-demand .section-body {
  margin-top: 32px;
}

.on-demand .section-header .section-title {
    padding: 8px;
    font-weight: 600;
    line-height: 29px;
}

.on-demand .section-header .btn-see-all {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: right;
}

.on-demand .section-header .btn-see-all svg {
  margin-left: 10px;
}

.on-demand .on-demand-card {
  min-width: 340px;
  margin-right: 25px;
}

@media (max-width: 767px) {
  .on-demand .on-demand-card {
    min-width: 300px;
    max-width: 300px;
    margin-right: 20px;
  }
}
</style>