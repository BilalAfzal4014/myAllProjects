<template>
  <!-- business-user-profile-menu-tab container -->
  <div
    class="swiper-container business-profile-menu-tab-swiper-container swiper-initialized swiper-horizontal swiper-pointer-events swiper-free-mode"
  >
    <!-- Additional required wrapper -->
    <swiper ref="mySwiper" :options="swiperOptions">
      <!-- Slides -->
      <swiper-slide
        aria-label="1 / 3"
        class="business-profile-menu-tab-list swiper-slide-active"
        role="group"
      >
        <button type="button" @click="onClickButton($event, 'info')">
          <div
            :class="{ active: activeTab === 'tab' }"
            class="business-profile-menu-tab-item rounded-full flex items-center"
          >
            <Info />
            Info
          </div>
        </button>
      </swiper-slide>
      <!-- Slides -->
      <swiper-slide
        aria-label="2 / 3"
        class="business-profile-menu-tab-list swiper-slide-next"
        role="group"
      >
        <button type="button" @click="onClickButton($event, 'activity')">
          <div
            :class="{ active: activeTab === 'tab2' }"
            class="business-profile-menu-tab-item rounded-full flex items-center"
          >
            <Activity />
            Activities
          </div>
        </button>
      </swiper-slide>
      <swiper-slide
        v-if="!isHideActivityTab"
        aria-label="2 / 3"
        class="business-profile-menu-tab-list swiper-slide-next"
        role="group"
      >
        <button type="button" @click="onClickButton($event, 'activity')">
          <div
            :class="{ active: activeTab === 'tab4' }"
            class="business-profile-menu-tab-item rounded-full flex items-center"
          >
            <Package />
            Packages
          </div>
        </button>
      </swiper-slide>
      <!-- Slides -->
      <swiper-slide
        aria-label="3 / 3"
        class="business-profile-menu-tab-list"
        role="group"
      >
        <button type="button" @click="onClickButton($event, 'gallery')">
          <div
            :class="{ active: activeTab === 'tab3' }"
            class="business-profile-menu-tab-item rounded-full flex items-center"
          >
            <Gallery />
            Gallery
          </div>
        </button>
      </swiper-slide>
    </swiper>
  </div>
</template>

<script>
import Info from "@/svgs/Info";
import Activity from "@/svgs/Acitivity";
import Gallery from "@/svgs/Gallery";
import Package from "@/svgs/Package";
import {Swiper, SwiperSlide} from "vue-awesome-swiper";

export default {
  name: "BusinessProfileTabs",
  props: {
    activeTab: {
      type: String,
      default: "",
    },
    isHideActivityTab: Boolean,
  },
  components: {Gallery, Activity, Package, Info, Swiper, SwiperSlide},
  data() {
    return {
      swiperOptions: {
        slidesPerView: "auto",
      },
    };
  },
  methods: {
    onClickButton(event, argument) {
      this.$emit("clicked", argument);
    },
  },
  computed: {
    swiper() {
      return this.$refs.mySwiper.$swiper;
    },
  },
  mounted() {
    this.swiper.slideTo(0, 1000, false);
  },
};
</script>

<style scoped></style>
