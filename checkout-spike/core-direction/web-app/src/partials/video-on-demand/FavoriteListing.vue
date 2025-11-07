<template>
  <section v-if="favoriteListing.length > 0" id="gyms-health" class="on-demand">
    <div class="custom-container">
      <div class="section-header flex items-center justify-between">
        <div class="title-box">
          <h4 class="section-title capitalize">
            Your Favourites
          </h4>
        </div>
        <div v-if="!$parent.showFavoriteTileView" class="see-all-btn-box">
          <button
            class="capitalize btn-see-all flex items-center"
            @click="$parent.showAllVideos = false; $parent.showFavoriteTileView = true"
          >
            View All
            <SeeAllArrow />
          </button>
        </div>
      </div>
    </div>
    <div v-if="!$parent.showFavoriteTileView" class="section-body">
      <div class="custom-container">
        <div class="content-container">
          <VideoCardListing :cards-detail="favoriteListing" :is-tile-view="$parent.showFavoriteTileView" />
        </div>
      </div>
    </div>

    <!--  Tile view -->
    <section v-else id="filter-items">
      <div class="custom-container">
        <div class="filter-items-outer-box">
          <VideoCardListing :cards-detail="favoriteListing" :is-tile-view="$parent.showFavoriteTileView" />
        </div>
      </div>
    </section>
    <!--  Tile view -->
  </section>
</template>

<script>
import SeeAllArrow from "@/svgs/video-on-demand/SeeAllArrow";
import VideoCardListing from "@/partials/video-on-demand/VideoCardListing";
import {getFavoriteVideoContent} from "@/apiManager/video-on-demand";
import * as toastr from "toastr";
import emitter from "tiny-emitter/instance";

export default {
    name: "FavoriteListing",
    components: {VideoCardListing, SeeAllArrow },
    data() {
        return {
            favoriteListing: [],
        };
    },
    created(){
        this.getFavoriteListing(this.getFavoriteListingPayload());
        emitter.on("fetch_favorites", () => {
            this.getFavoriteListing(this.getFavoriteListingPayload());
        });
    },
    methods: {
        getFavoriteListingPayload() {
            return {
                showAll: false
            };
        },
        getFavoriteListing(payload){
            getFavoriteVideoContent(payload)
                .then((response) => {
                    this.favoriteListing = response.data;
                })
                .catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });
        }
    }
};
</script>

<style scoped>
.on-demand .section-body .swiper-container {
  height: 435px !important;
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