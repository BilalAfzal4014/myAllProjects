<template>
  <div class="business-type-filter-box">
    <div class="">
      <div class="flex items-center justify-between">
        <div class="title-box">
          <h4 class="activity-listing-title capitalize">
            Business Type
          </h4>
        </div>
      </div>
    </div>
    <div class="section-body">
      <div class="">
        <div class="content-container">
          <!-- Slider main container -->
          <div
            class="swiper-container mass-events-swiper-container swiper-initialized swiper-horizontal swiper-pointer-events swiper-free-mode"
          >
            <!-- Additional required wrapper -->
            <div aria-live="polite" class="swiper-wrapper">
              <!-- Slides -->
              <div v-for="category in businessCategories" :key="category.id"
                   aria-label="1 / 5" class="swiper-slide swiper-slide-active hidden md:block" role="group"
              >
                <button type="button" @click="toggleClass($event,category.id)">
                  <div class="btn-filter">
                    <div class="btn-filter-icon-box">
                      <img :src="category.image_url">
                    </div>
                    <p class="btn-filter-content-box">
                      {{ category.title }}
                    </p>
                  </div>
                </button>
              </div>
            </div>
            <div aria-live="polite" class="swiper-wrapper">
              <swiper ref="mySwiper" :options="swiperOptions" class="md:hidden sm:block">
                <!-- Slides -->
                <swiper-slide v-for="category in businessCategories" :key="category.id"
                              aria-label="1 / 5" class="swiper-slide swiper-slide-active" role="group"
                >
                  <button type="button" @click="toggleClass($event,category.id)">
                    <div class="btn-filter">
                      <div class="btn-filter-icon-box">
                        <img :src="category.image_url">
                      </div>
                      <p class="btn-filter-content-box">
                        {{ category.title }}
                      </p>
                    </div>
                  </button>
                </swiper-slide>
              </swiper>
            </div>
            <span aria-atomic="true" aria-live="assertive" class="swiper-notification" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as toastr from "toastr";
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import {getAllCategories} from "@/apiManager/company";

export default {
    name: "BusinessFilters",
    components: {Swiper, SwiperSlide},
    data() {
        return {
            businessCategories: [],
            selectedCategories: [],
            swiperOptions: {
                slidesPerView: "auto",
                centeredSlidesBounds: true

            }
        };
    },
    computed: {
        swiper() {
            return this.$refs.mySwiper.$swiper;
        }
    },
    mounted() {
        getAllCategories().then((response) => {
            this.businessCategories = response.map(category => {
                category.image_url = this.constants.getImageUrl(`activity/${category.image_url}`);
                return category;
            });
        }).catch((error) => {
            toastr.error(error[0].response.data.errors[0].error);
        });
    },
    methods: {
        toggleClass(e, id) {
            this.$emit("clicked", id);
        }
    }
};
</script>

<style scoped>

.business-type-filter-box{
  width: 100%;
}
.business-type-filter-box .swiper-wrapper{
  flex-wrap: wrap !important;
}
.business-type-filter-box .section-body .swiper-container {
  margin-right: 0;
  width: 100%;
}
.business-type-filter-box .btn-filter{
  margin-bottom: 15px;
}

</style>