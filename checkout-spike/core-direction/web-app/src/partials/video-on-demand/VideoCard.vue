<template>
  <div>
    <div class="on-demand-card relative">
      <div class="card-body">
        <a href="#">
          <img
            :src="`${getImageUrl(cardDetails.thumbnail)}?optimizer=image&format=webp&width=680&aspect_ratio=16:9&sharpen=true`"
            alt=""
            class="card-img object-cover overflow-hidden"
          >
        </a>
        <a class="gym-logo-box rounded-full" href="#">
          <img
            :src="`${videoCardIcon}`"
            alt=""
            class="gym-logo"
          >
        </a>
        <div class="card-info-box flex justify-between">
          <div class="gym-info w-full">
            <div class="grid grid-cols-8 gap-1">
              <div class="col-span-6">
                <a class="on-demand-title" href="#">{{ cardDetails.title }}</a>
                <p class="on-demand-video-info flex items-center">
                  <span class="instructors">{{ cardDetails.presenter.firstname }} {{ cardDetails.presenter.lastname }}</span>
                  <!--                  TODO: Implement the count once implemented on backend-->
                  <!--                  <span class="instructor-counter rounded-full flex items-center justify-center" />-->
                  <span class="divider"> | </span>
                  <strong>{{ cardDetails.duration.name }}</strong>
                </p>
              </div>
              <a class="category-box col-span-2 flex flex-col items-center justify-center" href="#">
                <div class="on-demand-icon-box rounded-full">
                  <img :src="`${getImageUrl(cardDetails.category.icon)}`" alt="" class="category-img">
                </div>
                <p class="on-demand-category">{{ cardDetails.category.name }}</p>
              </a>
            </div>
            <p class="required-title">
              Equipment Required
            </p>
            <div class="requirement-box flex items-center">
              <p v-for="equipment in cardDetails.content_equipment" :key="`equipment-item-${equipment.equipment.code}`" class="required-tag rounded-full">
                {{ equipment.equipment.name }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DefaultImage from "../../assets/images/default_profile_img.png";
import VideoIcon from "../../assets/images/btn-play.svg";
import PodcastIcon from "../../assets/images/headphone.svg";

export default {
    name: "VideoCard",
    props: {
        cardDetails: {
            type: Object,
            default: null
        }
    },
    data() {
        return{
            videoCardIcon: this.cardDetails.content_type.code === "VIDEO" ? VideoIcon : PodcastIcon
        };
    },
    methods: {
        getImageUrl(imagePath) {
            return imagePath ? this.constants.getImageUrl(imagePath) : DefaultImage;
        }
    }
};
</script>

<style scoped>
.on-demand .on-demand-card {
  min-width: 340px;
  margin-right: 25px;
}
.on-demand-card {
  height: -webkit-fit-content;
  height: -moz-fit-content;
  height: fit-content;
  display: inline-block;
  min-height: 375px;
  width: 100%;
  max-width: 340px;
  transition: all .3s ease-in-out;
  -webkit-transition: all .3s ease-in-out;
  -moz-transition: all .3s ease-in-out;
  -ms-transition: all .3s ease-in-out;
  -o-transition: all .3s ease-in-out;
}
.on-demand-card:hover {
  transform: scale(1.03);
  -webkit-transform: scale(1.03);
  -moz-transform: scale(1.03);
  -ms-transform: scale(1.03);
  -o-transform: scale(1.03);
}
.on-demand-card .card-body {
  -webkit-box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 11px;
  overflow: hidden;
}
.on-demand-card .card-body .btn-fav-box {
  position: absolute;
  right: 15px;
  top: 15px;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  z-index: 1;
}
@media (max-width: 991px) {
  .on-demand-card .card-body .btn-fav-box {
    right: 13px;
    top: 14px;
    width: 32px;
    height: 32px;
  }
}
.on-demand-card .card-body .btn-fav-box .fav {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  background: #690FAD;
  -webkit-box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
}
.on-demand-card .card-body .btn-fav-box .fav svg,
.on-demand-card .card-body .btn-fav-box .fav path {
  fill: #F2F5EA;
}
.on-demand-card .card-body .card-img {
  width: 100%;
  height: 184px;
  border-radius: 11px 11px 0 0;
}
.on-demand-card .card-body .card-info-box {
  padding-left: 18px;
  padding-right: 18px;
  padding-bottom: 21px;
  padding-top: 39px;
  background-color: #FFFFFF;
  border-radius: 0 0 11px 11px;
  -webkit-border-radius: 0 0 11px 11px;
  -moz-border-radius: 0 0 11px 11px;
  -ms-border-radius: 0 0 11px 11px;
  -o-border-radius: 0 0 11px 11px;
}
.on-demand-card .card-body .grid.grid-cols-8.gap-1 {
  margin: 0;
  height: 100%;
  max-height: 54px;
  margin-bottom: 21px;
}
.on-demand-card .card-body .gym-logo-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 60px;
  height: 60px;
  background: #690FAD;
  -webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.25);
  position: absolute;
  top: 148px;
  left: 18px;
  padding: 13px;
}
@media screen and (max-width: 800px){
  #filter-items .filter-items-inner-box .gym-logo-box {
    top: 112px;
  }
}
.on-demand-card .card-body .on-demand-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 0;
  text-align: left;
  color: #000000;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  width: 100%;
  padding-top: 5px;
  margin: 0 0 10px !important;
  width: -webkit-fit-content;
  width: -moz-fit-content;
  width: fit-content;
  max-width: -webkit-fill-available;
}
.on-demand-card .card-body .on-demand-video-info {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0;
  text-align: left;
}
.on-demand-card .card-body .on-demand-video-info strong {
  min-width: -webkit-fit-content;
  min-width: -moz-fit-content;
  min-width: fit-content;
  font-weight: 700;
}
.on-demand-card .card-body .on-demand-video-info .instructor-counter {
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 500;
  line-height: 12px;
  letter-spacing: 0;
  text-align: left;
  margin-left: 2px;
  margin-right: 10px;
  color: #FFFFFA;
  background-color: #690FAD;
  min-width: 16px;
  min-height: 16px;
  max-width: 16px;
  max-height: 16px;
}
.on-demand-card .card-body .on-demand-video-info .instructors {
  width: 100%;
  max-width: 129px;
  text-align: left;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}
.on-demand-card .card-body .category-box {
  width: -webkit-fit-content;
  width: -moz-fit-content;
  width: fit-content;
  margin-left: auto;
}
.on-demand-card .card-body .on-demand-icon-box {
  width: 26px;
  height: 26px;
  background: #690FAD;
  margin-bottom: 7px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}
.on-demand-card .card-body .on-demand-category {
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 400;
  line-height: 12px;
  letter-spacing: 0;
  text-align: center;
}
@media (max-width: 389px) {
  .on-demand-card .card-body .on-demand-category {
    font-weight: 500;
  }
}
.on-demand-card .card-body .required-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 700;
  line-height: 12px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 10px;
}
.on-demand-card .card-body .required-tag {
  background: #690FAD;
  color: #F2F5EA;
  padding: 5px 10px;
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 600;
  line-height: 12px;
  letter-spacing: 0;
  text-align: center;
  width: -webkit-fit-content;
  width: -moz-fit-content;
  width: fit-content;
  max-width: -webkit-max-content;
  max-width: -moz-max-content;
  max-width: max-content;
  text-transform: capitalize;
}
.on-demand-card .requirement-box {
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}
span.divider {
  margin-right: 3px;
}
.category-img {
  border-radius: 50%;
  height: 100%;
}
</style>
