<template>
  <main id="main">
    <section id="activity-map-filter">
      <div class="custom-container">
        <div class="activity-search-box">
          <div class="search-field-container flex items-center">
            <button class="btn-back" type="button" @click="$router.push('/listing')">
              <div class="btn-back-icon flex items-center justify-center rounded-full">
                <svg fill="none" height="15" viewBox="0 0 9 15" width="9" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M7.61565 14.8844C7.36208 14.891 7.15649 14.7906 6.98516 14.6164C4.76472 12.4464 2.54427 10.283 0.330687 8.11297C-0.114772 7.67761 -0.107918 7.21547 0.337541 6.77342C2.54427 4.61006 4.75101 2.4534 6.9646 0.296739C7.35523 -0.0850305 7.86237 -0.0984259 8.27356 0.256553C8.4723 0.430693 8.65734 0.631624 8.82867 0.839253C9.07539 1.14735 9.04797 1.5693 8.78755 1.8774C8.73272 1.94438 8.66419 2.00465 8.60251 2.06493C6.88236 3.75276 5.16905 5.44058 3.4489 7.1351C3.1268 7.44989 3.1268 7.44989 3.45575 7.77808C5.1485 9.43242 6.84124 11.0868 8.52713 12.7411C8.65049 12.8683 8.76699 13.0023 8.86979 13.1496C8.95888 13.2769 9 13.4309 9 13.5917C8.98629 14.1007 8.15706 14.891 7.61565 14.8844Z"
                    fill="black"
                  />
                </svg>
              </div>
            </button>
            <div class="input-field-search-box rounded-full">
              <input v-model="keyword" class="input-field-search rounded-full"
                     placeholder="Search Activity, Provider Name . ." type="text"
              >
              <svg fill="none" height="14" viewBox="0 0 14 14" width="14" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M15.6539 14.7259L11.0786 10.1507C11.9464 9.07931 12.4688 7.7173 12.4688 6.2344C12.4688 2.79677 9.672 0 6.23437 0C2.79674 0 0 2.79677 0 6.2344C0 9.67203 2.79677 12.4688 6.2344 12.4688C7.7173 12.4688 9.07931 11.9464 10.1507 11.0786L14.7259 15.6539C14.8541 15.782 15.0618 15.782 15.19 15.6539L15.6539 15.19C15.782 15.0618 15.782 14.854 15.6539 14.7259ZM6.2344 11.1563C3.52032 11.1563 1.31251 8.94848 1.31251 6.2344C1.31251 3.52032 3.52032 1.31251 6.2344 1.31251C8.94848 1.31251 11.1563 3.52032 11.1563 6.2344C11.1563 8.94848 8.94848 11.1563 6.2344 11.1563Z"
                  fill="black"
                />
              </svg>
            </div>
          </div>
          <button class="btn-map-activity-search rounded-full" @click="searchByKeyword">
            Search
          </button>
        </div>

        <div class="business-profile-menu-tab-swiper-container">
          <!-- Additional required wrapper -->
          <swiper ref="mySwiper" :options="swiperOptions">
            <!-- Slides -->
            <swiper-slide v-for="category in categories"
                          :key="category.id" class="filter-by-category-list swiper-slide business-profile-menu-tab-list"
            >
              <button class="location-category-button" type="button" @click="showCategory(category)">
                <div
                  :class="`filter-by-category-item flex ${checkIfCategoryExistInFilterCategory(category).found ? 'category-active': ''}`"
                >
                  <span class="filter-by-category-icon">
                    <img :src="category.image_url">
                  </span>
                  <span class="filter-by-category-content">{{ category.title }}</span>
                </div>
              </button>
            </swiper-slide>
          </swiper>
        </div>
      </div>
    </section>

    <!-- activity-map section -->
    <section id="activity-map">
      <gmap-map
        :center="{lat:25.2048, lng:55.2708}"
        :options="mapOptions"
        :zoom="12"
        style="width: 100%; height: 100vh"
      >
        <gmap-marker
          v-for="(m, index) in markers"
          :key="index"
          :icon="m.myLocation ? 'https://maps.google.com/mapfiles/ms/icons/red-dot.png' : { url: require('../../public/assets/images/Pin-Location-icon.png')}"
          :option="MarkerOptions"
          :position="m.position"
          @click="toggleInfoWindow(m, index)"
        />
        <gmap-info-window
          :opened="infoWinOpen"
          :options="infoOptions"
          :position="infoWindowPos"
          @closeclick="infoWinOpen=false"
        >
          <div v-html="infoContent" />
        </gmap-info-window>
      </gmap-map>
    </section>
  </main>
</template>

<script>
import * as toastr from "toastr";
import {Map} from "vue2-google-maps";
import BG from "../../public/assets/images/bg-01.jpg";
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import {updateMetaInformation} from "@/utils";

export default {
  name: "Location",
  components: {
    "gmap-map": Map, Swiper,
    SwiperSlide
  },
  data() {
    return {
      categories: [],
      companies: [],
      company: [],
      filterCategories: [],
      image: BG,
      center: {lat: 23.4241, lng: 53.8478},
      mapOptions: {
        fullscreenControl: false,
        zoomControl: true,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        disableDefaultUi: false,
      },
      map: null,
      infoContent: "",
      infoWindowPos: {
        lat: 0,
        lng: 0
      },
      infoWinOpen: false,
      currentMidx: null,
      fullscreenControl: false,
      infoOptions: {
        pixelOffset: {
          width: 0,
          height: -35
        }
      },
      MarkerOptions: {
        zIndex: 999999,
        opacity: 0.2

      },
      markers: [],
      keyword: "",
      swiperOptions: {
        slidesPerView: "auto",
      }
    };
  },
  computed: {
    swiper() {
      return this.$refs.mySwiper.$swiper;
    }
  },
  mounted() {

    navigator.geolocation.getCurrentPosition(
      position => {
        this.markers.push({
          position: {lat: position.coords.latitude, lng: position.coords.longitude},
          myLocation: true
        });
      },
      error => {
        toastr.error(error.message);
      },
    );

    updateMetaInformation("Partner Location Map | Core Direction", "", "Browse our interactive map of partner businesses", "Partner Location Map | Core Direction", "Browse our interactive map of partner businesses", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/location");
    this.oldApi("get",
      this.constants.getUrl("getCategories"),
    ).then((response) => {
      this.categories = response.data.map(category => {
        category.image_url = this.constants.getImageUrl(`activity/${category.image_url}`);
        return category;
      });
      this.filterCategories = [...this.categories];
      this.categories.forEach((category) => {
        let payload = {
          "profile_cat_id": category.id
        };
        this.oldApi("post",
          this.constants.getUrl("getCompany"),
          payload
        ).then((response) => {
          let object = {};
          let companyData = response.data.companies.filter(company => {
            return company.latitude && company.longitude;
          });
          companyData.forEach((comp) => {
            object["company"] = comp;
            this.companies.push(comp);
            this.markers.push({
              position: {lat: +comp.latitude, lng: +comp.longitude},
              company: comp
            });
          });
        }).catch((error) => {
          toastr.error(error.message);
        });
      });

    }).catch((error) => {
      toastr.error(error.message);
    });
    this.swiper.slideTo(0, 1000, false);

  },

  methods: {
    checkIfCategoryExistInFilterCategory(category) {
      let index = this.filterCategories.findIndex(function (cat) {
        return cat.id === category.id;
      });
      return {
        index,
        found: index !== -1 ? true : false
      };
    },
    showCategory(category) {
      const {index, found} = this.checkIfCategoryExistInFilterCategory(category);
      if (found) {
        this.markers = [];
        this.filterCategories.splice(index, 1);
      } else {
        this.filterCategories.push({id: category.id});
      }

      this.filterCategories.forEach((category) => {
        let payload = {
          "profile_cat_id": category.id
        };
        this.oldApi("post",
          this.constants.getUrl("getCompany"),
          payload, true
        ).then((response) => {
          let object = {};
          let companyData = response.data.companies.filter(company => {
            return company.latitude && company.longitude;
          });
          companyData.forEach((comp) => {
            object["company"] = comp;
            this.markers.push({
              position: {lat: +comp.latitude, lng: +comp.longitude},
              company: comp
            });
          });
        }).catch((error) => {
          toastr.error(error.message);
        });
      });
    },
    toggleInfoWindow: function (marker, idx) {
      this.infoWindowPos = marker.position;
      this.infoContent = this.getInfoWindowContent(marker);
      if (this.currentMidx === idx) {
        this.infoWinOpen = !this.infoWinOpen;
      } else {
        this.infoWinOpen = true;
        this.currentMidx = idx;
      }
    },
    getInfoWindowContent: function (marker) {
      this.company = [];
      var categoryImages = "";

      this.company = marker.company;
      if (!this.company.company_banner.includes("https://")) {
        this.company.company_banner = this.constants.getImageUrl(`member/${this.company.company_banner}`);
        this.company.company_logo = this.constants.getImageUrl(`member/${this.company.company_logo}`);
        this.company.categories.map(category => {
          category.category_image = this.constants.getImageUrl(`activity/${category.category_image}`);
          return category;
        });

      }
      this.company.categories.forEach((category) => {
        categoryImages += `<img src="${category.category_image}" style="max-width:25px;" class="mr-3">
          `;
      });


      return (

        `<div class="section-body">
 <div class="card relative">
             <a href="/booking/${this.company.id}?lat=${this.company.latitude}&lng=${this.company.longitude}">
                  <div class="card-body">
                      <img src="${this.company.company_banner}" alt="" class="card-img rounded-t-lg object-cover overflow-hidden">
                      <div class="gym-logo rounded-full absolute top-20 left-6">
                          <img src="${this.company.company_logo}"  alt="" class="rounded-full h-16 w-16">
                      </div>
                      <div class="carf-info-box  flex justify-between px-4 pt-8 pb-4 rounded-b-lg">
                          <div class="gym-info w-full">
                              <h3 class="float-left card-title">${this.company.company_name}</h3>
                              <div class="clear-both"></div>
                               <div class="flex justify-start">${categoryImages}</div> <div class="flex mt-4 items-center">
                                  <svg class="mr-2 location-icon" width="15" height="20" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M5.29953 0.624512C2.7249 0.624512 0.630859 2.72678 0.630859 5.31153C0.630859 6.16278 1.01191 7.07606 1.02564 7.11741C1.14922 7.41035 1.38952 7.86527 1.56459 8.13064L4.76744 12.9969C4.89789 13.1968 5.09356 13.3105 5.29953 13.3105C5.50551 13.3105 5.70118 13.1968 5.83163 12.9969L9.03447 8.12719C9.20955 7.86182 9.44985 7.4069 9.57343 7.11397C9.5906 7.07606 9.96821 6.15933 9.96821 5.30808C9.96821 2.72678 7.87417 0.624512 5.29953 0.624512ZM5.29953 7.79289C3.93669 7.79289 2.82788 6.67973 2.82788 5.31153C2.82788 3.94334 3.93669 2.83017 5.29953 2.83017C6.66237 2.83017 7.77119 3.94334 7.77119 5.31153C7.77119 6.67973 6.66237 7.79289 5.29953 7.79289Z" fill="black"></path>
                                  </svg>
                                  <p class="text-xs location capitalize">${this.company.address}.</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </a>
          </div>
</div>
           `);
    },
    searchByKeyword() {
      if (this.keyword.length === 0) {
        toastr.error("Keyword should not be empty.");
        return;
      }

      let payload = {
        "profile_cat_id": "",
        "lat": "",
        "lng": "",
        "keyword": this.keyword
      };
      this.oldApi("post",
        this.constants.getUrl("getCompany"),
        payload, true
      ).then((response) => {
        let object = {};
        let companyData = response.data.companies.filter(company => {
          return company.latitude && company.longitude;
        });
        this.markers = [];
        companyData.forEach((comp) => {
          object["company"] = comp;
          this.companies.push(comp);
          this.markers.push({
            position: {lat: +comp.latitude, lng: +comp.longitude},
            company: comp
          });
        });
      }).catch((error) => {
        toastr.error(error[0].response.data.errors[0].error);
      });


    },
  }

};
</script>

<style scoped>
#activity-map-filter {
  background: rgba(6, 7, 14, 0.25);
  padding-top: 19px;
  padding-bottom: 36px;
}

@media screen and (max-width: 767px) {
  #activity-map-filter {
    padding-top: 10px;
    padding-bottom: 21px;
  }
}

#activity-map {
  height: 100vh;
}

.active {
  color: #FFFFFF;
  background-color: #690fad;
}


.activity-search-box,
.business-profile-menu-tab-swiper-container {
  width: 100%;
  max-width: 1185px;
  margin-left: auto;
  margin-right: auto;
}

#activity-map-filter .custom-container .activity-search-box .input-field-search-box .input-field-search {
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  padding: 12px 16px;
  padding-right: 40px;
}

.gm-style-iw.gm-style-iw-c {
  padding: 0;
}

.gm-style-iw-d {
  padding: 0 !important;
  overflow: hidden !important;
  background: transparent;
  border-radius: 0.5rem;
}

.gm-style-iw-d .section-body {
  margin-top: 0 !important;
}

.gm-style .gm-style-iw-d {
  overflow: hidden !important;
}

.gm-style .gm-style-iw {
  overflow: hidden !important;
  padding: 0 !important;
}

.gm-style .gm-style-iw .section-body {
  margin-top: 0 !important;
}

.card .card-body .float-left.card-title {
  margin-bottom: 12px;
}
</style>