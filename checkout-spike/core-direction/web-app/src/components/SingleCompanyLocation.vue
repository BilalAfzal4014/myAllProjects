<template>
  <div>
    <Signin />
    <SocialSharingOnSignup />
    <ForgetPassword />

    <header-component v-if="!isLogin" />
    <navbar v-if="isLogin" />
    <main id="main">
      <!-- activity-map-filter section -->
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
                       placeholder="Search by activity, brand location..."
                       type="text"
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
        </div>
      </section>

      <!-- activity-map section -->
      <section id="activity-map">
        <gmap-map
          :center="{lat:23.4241, lng:53.8478}"
          :options="mapOptions"
          :zoom="8"
          style="width: 100%; height: 100vh"
        >
          <gmap-marker
            v-for="(m, index) in markers"
            :key="index"
            :icon="{ url: require('../../public/assets/images/marker-icon.png')}"
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
  </div>
</template>

<script>
import ForgetPassword from "@/partials/ForgetPassword";
import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";
import Signin from "@/partials/modal/signin";
import HeaderComponent from "@/partials/header";
import Navbar from "@/partials/navbar";
import {Map} from "vue2-google-maps";
import BG from "../../public/assets/images/bg-01.jpg";
import * as toastr from "toastr";

export default {
    name: "SingleCompanyLocation",
    components: {
        ForgetPassword,
        SocialSharingOnSignup,
        Signin,
        HeaderComponent, Navbar, "gmap-map": Map
    },
    data() {
        return {
            isLogin: false,
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
        this.isLogin = (this.$store.getters.getStoreTokenGetters ? true : false);

        this.markers.push({
            position: {lat: +this.$route.query.lat, lng: +this.$route.query.lng},
        });

        this.swiper.slideTo(0, 1000, false);

    },

    methods: {
        showCategory(category) {
            let index = this.filterCategories.findIndex(function (cat) {
                return cat.id === category.id;
            });
            if (index !== -1) {
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
                    let companyData = response.data.filter(company => {
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
                    toastr.error(error[0].response.data.errors[0].error);
                });
            });
        },
        toggleInfoWindow: function (marker, idx) {
            this.infoWindowPos = marker.position;
            this.infoContent = this.getInfoWindowContent(marker);

            //check if its the same marker that was selected if yes toggle
            if (this.currentMidx == idx) {
                this.infoWinOpen = !this.infoWinOpen;
            }
            //if different marker set infowindow to open and reset current marker index
            else {
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
                              <div class="float-right">
                                  <div class="pl-7">
                                      <svg width="27" height="28" viewBox="0 0 27 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M13.7182 0.798828C6.42272 0.798828 0.503906 6.7409 0.503906 14.065C0.503906 21.3892 6.42272 27.3312 13.7182 27.3312C21.0137 27.3312 26.9325 21.3892 26.9325 14.065C26.9325 6.7409 21.0137 0.798828 13.7182 0.798828ZM19.9915 8.454C19.9585 8.57814 19.8926 8.68573 19.8348 8.80159C17.7328 13.0223 15.6307 17.2512 13.5286 21.4719C13.4956 21.5381 13.4626 21.6043 13.4297 21.6706C13.2978 21.9271 13.0999 22.0926 12.7949 22.0347C12.5146 21.985 12.3992 21.7781 12.3498 21.5216C12.086 20.0733 11.8222 18.625 11.5419 17.1768C11.3523 16.2002 11.5831 16.4402 10.6186 16.2499C9.21726 15.9768 7.81587 15.7285 6.41448 15.4637C6.1919 15.4223 5.93636 15.3975 5.82919 15.1492C5.70554 14.8843 5.77148 14.6443 5.98582 14.4623C6.09298 14.3795 6.21663 14.3133 6.34028 14.2554C10.5445 12.145 14.7486 10.0347 18.961 7.92435C19.1342 7.84159 19.2908 7.734 19.4969 7.74228C19.8513 7.74228 20.0986 8.08159 19.9915 8.454Z" fill="#690fad"></path>
                                      </svg>
                                  </div>
                                  <div class="distance-box flex mt-3">
                                      <div class="step mr-2">
                                          <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                              <path d="M9.33314 5.97737C9.25963 7.18892 9.02215 8.3612 8.43411 9.44934C8.20794 9.86441 8.12313 9.89806 7.67079 9.79149C7.13363 9.66248 6.59083 9.52787 6.05367 9.39886C5.90101 9.3596 5.77096 9.30351 5.72007 9.13524C5.23381 7.40767 5.21685 5.71375 6.16676 4.1208C6.6813 3.2514 7.60294 2.999 8.40019 3.44772C8.82425 3.6889 9.02215 4.09275 9.14089 4.53586C9.25398 5.00702 9.33314 5.48939 9.33314 5.97737Z" fill="black"></path>
                                              <path d="M4.05847 4.10848C4.01889 4.77595 3.97931 5.44903 3.78141 6.09967C3.71356 6.31842 3.60047 6.44182 3.36299 6.49791C2.79191 6.62131 2.22649 6.76714 1.66672 6.91298C1.45751 6.96907 1.3105 6.92419 1.18045 6.75593C0.965593 6.47548 0.818582 6.16698 0.705498 5.84166C0.219232 4.4955 -0.0578255 3.1269 0.3097 1.70222C0.479328 1.05158 0.784657 0.513113 1.51405 0.367279C2.23214 0.210227 2.79191 0.490677 3.21033 1.07962C3.70225 1.75831 3.9058 2.54357 4.00192 3.35687C4.03585 3.60367 4.09239 3.85047 4.05847 4.10848Z" fill="black"></path>
                                              <path d="M6.18389 12.9321C5.55627 12.9321 5.01912 12.2983 5.0587 11.513C5.08697 10.9409 5.29052 10.4249 5.58454 9.93689C5.66936 9.79666 5.76548 9.75179 5.92945 9.80227C6.51184 9.98176 7.09988 10.15 7.68792 10.3239C7.81232 10.3576 7.9254 10.4024 7.90278 10.5651C7.79535 11.3335 7.64834 12.0795 7.02072 12.6292C6.80586 12.8199 6.55707 12.9377 6.18389 12.9321Z" fill="black"></path>
                                              <path d="M4.39812 8.74233C4.39812 9.49954 3.75354 10.1334 3.02414 9.99874C2.53222 9.909 2.22124 9.57246 1.98376 9.163C1.72932 8.71989 1.62754 8.2263 1.54839 7.7271C1.52577 7.56444 1.571 7.46347 1.74063 7.41299C2.33432 7.24472 2.93367 7.07085 3.52171 6.88575C3.67438 6.84088 3.76485 6.86331 3.84966 6.99793C4.17195 7.50835 4.38116 8.05242 4.39812 8.74233Z" fill="black"></path>
                                          </svg>
                                      </div>
                                      <div class="text-xs distance">0.10 mile
                                      </div>
                                  </div>
                              </div>
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
            this.oldApi("get",
                this.constants.getUrl("getCategories"), true
            ).then((response) => {
                this.categories = response.data.map(category => {
                    category.image_url = this.constants.getImageUrl(`activity/${category.image_url}`);
                    return category;
                });
                this.categories.forEach((category) => {
                    let payload = {
                        "profile_cat_id": category.id
                    };
                    this.oldApi("post",
                        this.constants.getUrl("getCompany"),
                        payload, true
                    ).then((response) => {
                        let object = {};
                        let companyData = response.data.filter(company => {
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
                        toastr.error(error[0].response.data.errors[0].error);
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
#activity-map {
  height: 100vh;
}

.active {
  color: #FFFFFF;
  background-color: #690fad;
}

@media only screen and (max-width: 768px) {
  /*.business-user-profile-menu-tab-swiper-container {*/
  /*  position: relative;*/
  /*  left: 12%;*/
  /*  width:*/
  /*}*/
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
</style>