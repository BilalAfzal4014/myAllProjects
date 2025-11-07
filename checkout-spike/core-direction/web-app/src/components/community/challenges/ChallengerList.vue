<template>
  <div id="challengers-modal" class="custom-modal m-auto">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="$parent.showCalendar = false">
              <svg width="36" height="36" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M30.7336 5.26685C23.7103 -1.75497 12.2885 -1.75497 5.2652 5.26685C-1.75507 12.2887 -1.75507 23.7135 5.2652 30.7353C8.77685 34.2455 13.3885 35.9997 18.0002 35.9997C22.6119 35.9997 27.222 34.2454 30.7336 30.7353C37.7555 23.7135 37.7555 12.2887 30.7336 5.26685ZM25.4271 23.3068C26.0139 23.8936 26.0139 24.842 25.4271 25.4288C25.1345 25.7214 24.7503 25.8685 24.3661 25.8685C23.982 25.8685 23.5977 25.7214 23.3051 25.4288L18.0001 20.1223L12.6966 25.4272C12.4025 25.7199 12.0183 25.867 11.6356 25.867C11.2515 25.867 10.8672 25.7199 10.5746 25.4272C9.98785 24.8405 9.98785 23.8905 10.5746 23.3053L15.8781 18.0003L10.5731 12.6953C9.98637 12.1086 9.98637 11.1586 10.5731 10.5734C11.1584 9.98661 12.1083 9.98661 12.6951 10.5734L18.0001 15.8783L23.305 10.5734C23.8918 9.98661 24.8402 9.98661 25.427 10.5734C26.0137 11.1586 26.0137 12.1086 25.427 12.6953L20.122 18.0003L25.4271 23.3068Z"
                />
              </svg>
            </div>
          </div>
          <div class="modal-body px-5">
            <div class="form-container mx-auto">
              <p class="challengers-modal-title">
                {{ challengeDetail.title }}
              </p>
              <p class="challengers-modal-subtitle">
                Core Points Challenge
              </p>
              <p class="challengers-modal-desc">
                <span class="start-date">Start Date on {{ setDateFormat(challengeDetail.start_date) }} </span>
                <span class="end-date">End Date on {{ setDateFormat(challengeDetail.end_date) }}</span>
              </p>
              <ul class="tbody challenger-list">
                <li v-for="(participent,index) in participents" :key="index" class="tr challenger-item">
                  <div class="td flex items-center">
                    <div class="ranker-img-box">
                      <img :src="getImageUrl(participent.profile_picture)" class="rounded-full"
                           alt=""
                      >
                    </div>
                    <div class="ranker-info-box">
                      <p class="ranker-name">
                        {{ participent.firstname }} {{ participent.lastname }}
                      </p>
                      <p class="ranker-core-points">
                        {{ participent.totalCorePoints ? participent.totalCorePoints : 0 }} Points
                      </p>
                    </div>
                  </div>
                  <div class="td flex items-center justify-end">
                    <div class="ranking-img-box rounded-full">
                      <svg width="29" height="28" viewBox="0 0 29 28" fill="none"
                           xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M26.6682 14.0002C26.6734 7.29024 21.2124 1.83403 14.5 1.83403C7.78742 1.83403 2.33177 7.29038 2.33177 14C2.33177 20.7097 7.79255 26.166 14.5 26.166C21.2125 26.166 26.6681 20.7097 26.6682 14.0002ZM26.6682 14.0002L26.4182 14H26.6682C26.6682 14.0001 26.6682 14.0001 26.6682 14.0002ZM14.5 27.75C6.91879 27.75 0.75 21.5845 0.75 14C0.75 6.41555 6.91878 0.25 14.5 0.25C22.0812 0.25 28.25 6.42056 28.25 14C28.25 21.5795 22.0862 27.75 14.5 27.75Z"
                          fill="#CAA8F5" stroke="#FFFFFA" stroke-width="0.5"
                        />
                        <path
                          d="M17.4531 19.5617H17.084V18.7742H11.916V19.5617H11.5469C11.3428 19.5617 11.1777 19.7268 11.1777 19.9308C11.1777 20.1348 11.3428 20.2999 11.5469 20.2999H17.4531C17.6572 20.2999 17.8223 20.1348 17.8223 19.9308C17.8223 19.7268 17.6572 19.5617 17.4531 19.5617Z"
                          fill="#CAA8F5"
                        />
                        <path
                          d="M14.5527 10.6607L14.5004 10.5562L14.4503 10.6564C14.3593 10.8436 14.1752 10.8583 14.1727 10.8608L14.0566 10.8781L14.1363 10.9566C14.2237 11.0399 14.2668 11.1655 14.2445 11.2883L14.2261 11.3997L14.3302 11.3456C14.4348 11.2912 14.5626 11.2895 14.6705 11.3456L14.7747 11.3997L14.7556 11.284C14.7358 11.1654 14.7751 11.0446 14.8605 10.9606L14.9441 10.8781L14.8281 10.8608C14.7091 10.8427 14.6064 10.7681 14.5527 10.6607Z"
                          fill="#CAA8F5"
                        />
                        <path
                          d="M20.4311 8.43823H18.5529C18.556 8.31548 18.5607 8.19401 18.5607 8.06909C18.5607 7.86506 18.3956 7.69995 18.1916 7.69995H10.8088C10.6048 7.69995 10.4396 7.86506 10.4396 8.06909C10.4396 8.19401 10.4444 8.31553 10.4475 8.43823H8.56934C8.3653 8.43823 8.2002 8.60334 8.2002 8.80737V9.74248C8.2002 11.8033 9.85075 13.4773 11.8851 13.5856C12.3093 14.1691 12.8145 14.6024 13.3863 14.8484C13.3024 16.5328 12.4087 17.6995 12.1196 18.0359H16.8802C16.5912 17.7018 15.6976 16.5421 15.6138 14.8484C16.1857 14.6024 16.6911 14.1692 17.1154 13.5856C19.1496 13.4773 20.8002 11.8033 20.8002 9.74248V8.80737C20.8002 8.60334 20.6351 8.43823 20.4311 8.43823ZM8.93848 9.74248V9.17651H10.4843C10.5944 10.5829 10.9144 11.8159 11.4034 12.7869C10.0094 12.473 8.93848 11.23 8.93848 9.74248ZM15.9883 10.8856L15.515 11.3517L15.6239 12.0071C15.6469 12.1451 15.59 12.2843 15.4768 12.3661C15.3632 12.4486 15.214 12.4596 15.0896 12.3953L14.5002 12.0893L13.9108 12.3953C13.7861 12.4588 13.6368 12.4483 13.5236 12.3661C13.4104 12.2843 13.3535 12.1451 13.3766 12.0071L13.4854 11.3517L13.0121 10.8856C12.9106 10.7855 12.8779 10.6379 12.9202 10.5085C12.9635 10.3759 13.0781 10.2785 13.2165 10.2576L13.8733 10.1588L14.17 9.56512C14.2947 9.31494 14.7057 9.31494 14.8304 9.56512L15.1271 10.1588L15.7839 10.2576C15.9223 10.2785 16.037 10.3759 16.0802 10.5085C16.1235 10.6415 16.0878 10.7875 15.9883 10.8856ZM20.0619 9.74248C20.0619 11.2299 18.991 12.473 17.597 12.7869C18.086 11.8159 18.406 10.583 18.5161 9.17651H20.0619V9.74248Z"
                          fill="#CAA8F5"
                        />
                      </svg>
                    </div>
                    <div class="ranking-info-box">
                      <p class="ranking-title">
                        Rank
                      </p>
                      <p class="ranking-number">
                        {{ participent.rank }}
                      </p>
                    </div>
                    <button class="btn-rank" @click="setUser(participent.id)">
                      <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                           xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M1.5 7C0.947715 7 0.5 7.44772 0.5 8C0.5 8.55228 0.947715 9 1.5 9L1.5 7ZM17.2071 8.70711C17.5976 8.31658 17.5976 7.68342 17.2071 7.29289L10.8431 0.928933C10.4526 0.538409 9.81946 0.538409 9.42893 0.928933C9.03841 1.31946 9.03841 1.95262 9.42893 2.34315L15.0858 8L9.42893 13.6569C9.03841 14.0474 9.03841 14.6805 9.42893 15.0711C9.81946 15.4616 10.4526 15.4616 10.8431 15.0711L17.2071 8.70711ZM1.5 9L16.5 9L16.5 7L1.5 7L1.5 9Z"
                          fill="#690FAD"
                        />
                      </svg>
                    </button>
                  </div>
                </li>
              </ul>
            </div>
            <ul class="tbody challenger-list-footer">
              <li class="tr challenger-item">
                <div class="td flex items-center">
                  <div class="ranker-img-box">
                    <img :src="getImageUrl(challengeDetail.user.profile_picture)" class="rounded-full"
                         alt=""
                    >
                  </div>
                  <div class="ranker-info-box">
                    <p class="ranker-name">
                      {{ challengeDetail.user.firstname }} {{ challengeDetail.user.lastname }}
                    </p>
                    <p class="ranker-core-points">
                      {{ challengeDetail.corePoints.totalCorePoints }} Points
                    </p>
                  </div>
                </div>
                <div class="td flex items-center justify-end">
                  <div class="ranking-img-box rounded-full">
                    <svg width="29" height="28" viewBox="0 0 29 28" fill="none"
                         xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M26.6682 14.0002C26.6734 7.29024 21.2124 1.83403 14.5 1.83403C7.78742 1.83403 2.33177 7.29038 2.33177 14C2.33177 20.7097 7.79255 26.166 14.5 26.166C21.2125 26.166 26.6681 20.7097 26.6682 14.0002ZM26.6682 14.0002L26.4182 14H26.6682C26.6682 14.0001 26.6682 14.0001 26.6682 14.0002ZM14.5 27.75C6.91879 27.75 0.75 21.5845 0.75 14C0.75 6.41555 6.91878 0.25 14.5 0.25C22.0812 0.25 28.25 6.42056 28.25 14C28.25 21.5795 22.0862 27.75 14.5 27.75Z"
                        fill="#CAA8F5" stroke="#FFFFFA" stroke-width="0.5"
                      />
                      <path
                        d="M17.4531 19.5617H17.084V18.7742H11.916V19.5617H11.5469C11.3428 19.5617 11.1777 19.7268 11.1777 19.9308C11.1777 20.1348 11.3428 20.2999 11.5469 20.2999H17.4531C17.6572 20.2999 17.8223 20.1348 17.8223 19.9308C17.8223 19.7268 17.6572 19.5617 17.4531 19.5617Z"
                        fill="#CAA8F5"
                      />
                      <path
                        d="M14.5527 10.6607L14.5004 10.5562L14.4503 10.6564C14.3593 10.8436 14.1752 10.8583 14.1727 10.8608L14.0566 10.8781L14.1363 10.9566C14.2237 11.0399 14.2668 11.1655 14.2445 11.2883L14.2261 11.3997L14.3302 11.3456C14.4348 11.2912 14.5626 11.2895 14.6705 11.3456L14.7747 11.3997L14.7556 11.284C14.7358 11.1654 14.7751 11.0446 14.8605 10.9606L14.9441 10.8781L14.8281 10.8608C14.7091 10.8427 14.6064 10.7681 14.5527 10.6607Z"
                        fill="#CAA8F5"
                      />
                      <path
                        d="M20.4311 8.43823H18.5529C18.556 8.31548 18.5607 8.19401 18.5607 8.06909C18.5607 7.86506 18.3956 7.69995 18.1916 7.69995H10.8088C10.6048 7.69995 10.4396 7.86506 10.4396 8.06909C10.4396 8.19401 10.4444 8.31553 10.4475 8.43823H8.56934C8.3653 8.43823 8.2002 8.60334 8.2002 8.80737V9.74248C8.2002 11.8033 9.85075 13.4773 11.8851 13.5856C12.3093 14.1691 12.8145 14.6024 13.3863 14.8484C13.3024 16.5328 12.4087 17.6995 12.1196 18.0359H16.8802C16.5912 17.7018 15.6976 16.5421 15.6138 14.8484C16.1857 14.6024 16.6911 14.1692 17.1154 13.5856C19.1496 13.4773 20.8002 11.8033 20.8002 9.74248V8.80737C20.8002 8.60334 20.6351 8.43823 20.4311 8.43823ZM8.93848 9.74248V9.17651H10.4843C10.5944 10.5829 10.9144 11.8159 11.4034 12.7869C10.0094 12.473 8.93848 11.23 8.93848 9.74248ZM15.9883 10.8856L15.515 11.3517L15.6239 12.0071C15.6469 12.1451 15.59 12.2843 15.4768 12.3661C15.3632 12.4486 15.214 12.4596 15.0896 12.3953L14.5002 12.0893L13.9108 12.3953C13.7861 12.4588 13.6368 12.4483 13.5236 12.3661C13.4104 12.2843 13.3535 12.1451 13.3766 12.0071L13.4854 11.3517L13.0121 10.8856C12.9106 10.7855 12.8779 10.6379 12.9202 10.5085C12.9635 10.3759 13.0781 10.2785 13.2165 10.2576L13.8733 10.1588L14.17 9.56512C14.2947 9.31494 14.7057 9.31494 14.8304 9.56512L15.1271 10.1588L15.7839 10.2576C15.9223 10.2785 16.037 10.3759 16.0802 10.5085C16.1235 10.6415 16.0878 10.7875 15.9883 10.8856ZM20.0619 9.74248C20.0619 11.2299 18.991 12.473 17.597 12.7869C18.086 11.8159 18.406 10.583 18.5161 9.17651H20.0619V9.74248Z"
                        fill="#CAA8F5"
                      />
                    </svg>
                  </div>
                  <div class="ranking-info-box">
                    <p class="ranking-title">
                      Rank
                    </p>
                    <p class="ranking-number">
                      {{ challengeDetail.rank }}
                    </p>
                  </div>
                  <button class="btn-rank" @click="setUser(null)">
                    <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                         xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M1.5 7C0.947715 7 0.5 7.44772 0.5 8C0.5 8.55228 0.947715 9 1.5 9L1.5 7ZM17.2071 8.70711C17.5976 8.31658 17.5976 7.68342 17.2071 7.29289L10.8431 0.928933C10.4526 0.538409 9.81946 0.538409 9.42893 0.928933C9.03841 1.31946 9.03841 1.95262 9.42893 2.34315L15.0858 8L9.42893 13.6569C9.03841 14.0474 9.03841 14.6805 9.42893 15.0711C9.81946 15.4616 10.4526 15.4616 10.8431 15.0711L17.2071 8.70711ZM1.5 9L16.5 9L16.5 7L1.5 7L1.5 9Z"
                        fill="#690FAD"
                      />
                    </svg>
                  </button>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment/moment";
import DefaultImage from "@/assets/images/default_profile_img.png";

export default {
    name: "ChallengerList",
    props : {
        challengeDetail : {
            type : Object,
            default : null
        },
        participents : {
            type : Array,
            default: null
        }
    },
    methods : {
        setDateFormat (date) {
            return moment(date).format("MMM DD YYYY");
        },
        setUser (id) {
            this.$parent.showChallengerList = false;
            this.$emit("getUserGamificationData", id);
        },
        getImageUrl(imagePath) {
            return imagePath ? this.constants.getImageUrl(imagePath) : DefaultImage;
        },
    }
};
</script>

<style scoped>
#challengers-modal .modal-header {
  padding-top: 13px;
  padding-right: 20px;
  padding-bottom: 0;
}
@media (max-width: 767px) {
  #challengers-modal .modal-header {
    padding-top: 17px;
    padding-right: 18px;
    position: absolute;
    right: 0;
  }
  #challengers-modal .modal-header svg {
    max-width: 28px;
  }
}
#challengers-modal .modal-header svg,
#challengers-modal .modal-header path {
  fill: #690FAD;
}
#challengers-modal .modal-outer-box {
  position: relative;
  max-width: 550px;
  background: #FFFFFF;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 21px;
}
@media (max-width: 767px) {
  #challengers-modal .modal-body.px-5 {
    padding-left: 15px;
    padding-right: 15px;
  }
}
#challengers-modal .form-container {
  width: 100%;
  max-width: 500px;
}
#challengers-modal .tr {
  width: 100%;
  display: grid;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  grid-template-columns: 2fr 1fr;
  margin-bottom: 10px;
}
@media screen and (max-width: 767px) {
  #challengers-modal .tr {
    grid-template-columns: 1fr 1fr;
  }
  #challengers-modal .tr .td.justify-end {
    -webkit-column-gap: 10px;
    column-gap: 10px;
  }
}
#challengers-modal .tbody {
  margin-top: 30px;
  margin-bottom: 17px;
}
@media (max-width: 767px) {
  #challengers-modal .tbody {
    margin-top: 15px;
    margin-bottom: 6px;
  }
}
#challengers-modal .tbody .tr {
  color: #000000;
  background-color: #FFFFFF;
  -webkit-box-shadow: 1px 3px 7px rgba(0, 0, 0, 0.15);
  box-shadow: 1px 3px 7px rgba(0, 0, 0, 0.15);
  border-radius: 11px;
}
@media screen and (min-width: 992px) {
  #challengers-modal .tbody .tr {
    padding: 15px 21px;
    height: 74px;
  }
}
@media screen and (max-width: 991px) {
  #challengers-modal .tbody .tr {
    padding: 13px 9px;
    height: 60px;
  }
}
#challengers-modal .tbody .tr:hover {
  color: #FFFFFF;
  background-color: #690FAD;
}
#challengers-modal .tbody .tr:hover .btn-rank svg,
#challengers-modal .tbody .tr:hover .btn-rank path {
  fill: #FFFFFA;
}
#challengers-modal .tbody .tr:hover .ranker-core-points {
  color: #FFFFFA;
}
#challengers-modal .tr.active {
  color: #FFFFFF;
  background-color: #690FAD;
}
#challengers-modal .tr.active .btn-rank svg,
#challengers-modal .tr.active .btn-rank path {
  fill: #FFFFFA;
}
#challengers-modal .ranking-info-box {
  width: 100%;
  max-width: 70px;
  margin-right: 15px;
}
@media (max-width: 767px) {
  #challengers-modal .ranking-info-box {
    max-width: -webkit-fit-content;
    max-width: -moz-fit-content;
    max-width: fit-content;
    margin-right: 0;
  }
}
#challengers-modal .ranking-img-box {
  margin-right: 15px;
  width: 100%;
  max-width: 40px;
}
#challengers-modal .ranking-img-box img,
#challengers-modal .ranking-img-box svg {
  width: 100%;
  max-width: 40px;
}
@media (max-width: 767px) {
  #challengers-modal .ranking-img-box {
    margin-right: 5px;
    max-width: 28px;
  }
  #challengers-modal .ranking-img-box img,
  #challengers-modal .ranking-img-box svg {
    max-width: 28px;
  }
}
@media (max-width: 389px) {
  #challengers-modal .ranking-img-box {
    margin-right: 0;
  }
}
#challengers-modal .ranking-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 500;
  line-height: 12px;
  letter-spacing: 0em;
  text-align: left;
}
#challengers-modal .ranking-number {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 700;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
}
@media (max-width: 767px) {
  #challengers-modal .ranking-number {
    font-size: 14px;
    line-height: 17px;
  }
}
@media (max-width: 389px) {
  #challengers-modal .ranking-number {
    font-size: 10px;
    line-height: 12px;
  }
}
#challengers-modal .ranker-img-box {
  margin-right: 20px;
  width: 100%;
  max-width: 44px;
}
#challengers-modal .ranker-img-box img,
#challengers-modal .ranker-img-box svg {
  width: 100%;
  max-width: 44px;
}
@media (max-width: 767px) {
  #challengers-modal .ranker-img-box {
    margin-right: 9px;
    max-width: 34px;
  }
  #challengers-modal .ranker-img-box img,
  #challengers-modal .ranker-img-box svg {
    max-width: 34px;
  }
}
#challengers-modal .ranker-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}
@media (max-width: 767px) {
  #challengers-modal .ranker-name {
    font-size: 12px;
    line-height: 15px;
  }
}
@media (max-width: 389px) {
  #challengers-modal .ranker-name {
    font-size: 10px;
  }
}
#challengers-modal .ranker-core-points {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  color: #690FAD;
}
@media (max-width: 767px) {
  #challengers-modal .ranker-core-points {
    font-size: 12px;
    line-height: 15px;
  }
}
@media (max-width: 389px) {
  #challengers-modal .ranker-core-points {
    font-size: 10px;
  }
}
@media (max-width: 319px) {
  #challengers-modal .ranker-core-points {
    display: none;
  }
}
#challengers-modal .challengers-modal-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 700;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 5px;
  color: #690FAD;
}
@media (max-width: 767px) {
  #challengers-modal .challengers-modal-title {
    padding-top: 30px;
    font-size: 16px;
    line-height: 20px;
  }
}
#challengers-modal .challengers-modal-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 5px;
}
@media (max-width: 767px) {
  #challengers-modal .challengers-modal-subtitle {
    font-size: 12px;
    line-height: 15px;
  }
}
#challengers-modal .challengers-modal-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
}
@media (max-width: 767px) {
  #challengers-modal .challengers-modal-desc {
    font-size: 10px;
    line-height: 12px;
  }
}
#challengers-modal .challengers-modal-desc .start-date {
  margin-right: 10px;
}
#challengers-modal .challenger-list {
  height: 100%;
  max-height: 512px;
  overflow-y: auto;
  padding-right: 10px;
  /* width */
  /* Track */
  /* Handle */
}
#challengers-modal .challenger-list::-webkit-scrollbar {
  width: 5px;
}
#challengers-modal .challenger-list::-webkit-scrollbar-track {
  background: #F1F1F1;
  border-radius: 11px;
}
#challengers-modal .challenger-list::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.45);
  border-radius: 11px;
}
#challengers-modal .challenger-list-footer {
  margin-top: 0;
  margin-bottom: 0;
  margin-left: -1.25rem;
  margin-right: -1.25rem;
}
#challengers-modal .challenger-list-footer .tr {
  color: #FFFFFA;
  background: #690FAD;
  margin-bottom: 0;
  border-radius: 0px 0px 21px 21px;
  padding-left: 45px;
  padding-right: 60px;
}
@media screen and (max-width: 767px) {
  #challengers-modal .challenger-list-footer .tr {
    padding-left: 33px;
    padding-right: 44px;
  }
}
@media screen and (max-width: 389px) {
  #challengers-modal .challenger-list-footer .tr {
    padding-left: 20px;
    padding-right: 20px;
  }
}
#challengers-modal .challenger-list-footer p {
  color: #FFFFFA;
}
#challengers-modal .challenger-list-footer .btn-rank svg,
#challengers-modal .challenger-list-footer .btn-rank path {
  fill: #F2F5EA;
}
@media (max-width: 389px) {
  #challengers-modal .btn-modal-close {
    width: 28px;
  }
}
</style>