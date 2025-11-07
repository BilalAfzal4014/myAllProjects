<template>
  <section id="friends">
    <div class="custom-container">
      <div class="section-header flex items-center justify-between">
        <div class="title-box">
          <h4 class="section-title capitalize">
            Following
          </h4>
        </div>
        <div class="see-all-btn-box">
          <a
            class="flex items-center capitalize text-vibrantDark btn-see-all"
            href="community/friend"
          >Explore All
            <blue-arrow-icon />
          </a>
        </div>
      </div>

      <swiper-component :data="friendListing">
        <swiper-slide>
          <community-add-friend />
        </swiper-slide>
        <template #default="{ slideData }">
          <div v-if="slideData.isAddFriendComponent">
            <community-add-friend />
          </div>
          <router-link v-else :to="`/profile/${slideData.username}`">
            <button class="btn-friend-card">
              <img
                :src="`${getFriendImageUrl(slideData.profile_picture)}`"
                :alt="`Profile image of ${slideData.firstname} ${slideData.lastname}`"
                class="friend-img"
                height="100"
                width="100"
              >
              <span class="friend-name">
                {{ slideData.firstname }} {{ slideData.lastname }}
              </span>
            </button>
          </router-link>
        </template>
      </swiper-component>
    </div>
  </section>
</template>

<script>
import BlueArrowIcon from "@/svgs/blue-arrow-icon";
import constants from "@/constants/constants";
import DefaultImage from "../../assets/images/default_profile_img.png";
import {getMyFollowings} from "@/apiManager/user";
import * as toastr from "toastr";
import CommunityAddFriend from "@/components/community/friends/community-add-friend";
import SwiperComponent from "@/components/swiper-component";
import {SwiperSlide} from "vue-awesome-swiper";

import "swiper/css/swiper.css";

export default {
  name: "ExploreFriends",
  components: {SwiperComponent, CommunityAddFriend, BlueArrowIcon, SwiperSlide},
  data() {
    return {
      friendListing: [],
    };
  },
  mounted() {
    this.fetchMyFollowings();
  },
  methods: {
    getFriendImageUrl(imageUrl) {
      return imageUrl ? constants.getImageUrl(imageUrl) + "?optimizer=image&format=webp&width=200&aspect_ratio=1:1&sharpen=true" : DefaultImage;
    },
    fetchMyFollowings() {
      getMyFollowings()
        .then((response) => {
          this.friendListing = [
            {
              isAddFriendComponent: true,
            },
            ...response.data,
          ];
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
  },
};
</script>

<style scoped>

#friends {
  margin-top: 90px;
  margin-bottom: 70px;
}

@media screen and (max-width: 767px) {
  #friends {
    margin-top: 65px;
    margin-bottom: 70px;
  }
}

#friends .section-header {
  margin-bottom: 35px;
}

@media screen and (max-width: 767px) {
  #friends::v-deep .swiper-wrapper {
    width: calc(100% + 1rem);
    margin-right: -1rem !important;
  }
}

#friends::v-deep .swiper-slide {
  width: -webkit-max-content !important;
  width: -moz-max-content !important;
  width: max-content !important;
}

@media screen and (max-width: 767px) {
  #friends::v-deep .swiper-slide {
    margin-right: 19px;
  }
}

@media screen and (min-width: 768px) {
  #friends::v-deep .swiper-slide {
    margin-right: 30px;
  }
}

@media screen and (min-width: 768px) {
  #friends::v-deep .swiper-slide:last-child {
    margin-right: 125px !important;
  }
}

#friends .btn-friend-card {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  height: 139px;
  padding: 0px 5px 5px;
}

#friends .friend-img {
  border-radius: 50%;
  width: 100px;
  height: 100px;
  margin-left: auto;
  margin-right: auto;
  -o-object-fit: fill;
  object-fit: fill;
  -o-object-position: center;
  object-position: center;
}

#friends .friend-name {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
}

#friends .swiper-button-next,
#friends .swiper-button-prev {
  -webkit-box-shadow: 0px 4px 4px 0px #00000040;
  box-shadow: 0px 4px 4px 0px #00000040;
  width: 42px;
  height: 42px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  background-color: #ffffff;
}

#friends .swiper-button-next::after,
#friends .swiper-button-prev::after {
  font-size: 18px;
  font-weight: 900;
  color: #000000;
}

@media screen and (max-width: 767px) {
  #friends .swiper-button-next,
  #friends .swiper-button-prev {
    display: none;
  }
}

@media (min-width: 1220px) and (max-width: 1920px) {
  #friends .swiper-container {
    overflow: visible;
  }
}

</style>
