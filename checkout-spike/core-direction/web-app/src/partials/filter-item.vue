<template>
  <div :class="`filter-items-inner-box ${isLayoutTypeThree ? 'three-columns': ''}`">
    <div v-for="company in companies" :key="company.id" class="card relative">
      <router-link
        :to="{
          name: 'Booking',
          params: { slug: company.company_slug },
        }"
      >
        <div class="card-body">
          <PreviewImage
            :additionalParams="{
              optimizer: 'image',
              format: 'webp',
              width: '680',
              aspect_ratio: '16:9',
              sharpen: 'true'
            }"
            :src="`member/${company.company_banner}`"
            :useSrcset="false"
            alt="company-profile-image"
            class="card-img rounded-t-lg object-cover overflow-hidden"
            type="thumbnail"
          />
          <button v-if="showFav"
                  :class="`btn-fav-box ${ company.is_fvt ? 'active': '' }`"
                  @click.stop.prevent="addToFavorite(company.id, company.is_fvt); company.is_fvt = !company.is_fvt"
          >
            <span
              :class="`${ company.is_fvt || $route.path === '/favourite-companies' ? 'span-circle active-fav' : 'span-circle'}`"
            >
              <favorite-icon />
            </span>
          </button>
          <div class="gym-logo rounded-full absolute top-20 left-6">
            <PreviewImage
              :additionalParams="{
                optimizer: 'image',
                format: 'webp',
                width: '140',
                aspect_ratio: '1:1',
                sharpen: true
              }"
              :src="`member/${company.company_logo}`"
              :useSrcset="false"
              alt="Company logo"
              class="rounded-full h-16 w-16"
              type="logo"
            />
          </div>
          <div
            class="card-info-box  flex justify-between px-4 pt-8 pb-4 rounded-b-lg"
          >
            <div class="gym-info w-full">
              <div class="card-title-box">
                <h3 class="float-left card-title" style="width:100%">
                  {{ company.company_name }}
                </h3>
                <div class="float-right">
                  <div class="pl-7">
                    <svg
                      fill="none"
                      height="28"
                      viewBox="0 0 27 28"
                      width="27"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M13.7729 0.798828C6.47741 0.798828 0.558594 6.7409 0.558594 14.065C0.558594 21.3892 6.47741 27.3312 13.7729 27.3312C21.0684 27.3312 26.9872 21.3892 26.9872 14.065C26.9872 6.7409 21.0684 0.798828 13.7729 0.798828ZM20.0462 8.454C20.0132 8.57814 19.9472 8.68573 19.8895 8.80159C17.7874 13.0223 15.6854 17.2512 13.5833 21.4719C13.5503 21.5381 13.5173 21.6043 13.4844 21.6706C13.3525 21.9271 13.1546 22.0926 12.8496 22.0347C12.5693 21.985 12.4539 21.7781 12.4045 21.5216C12.1407 20.0733 11.8769 18.625 11.5966 17.1768C11.407 16.2002 11.6378 16.4402 10.6733 16.2499C9.27194 15.9768 7.87055 15.7285 6.46916 15.4637C6.24659 15.4223 5.99104 15.3975 5.88388 15.1492C5.76023 14.8843 5.82617 14.6443 6.0405 14.4623C6.14767 14.3795 6.27132 14.3133 6.39497 14.2554C10.5991 12.145 14.8033 10.0347 19.0157 7.92435C19.1888 7.84159 19.3455 7.734 19.5516 7.74228C19.906 7.74228 20.1533 8.08159 20.0462 8.454Z"
                        fill="#690FAD"
                      />
                    </svg>
                  </div>
                  <div v-if="company.distance" class="distance-box flex items-center mt-3">
                    <div class="step mr-2">
                      <map-icon />
                    </div>
                    <div class="text-xs distance">
                      {{ company.distance.toFixed(1) }} Miles
                    </div>
                  </div>
                </div>
              </div>

              <div class="clear-both" />

              <div class="flex card-activity-icons-list activity-icon-box">
                <div
                  v-for="(category,index) in company.categories"
                  :key="'category'+index"
                  class="card-activity-icons-item"
                >
                  <PreviewImage
                    :additionalParams="{
                      optimizer: 'image',
                      format: 'webp',
                      width: '72px',
                      aspect_ratio: null,
                      sharpen: true
                    }"
                    :src=" `activity/${category.category_image}`"
                    :useSrcset="false"
                    alt="activity-category"
                    class=""
                    type="logo"
                    width="18"
                    height="18"
                  />
                </div>
              </div>
              <div class="flex mt-4 items-center">
                <CardLocationIcon />
                <p class="text-xs location capitalize">
                  {{ company.address }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </router-link>
    </div>
  </div>
</template>

<script>
import CardLocationIcon from "@/svgs/CardLocationIcon";
import FavoriteIcon from "@/svgs/favorite-icon";
import {mapGetters} from "vuex";
import MapIcon from "@/svgs/MapIcon";
import PreviewImage from "@/components/PreviewImage";
import toggleFavouriteMixin from "@/mixin/toggleFavouriteMixin";

export default {
  name: "FilterItem",
  components: {PreviewImage, MapIcon, CardLocationIcon, FavoriteIcon},
  mixins: [toggleFavouriteMixin],
  props: {
    companies: {
      type: Array,
      required: true,
      default: (() => [])
    },
    isLayoutTypeThree: {
      type: Boolean,
      required: true,
      default: false
    },
    showFav: {
      type: Boolean,
      required: false,
      default: true
    }
  },
  computed: {
    ...mapGetters({
      authToken: "getStoreTokenGetters",
      companiesList: "getCompaniesList"
    })
  },
};
</script>

<style scoped>
.filter-items-inner-box {
  grid-template-rows: 1fr;
}
.filter-items-inner-box .card,
.filter-items-inner-box .card .card-body{
  height: 100%;
}
.filter-items-inner-box .card>a{
  height: 100%;
  display: block;
}
.span-circle {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  background: #FFFFFF;
  -webkit-box-shadow: 0px 4px 4px rgb(0 0 0 / 25%);
  box-shadow: 0px 4px 4px rgb(0 0 0 / 25%);
  cursor: pointer;
}

</style>
