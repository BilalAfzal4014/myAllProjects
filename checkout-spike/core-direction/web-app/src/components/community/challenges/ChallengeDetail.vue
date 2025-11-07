<template>
  <section id="challenges">
    <div class="challenges-banner-box">
      <div class="custom-container">
        <div class="challenges-banner-inner-box">
          <PreviewImage :src="challengeData.cover_photo" alt="Challenge cover photo" type="cover" />
        </div>
      </div>
    </div>
    <div class="custom-container">
      <div class="business-profile-info-box mx-auto">
        <div class="business-logo-container rounded-full">
          <PreviewImage :src="challengeData.logo" alt="Challenge Logo" class="business-logo rounded-full" type="logo" />
        </div>
        <div class="business-profile-info-outer-box flex items-start flex-wrap">
          <challenge-information :challenge-data="challengeData" />
          <div class="challenges-btn-box">
            <button v-if="challengeData?.corePoints?.isChallengeJoined"
                    class="btn-joined rounded-full flex items-center"
                    @click="showLeaveBtn = !showLeaveBtn"
            >
              <blue-chevron-down-icon />
              Joined
            </button>
            <button v-else class="btn-joined rounded-full flex items-center"
                    @click="challengeData?.isLoggedIn ? leaveChallenge(true) : showSignInModal()"
            >
              Join Challenge
            </button>
            <button v-show="showLeaveBtn" class="btn-leave flex items-center justify-between"
                    @click="showLeaveModal = true"
            >
              Leave Challenge
              <blue-exist-icon />
            </button>
          </div>
        </div>
        <div class="business-profile-info-inner-box flex items-center flex-wrap">
          <p class="tag rounded-full flex items-center">
            <img alt="test" src="/assets/images/challenges/icon-01.svg">
            {{ duration }} Day Challenge
          </p>
          <div class="tag rounded-full flex items-center">
            <img alt="" src="/assets/images/challenges/icon-02.svg">
            <span class="date-from">
              {{ dateFormat(challengeData.start_date) }}
            </span>
            <span class="date-divider"> - </span>
            <span class="date-to">
              {{ dateFormat(challengeData.end_date) }}
            </span>
            <span class="challenge-started">
              {{
                dateDifference
              }}
              / {{ totalDateDifference }}</span>
          </div>
          <p class="tag rounded-full flex items-center">
            <img alt="" src="/assets/images/challenges/icon-03.svg">
            Participants:
            <span class="participants">
              {{ challengeData.numberOfParticipent }}</span>
          </p>
        </div>
      </div>

      <div class="challenges-tab-list-box flex items-center justify-between">
        <challenge-tabs-list :show-info="showInfo" :tabs="tabs" @updateShowInfo="showInfo = $event" />
        <go-back :gotoUrl="challengeData?.company_slug ? `/booking/${challengeData?.company_slug}` : '/listing'" />
      </div>
    </div>
    <div class="custom-container">
      <div v-if="!showInfo" class="challenge-leaderboards-box">
        <leaderboard :participents="participents" :user="challengeData"
                     @getUserGamificationData="getUserGamificationData"
        />
      </div>
      <challenge-info v-else :challenge-detail="challengeData" />
    </div>
    <challenger-list v-if="showChallengerList && showCalendar" :challenge-detail="challengeData"
                     :participents="participents" @getUserGamificationData="getUserGamificationData"
    />
    <challenger-calendar v-else-if="showCalendar && !showChallengerList" :challenge-detail="challengeData"
                         :gamification-data="gamificationData" :selected-user-data="selectedUserData"
                         @updateUserObject="event => updateUserObject(event)"
    />
    <leave-challenge-modal v-if="showLeaveModal" @leaveChallenge="leaveChallenge" />
    <challenge-leave-success v-if="showLeaveSuccess" />
  </section>
</template>

<script>
import Emitter from "tiny-emitter/instance";
import Leaderboard from "@/components/community/challenges/Leaderboard";
import ChallengeInfo from "@/components/community/challenges/ChallengeInfo";
import {
  getChallengeById,
  getChallengeParticipents,
  getUserGamification,
  leaveChallenge,
} from "@/apiManager/gamification";
import * as toastr from "toastr";
import moment from "moment/moment";
import ChallengerList from "@/components/community/challenges/ChallengerList";
import ChallengerCalendar from "@/components/community/challenges/ChallengerCalendar";
import LeaveChallengeModal from "@/components/community/challenges/LeaveChallengeModal";
import ChallengeLeaveSuccess from "@/components/community/challenges/ChallengeLeaveSuccess";
import BlueChevronDownIcon from "@/svgs/arrows/blue-chevron-down-icon";
import BlueExistIcon from "@/svgs/arrows/blue-exist-icon";
import ChallengeInformation from "@/components/challenges/challenge-information";
import ChallengeTabsList from "@/components/challenges/challenge-tabs-list";
import GoBack from "@/partials/back-button";
import PreviewImage from "@/components/PreviewImage";
import {updateMetaInformation} from "@/utils";

export default {
  name: "Header",
  components: {
    PreviewImage,
    GoBack,
    ChallengeTabsList,
    ChallengeInformation,
    BlueExistIcon,
    BlueChevronDownIcon,
    Leaderboard,
    ChallengeInfo,
    ChallengerList,
    ChallengerCalendar,
    LeaveChallengeModal,
    ChallengeLeaveSuccess,
  },
  data() {
    return {
      showInfo: false,
      challengeData: {},
      total: 0,
      data: {
        challengeId: this.$route.params.id,
        limit: 100,
        offset: 0,
      },
      participents: [],
      showChallengerList: false,
      showCalendar: false,
      gamificationData: [],
      showLeaveBtn: false,
      showLeaveModal: false,
      showLeaveSuccess: false,
      duration: 0,
      selectedUserData: {},
      tabs: [
        {label: "Challenge Info", value: true},
        {label: "Leaderboards", value: false},
      ],
      pageTitle: "",
    };
  },
  async mounted() {
    const isChallengeDataAvailable = await this.getChallengeData();

    if (isChallengeDataAvailable) {
      this.getParticipants(this.data);
      this.updateMetaData();
    }
  },
  watch: {
    "challengeData.title": "updateMetaData",
  },
  computed: {
    user_date() {
      return moment().format("YYYY-MM-DD");
    },
    dateDifference() {
      let startDate = moment(this.challengeData.start_date.split("T")[0], "YYYY-MM-DD");
      let endDate = moment(this.challengeData.end_date.split("T")[0], "YYYY-MM-DD");
      let userDate = moment(this.user_date, "YYYY-MM-DD");

      if (endDate.isBefore(userDate)) {
        let diff = endDate.diff(startDate, "days") + 1;
        return diff > 0 ? diff : 0;
      } else {
        let diff = userDate.diff(startDate, "days") + 1;
        return diff > 0 ? diff : 0;
      }
    },
    totalDateDifference() {

      let startDate = moment(this.challengeData.start_date.split("T")[0], "YYYY-MM-DD");
      let endDate = moment(this.challengeData.end_date.split("T")[0], "YYYY-MM-DD");

      let diff = endDate.diff(startDate, "days") + 1;

      return diff;

    }
  },
  methods: {
    async getChallengeData() {
      try {
        let data = {dataId: this.$route.params.id};
        let response = await getChallengeById(data);

        if (!response.data) {
          toastr.error(response.message);
          this.$router.push("/");
          return false;
        }

        this.challengeData = response.data;
        this.getDatesDifference(
          this.challengeData.start_date,
          this.challengeData.end_date
        );

        return true;
      } catch (error) {
        this.handleError(error);
        return false;
      }
    },

    getParticipants(data) {
      getChallengeParticipents(data)
        .then((response) => {
          this.participents = response.data.participents;
          this.total = response.data.totalCount;
        })
        .catch((error) => {
          this.handleError(error);
        });
    },
    dateFormat(date) {
      const momentDate = moment.parseZone(date);
      return momentDate.format("DD/MM/YYYY");
    },
    getUserGamificationData(id) {
      if (id !== null) {
        this.selectedUserData = JSON.parse(JSON.stringify(this.participents.find(
          (participent) => participent.id === id
        )));
      } else {
        this.selectedUserData = {
          ...this.selectedUserData,
          ...this.challengeData.user,
          rank: this.challengeData.rank,
          totalCorePoints: this.challengeData.totalCorePoints,
        };
      }
      let data = {
        challengeId: this.$route.params.id,
        userId: id,
      };
      getUserGamification(data)
        .then((response) => {
          this.gamificationData = response.data;
          this.showCalendar = true;
        })
        .catch((error) => {
          this.handleError(error);
        });
    },
    updateUserObject(event) {
      event.type === "unfollow" ? this.updateUserToUnfollow() : this.updateUserToFollow();
    },
    updateUserToUnfollow() {
      this.selectedUserData.status = "unknown";
      this.selectedUserData.isFriend = this.selectedUserData.friendship === "follower" ? false : this.selectedUserData.isFriend;
    },

    updateUserToFollow() {
      this.selectedUserData.status = this.selectedUserData.privacy === "public" ? "accepted" : "requested";
      this.selectedUserData.isFriend = this.selectedUserData.friendship === "follower" && this.selectedUserData.privacy === "public" ? true : this.selectedUserData.isFriend;
    },
    leaveChallenge(key) {
      let data = {
        status: key,
        challengeId: this.$route.params.id,
      };
      leaveChallenge(data)
        .then((response) => {
          if (key === false) {
            this.showLeaveModal = false;
            this.showLeaveSuccess = true;
            this.showLeaveBtn = false;
          } else {
            toastr.success(response.message);
          }
          this.getChallengeData();
          this.getParticipants({
            challengeId: this.$route.params.id,
            limit: 10,
            offset: 0,
          });
        })
        .catch((error) => {
          this.handleError(error);
        });
    },
    getDatesDifference(startDate, endDate) {
      this.duration = 0;
      let result = moment(endDate).diff(moment(startDate), "days");

      this.duration = result === 0 ? 1 : result + 1;
    },
    getPassedDays(endDate) {
      let remainingDays = moment(endDate).diff(moment(), "days");
      let passedDays = this.duration - Math.max(0, remainingDays + 1);
      return Math.max(0, passedDays);
    },
    showSignInModal: function () {
      const body = document.querySelector("body");
      body.classList.add("overflow-y-hidden");
      Emitter.emit("sign_in_modal", "show sign in modal");
    },
    handleError(error) {
      const errorMessage = error[0]?.response?.data?.errors?.[0]?.error || "Something Went Wrong Try Again";
      toastr.error(errorMessage);
    },

    updateMetaData() {
      this.pageTitle = this.challengeData.title || "Challenge";
      this.pageImage = this.challengeData.cover_photo || "cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp";
      const pageUrl = window.location.href;
      updateMetaInformation(this.pageTitle + " | Core Direction", "", "Join the Challenge! Earn points and climb the leaderboard to win!", this.pageTitle + " | Core Direction", "Join the Challenge! Earn points and climb the leaderboard to win!", "https://cdn.coredirection.com/" + this.pageImage + "?optimizer=image&format=webp&width=1200&quality=80", pageUrl);
    },

  },
};
</script>
<style scoped>
@import "/assets/css/challenge-detail.css";

#challenges .btn-back {
  margin-top: -5px;
}
</style>