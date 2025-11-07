<template>
  <div class="card relative" @click="navigateToCompanyDetail(companySlug)">
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
              :class="`btn-fav-box ${ isAlwaysFavourite ? 'active cursor-default' : company.is_fvt ? 'active': '' }`"
              @click.stop.prevent="toggleFavourite(company.id, company.is_fvt, isAlwaysFavourite)"
      >
        <span :class="`${ company.is_fvt || isAlwaysFavourite ? 'span-circle active-fav' : 'span-circle'}`">
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
            sharpen: 'true'
          }"
          :src="`member/${company.company_logo}`"
          :useSrcset="false"
          alt="Company logo"
          class="rounded-full h-16 w-16"
          type="logo"
        />
      </div>
      <div
        class="card-info-box  flex justify-between px-4 pt-6 pb-4 rounded-b-lg"
      >
        <div class="gym-info w-full">
          <div class="grid grid-cols-8 gap-1 mb-2">
            <h3 class="float-left card-title col-span-6">
              {{ company.company_name }}
            </h3>
            <div class="float-right col-span-2">
              <div class="pl-7">
                <svg
                  class="mx-auto"
                  fill="none"
                  height="28"
                  viewBox="0 0 27 28"
                  width="27"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M13.7182 0.798828C6.42272 0.798828 0.503906 6.7409 0.503906 14.065C0.503906 21.3892 6.42272 27.3312 13.7182 27.3312C21.0137 27.3312 26.9325 21.3892 26.9325 14.065C26.9325 6.7409 21.0137 0.798828 13.7182 0.798828ZM19.9915 8.454C19.9585 8.57814 19.8926 8.68573 19.8348 8.80159C17.7328 13.0223 15.6307 17.2512 13.5286 21.4719C13.4956 21.5381 13.4626 21.6043 13.4297 21.6706C13.2978 21.9271 13.0999 22.0926 12.7949 22.0347C12.5146 21.985 12.3992 21.7781 12.3498 21.5216C12.086 20.0733 11.8222 18.625 11.5419 17.1768C11.3523 16.2002 11.5831 16.4402 10.6186 16.2499C9.21726 15.9768 7.81587 15.7285 6.41448 15.4637C6.1919 15.4223 5.93636 15.3975 5.82919 15.1492C5.70554 14.8843 5.77148 14.6443 5.98582 14.4623C6.09298 14.3795 6.21663 14.3133 6.34028 14.2554C10.5445 12.145 14.7486 10.0347 18.961 7.92435C19.1342 7.84159 19.2908 7.734 19.4969 7.74228C19.8513 7.74228 20.0986 8.08159 19.9915 8.454Z"
                    fill="#690FAD"
                  />
                </svg>
              </div>
            </div>
          </div>
          <div class="clear-both" />
          <div v-if="company.categories" class="flex card-activity-icons-list mt-1">
            <div
              v-for="(category, index) in company.categories"
              :key="`company-icons-${index}`"
              class="card-activity-icons-item"
            >
              <PreviewImage
                :additionalParams="{
                  optimizer: 'image',
                  format: 'webp',
                  width: '72px',
                  aspect_ratio: null,
                  sharpen: 'true'
                }"
                :src="`activity/${category.category_image}`"
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
              {{ company.address }}.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CardLocationIcon from "@/svgs/CardLocationIcon";
import FavoriteIcon from "@/svgs/favorite-icon";
import {mapGetters} from "vuex";
import PreviewImage from "@/components/PreviewImage";
import toggleFavouriteMixin from "@/mixin/toggleFavouriteMixin";

export default {
  components: {PreviewImage, FavoriteIcon, CardLocationIcon},
  mixins: [toggleFavouriteMixin],
  props: {
    company: {
      type: Object,
      required: true
    },
    isAlwaysFavourite: {
      type: Boolean,
      required: false,
      default: false,
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
    }),
    companySlug() {
      return this.company.slug ?? this.company.company_slug;
    }
  },
  name: "Card",
  methods: {
    navigateToCompanyDetail(slug) {
      this.$router.push(`/booking/${slug}`);
    },
  }
};
</script>

<style scoped>
h3.float-left.card-title.col-span-6 {
  margin-top: 3px;
  width: 100%;
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
