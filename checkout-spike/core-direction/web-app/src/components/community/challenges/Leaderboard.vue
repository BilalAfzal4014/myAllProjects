<template>
  <div class="tabel">
    <div class="thead">
      <div class="tr">
        <p class="td heading">
          Ranking
        </p>
        <p class="td heading md:pl-2">
          Participant Name
        </p>
        <p class="td heading text-center sm-hide">
          Log Activity
        </p>
        <p class="td heading text-center sm-hide">
          Bookings
        </p>
        <p class="td heading text-center sm-hide">
          Steps
        </p>
        <p class="td heading text-center sm-hide">
          Heart Rate
        </p>
        <p class="td heading text-center sm-hide">
          On Demand
        </p>
        <p class="td heading text-center sm-hide">
          Core Points
        </p>
        <p class="td lg-hide" />
      </div>
    </div>

    <div class="tbody">
      <div v-for="participent in participents" :key="participent.id" class="tr" @click="setUser(participent.id)">
        <div class="td flex items-center">
          <div class="ranking-img-box rounded-full">
            <rank-icon />
          </div>
          <div class="ranking-info-box">
            <p class="ranking-title">
              Rank
            </p>
            <p class="ranking-number">
              {{ participent.rank }}
            </p>
          </div>
        </div>
        <div class="td flex items-center">
          <div class="ranker-img-box">
            <img :src="getImageUrl(participent.profile_picture)" alt=""
                 class="rounded-full"
            >
          </div>
          <div class="ranker-info-box">
            <p class="ranker-name">
              {{ participent.firstname }} {{ participent.lastname }}
            </p>
            <p class="ranker-core-points lg-hide">
              {{ participent.totalCorePoints ? participent.totalCorePoints : 0 }}
            </p>
          </div>
        </div>
        <p class="td points text-center sm-hide">
          {{ participent.totalActivityLogToday ? participent.totalActivityLogToday : 0 }}
        </p>
        <p class="td points text-center sm-hide">
          {{ participent.checkins ? participent.checkins : 0 }}
        </p>
        <p class="td points text-center sm-hide">
          {{ participent.stepCounts ? participent.stepCounts : 0 }}
        </p>
        <p class="td points text-center sm-hide">
          {{ participent.heartRate ? participent.heartRate : 0 }}
        </p>
        <p class="td points text-center sm-hide">
          {{ participent.totalWatchedVideoToday ? participent.totalWatchedVideoToday : 0 }}
        </p>
        <p class="td points text-center sm-hide">
          {{ participent.totalCorePoints ? participent.totalCorePoints : 0 }}
        </p>
        <div class="td lg-hide">
          <button class="btn-rank">
            <svg fill="none" height="16" viewBox="0 0 18 16" width="18"
                 xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M1.5 7C0.947715 7 0.5 7.44772 0.5 8C0.5 8.55228 0.947715 9 1.5 9L1.5 7ZM17.2071 8.70711C17.5976 8.31658 17.5976 7.68342 17.2071 7.29289L10.8431 0.928933C10.4526 0.538409 9.81946 0.538409 9.42893 0.928933C9.03841 1.31946 9.03841 1.95262 9.42893 2.34315L15.0858 8L9.42893 13.6569C9.03841 14.0474 9.03841 14.6805 9.42893 15.0711C9.81946 15.4616 10.4526 15.4616 10.8431 15.0711L17.2071 8.70711ZM1.5 9L16.5 9L16.5 7L1.5 7L1.5 9Z"
                fill="#690FAD"
              />
            </svg>
          </button>
        </div>
      </div>

      <search-friend-pagination v-if="participents.length" :count="$parent.total" :limit="data.limit"
                                :offset="data.offset"
                                @fetch-data="getParticipentsWithPagination"
      />

      <div v-if="user?.corePoints?.isChallengeJoined" class="active-user" @click="setUser(null)">
        <div class="custom-container">
          <div class="tr active">
            <div class="td flex items-center">
              <div class="ranking-img-box rounded-full">
                <svg fill="none" height="28" viewBox="0 0 29 28" width="29"
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
                  {{ user.rank }}
                </p>
              </div>
            </div>
            <div class="td flex items-center">
              <div class="ranker-img-box">
                <img :src="getImageUrl(user.user.profile_picture)" alt=""
                     class="rounded-full"
                >
              </div>
              <div class="ranker-info-box">
                <p class="ranker-name">
                  {{ user.user.firstname }} {{ user.user.lastname }}
                </p>
                <p class="ranker-core-points lg-hide">
                  {{ user.corePoints.totalCorePoints }}
                </p>
              </div>
            </div>
            <p class="td points text-center sm-hide">
              {{ user.corePoints.logActivityCorePoints ? user.corePoints.logActivityCorePoints : 0 }}
            </p>
            <p class="td points text-center sm-hide">
              {{ user.corePoints.checkinCorePoints ? user.corePoints.checkinCorePoints : 0 }}
            </p>
            <p class="td points text-center sm-hide">
              {{ user.corePoints.stepsCorePoints ? user.corePoints.stepsCorePoints : 0 }}
            </p>
            <p class="td points text-center sm-hide">
              {{ user.corePoints.heartRateCorePoints ? user.corePoints.heartRateCorePoints : 0 }}
            </p>
            <p class="td points text-center sm-hide">
              {{ user.corePoints.onDemandCorePoints ? user.corePoints.onDemandCorePoints : 0 }}
            </p>
            <div class="td flex items-center justify-between">
              <p class="points text-center sm-hide mx-auto">
                {{ user.corePoints.totalCorePoints ? user.corePoints.totalCorePoints : 0 }}
              </p>
              <div class="btn-redirect-box">
                <!-- add actie class to rotate the button -->
                <button class="btn-redirect rounded-full flex items-center justify-center">
                  <svg fill="none" height="9" viewBox="0 0 15 9" width="15" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M14.8844 1.38435C14.891 1.63792 14.7906 1.84351 14.6164 2.01484C12.4464 4.23528 10.283 6.45573 8.11297 8.66931C7.67761 9.11477 7.21547 9.10792 6.77342 8.66246C4.61006 6.45572 2.4534 4.24899 0.296739 2.0354C-0.0850308 1.64477 -0.0984262 1.13763 0.256552 0.72644C0.430693 0.527698 0.631624 0.342661 0.839253 0.17133C1.14735 -0.0753851 1.5693 -0.0479718 1.8774 0.21245C1.94438 0.267276 2.00465 0.335807 2.06493 0.397486C3.75276 2.11764 5.44058 3.83094 7.1351 5.5511C7.44989 5.8732 7.44989 5.8732 7.77808 5.54425C9.43241 3.8515 11.0867 2.15876 12.7411 0.472871C12.8683 0.349513 13.0023 0.23301 13.1496 0.130211C13.2769 0.0411199 13.4309 -5.87085e-07 13.5917 -5.94112e-07C14.1007 0.0137056 14.891 0.842943 14.8844 1.38435Z"
                      fill="white"
                    />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DefaultImage from "@/assets/images/default_profile_img.png";
import SearchFriendPagination from "@/partials/SearchFriendPagination";
import RankIcon from "@/svgs/rank-icon";

export default {
  name: "Leaderboard",
  components: {RankIcon, SearchFriendPagination},
  data() {
    return {
      data: {
        challengeId: this.$route.params.id,
        limit: 100,
        offset: 0
      },
    };
  },
  props: {
    participents: {
      type: Array,
      default: null
    },
    user: {
      type: Object,
      default: null
    }
  },
  methods: {
    setUser(id) {
      this.$emit("getUserGamificationData", id);
    },
    getImageUrl(imagePath) {
      return imagePath ? this.constants.getImageUrl(`${imagePath}?optimizer=image&format=webp&width=78&aspect_ratio=1:1&sharpen=true`) : DefaultImage;
    },
    getParticipentsWithPagination() {
      this.$parent.getParticipants(this.data);
    }
  }
};
</script>

<style scoped>

</style>