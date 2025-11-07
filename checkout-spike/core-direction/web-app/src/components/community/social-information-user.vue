<template>
  <section id="community">
    <unfollow-user-confirmation v-if="isShowUserConfirmation" :username="user.username" @unfollowUser="unfollowUser" />
    <div class="custom-container">
      <div class="community-outer-box">
        <div class="community-profile-img-box">
          <PreviewImage
            :additionalParams="{
              optimizer: 'image',
              format: 'webp',
              width: '300',
              aspect_ratio: '1:1',
              sharpen: 'true'
            }"
            :alt="`${user.firstname} ${user.lastname} Profile Image`"
            :src="user.profile_picture"
            :useSrcset="false"
            class="community-profile-img rounded-full"
            type="logo"
          />
        </div>
        <div class="user-info-box">
          <div class="user-info-inner-box">
            <p class="user-name">
              <span class="first-letter-capital">{{ user.firstname }} </span>
              <span class="first-letter-capital">{{ user.lastname }}</span>
            </p>
            <p class="user-handle">
              @{{ user.username }}
            </p>
          </div>
          <div v-if="isCommunityRoute" class="activities-btn-box">
            <button
              class="btn-add-activity bg-gradient rounded-full"
              @click="setStepAndOpenModal"
            >
              Add Activity
            </button>
            <router-link class="btn-book-activity bg-gradient rounded-full" to="/listing">
              Book Activity
            </router-link>
            <button class="btn-share-activity bg-gradient rounded-full" @click="showShareActivityModal()">
              <share-activity-icon />
            </button>
          </div>

          <div v-else class="activities-btn-box">
            <button class="btn-book-activity bg-gradient rounded-full"
                    @click="inviteFriendForUnknownStatusUser(user)"
            >
              {{ getUserStatus(user) }}
            </button>
          </div>
          <div v-if="isShowActivityModal">
            <ActivityModal
              v-if="currentStep === 1"
              @setActivity="setActivity"
              @setCurrentStep="setCurrentStep"
              @setShowActivityModal="setShowActivityModal"
            />
            <InviteFriendModal
              v-if="currentStep === 2"
              :activity-id="activity.id"
              @setCurrentStep="setCurrentStep"
              @setShowActivityModal="setShowActivityModal"
            />
            <SocialSharingOnSignup
              v-if="currentStep === 3"
              :show-modal="true"
              :slug-url="profileUrl"
              :user-detail="userDetail"
              @setShowActivityModal="setShowActivityModal"
            />
          </div>
          <ul class="user-profile-list">
            <li class="user-profile-item">
              <span class="activity-value">{{ user?.corePoints || 0 }}</span>
              <span class="activity-label">Core Points</span>
            </li>
            <router-link v-if="!isPrivateRoute" :to="generateLink('followers')" class="user-profile-item">
              <li>
                <span class="activity-value">{{ user?.followersCount }}</span>
                <span class="activity-label">Followers</span>
              </li>
            </router-link>
            <router-link v-if="!isPrivateRoute" :to="generateLink('following')" class="user-profile-item">
              <li>
                <span class="activity-value">{{ user.followingCount }}</span>
                <span class="activity-label">Following</span>
              </li>
            </router-link>
          </ul>
        </div>
      </div>
      <div class="community-inner-box">
        <div class="activity-score-box flex items-center flex-wrap">
          <div class="activity-score-content-box">
            <p class="score-title">
              Today’s Score:
            </p>
            <p class="score-desc">
              Total Core Points
            </p>
          </div>
          <ul class="score-point-list flex items-center flex-wrap">
            <li v-for="n in 10" :key="n" :class="n <= user.todayScore ? 'active' : ''" class="score-point-item">
              <core-points-icon />
            </li>
            <li class="btn-show-wearable-box" @click="toggleWearableModal">
              <button class="btn-show-wearable">
                <three-gray-dots />
              </button>
            </li>
          </ul>
        </div>
        <ul class="activity-stats-list flex items-center flex-wrap">
          <li class="activity-stats-item flex items-center">
            <div class="activity-icon-box">
              <activity-log-icon />
            </div>
            <div class="activity-content-box">
              <p class="activity-heading">
                Activity Log
              </p>
              <p class="activity-points">
                {{ user.activityLog ? ('0' + user.activityLog).slice(-2) : "00" }}
              </p>
              <p class="activity-heading">
                Today
              </p>
            </div>
          </li>
          <li class="activity-stats-item flex items-center">
            <div class="activity-icon-box">
              <booking-acitiviy-icon />
            </div>
            <div class="activity-content-box">
              <p class="activity-heading">
                Bookings
              </p>
              <p class="activity-points">
                {{ user.checkin ? ('0' + user.checkin).slice(-2) : "00" }}
              </p>
              <p class="activity-heading">
                Today
              </p>
            </div>
          </li>
          <li class="activity-stats-item flex items-center">
            <div class="activity-icon-box">
              <activity-steps-icon />
            </div>
            <div class="activity-content-box">
              <p class="activity-heading">
                Steps
              </p>
              <p class="activity-points">
                {{ user.steps ? user.steps : "00" }}
              </p>
              <p class="activity-heading">
                Today
              </p>
            </div>
          </li>
          <li class="activity-stats-item flex items-center">
            <div class="activity-icon-box">
              <heart-rate-icon />
            </div>
            <div class="activity-content-box">
              <p class="activity-heading">
                Heart Rate
              </p>
              <p class="activity-points">
                {{ user.heartRate ? user.heartRate : "00" }}
              </p>
              <p class="activity-heading">
                Active Minutes
              </p>
            </div>
          </li>
          <li class="activity-stats-item flex items-center">
            <div class="activity-icon-box">
              <on-demand-icon />
            </div>
            <div class="activity-content-box">
              <p class="activity-heading">
                On Demand
              </p>
              <p class="activity-points">
                {{ user.ondemand ? user.ondemand : "00" }}
              </p>
              <p class="activity-heading">
                Today
              </p>
            </div>
          </li>
        </ul>
        <div class="profile-detail-box active">
          <button
            class="community-title flex items-center"
            @click="showBiography = !showBiography"
          >
            Profile Details
            <chevron-black-forward-icon :show-biography="showBiography" />
          </button>
          <div
            :class="`profile-detail-inner-box  ${
              showBiography ? 'open-accordion' : 'close-accordion'
            }`"
          >
            <div class="user-bio-box">
              <p class="community-desc">
                {{ user.biography }}
              </p>
            </div>
            <div class="user-interested-activity-box">
              <p class="community-title">
                My Interests
              </p>
              <ul class="user-interested-activity-list">
                <li
                  v-for="(interest, index) in interests"
                  :key="`interest-${index}`"
                  class="user-interested-activity-item"
                >
                  <img
                    :src="`${getActivityTypeUrl(interest.imageName)}`"
                    alt=""
                    class="activity-icon"
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <WearableModal :show-wearable-modal="showWearableModal" @closeWearableModal="showWearableModal = false" />
  </section>
</template>

<script>
import ActivityModal from "@/components/ActivityModal";
import InviteFriendModal from "@/components/InviteFriendModal";
import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";
import constants from "@/constants/constants";
import DefaultImage from "../../assets/images/default_profile_img.png";
import {sendFollowRequest, sendUnfollowRequest} from "@/apiManager/user";
import * as toastr from "toastr";
import {PRIVACY_TYPE as privacyTypes, PRIVACY_TYPE, USER_STATUS} from "@/common/constants/constants";
import Emitter from "tiny-emitter/instance";
import WearableModal from "@/components/community/WearableModal";
import ShareActivityIcon from "@/svgs/community/share-activity-icon";
import CorePointsIcon from "@/svgs/community/core-points-icon";
import ThreeGrayDots from "@/svgs/community/three-gray-dots";
import ActivityLogIcon from "@/svgs/community/activity-log-icon";
import BookingAcitiviyIcon from "@/svgs/community/booking-acitiviy-icon";
import ActivityStepsIcon from "@/svgs/community/activity-steps-icon";
import HeartRateIcon from "@/svgs/community/heart-rate-icon";
import OnDemandIcon from "@/svgs/community/on-demand-icon";
import ChevronBlackForwardIcon from "@/svgs/arrows/chevron-black-forward-icon";
import UnfollowUserConfirmation from "@/components/social/unfollow-user-confirmation";
import PreviewImage from "@/components/PreviewImage";
import {mapGetters} from "vuex";
import {updateMetaInformation} from "@/utils";

export default {
  name: "SocialInformationUser",
  components: {
    PreviewImage,
    UnfollowUserConfirmation,
    ChevronBlackForwardIcon,
    OnDemandIcon,
    HeartRateIcon,
    ActivityStepsIcon,
    BookingAcitiviyIcon,
    ActivityLogIcon,
    ThreeGrayDots,
    CorePointsIcon,
    ShareActivityIcon,
    SocialSharingOnSignup,
    ActivityModal,
    InviteFriendModal,
    WearableModal
  },
  computed: {
    ...mapGetters({
      userProfile: "getStoreUserProfileGetters",
    }),

    isCommunityRoute() {
      return this.$route.name === "Community";
    },
    isPrivateRoute() {
      return this.$route.name === "PrivateProfile";
    }

  },
  watch: {
    showActivityModal() {
      this.isShowActivityModal
        ? this.body.classList.add("overflow-y-hidden")
        : this.body.classList.remove("overflow-y-hidden");
    },
    userDetail() {
      this.user = this.userDetail;
    },
    userInterests() {
      this.interests = this.userInterests;
    }
  },
  created() {
    this.body = document.querySelector("body");
  },
  props: {
    userDetail: {
      type: Object,
      default: null
    },
    userInterests: {
      type: Array,
      default: null
    }
  },
  data() {
    return {
      user: this.userDetail,
      isShowActivityModal: false,
      currentStep: 1,
      body: null,
      activity: null,
      interests: this.userInterests,
      showBiography: false,
      showWearableModal: false,
      flag: false,
      profileUrl: "",
      isShowUserConfirmation: false
    };
  },
  mounted() {
    updateMetaInformation("My Community | Core Direction", "", "My Community: View your daily score, challenges, friends & groups", "My Community | Core Direction", "My Community: View your daily score, challenges, friends & groups", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/community");
  },
  methods: {
    generateLink(type) {
      const loggedInUsername = this.userProfile().username;
      const viewedProfileUsername = this.user.username;

      let friendQueryParameter = {type};

      if (viewedProfileUsername !== loggedInUsername) {
        friendQueryParameter.username = viewedProfileUsername;
      }

      return {path: "/community/friend", query: friendQueryParameter};
    },
    inviteFriendForUnknownStatusUser() {
      if (this.user.friendship === "Following") {
        this.isShowUserConfirmation = true;
        return false;
      }
      if (this.user.privacy === privacyTypes.PUBLIC && this.user.status === "accepted" && this.user.friendship === "Follower") {
        this.isShowUserConfirmation = true;
        return false;
      }
      if ((this.user.status === "unknown" || this.user.status === "accepted") && !this.user.isFriend) {
        this.inviteFriend();
        return;
      } else if ((this.user.status === "accepted" && this.user.isFriend) || this.user.friendship == "Following") {
        this.isShowUserConfirmation = true;
      }
    },
    async unfollowUser() {
      try {
        await sendUnfollowRequest(this.user.id);
        toastr.success("unfollowed user");
        this.isShowUserConfirmation = false;
        this.user.status = "unknown";
        this.user.isFriend = false;
        this.user.friendship = "";
      } catch (error) {
        toastr.error(this.getErrorMessage(error));
      }
    },
    getErrorMessage(error) {
      return error[0]?.response?.data?.errors[0]?.error || "An unexpected error occurred";
    },
    setStepAndOpenModal() {
      this.setShowActivityModal(true);
      this.setCurrentStep(1);
    },
    toggleWearableModal() {
      this.showWearableModal = !this.showWearableModal;
    },
    setShowActivityModal: function (value) {
      this.isShowActivityModal = value;
    },
    setCurrentStep: function (value) {
      this.currentStep = value;
      if (this.currentStep === 3) {
        this.profileUrl = this.activity?.slug_url;
      }

    },
    setActivity: function (value) {
      this.activity = value;
    },
    getActivityTypeUrl: function (imageName) {
      return constants.getImageUrl("activity/" + imageName);
    },
    getProfileImageUrl() {
      return this.user.profile_picture
        ? constants.getImageUrl(this.user.profile_picture) + "?optimizer=image&format=webp&width=300&aspect_ratio=1:1&sharpen=true"
        : DefaultImage;
    },
    getSendFollowRequestPayload() {
      return {
        followingId: this.user.id,
      };
    },
    inviteFriend() {
      sendFollowRequest(this.getSendFollowRequestPayload())
        .then(() => {
          if (this.user.privacy === PRIVACY_TYPE.PUBLIC)
            this.user.status = USER_STATUS.ACCEPTED;
          this.user.isFriend = true;
          if (this.user.privacy === PRIVACY_TYPE.PRIVATE)
            this.user.status = USER_STATUS.REQUESTED;
          toastr.success("Invite is sent");
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
    getUserStatus(user) {
      if (user.privacy === privacyTypes.PRIVATE && user.friendship === "Following" && user.status !== USER_STATUS.REQUESTED)
        return "Following";
      if ((user.status === USER_STATUS.UNKNOWN || user.status === "accepted") && user.privacy === privacyTypes.PRIVATE && !user.isFriend)
        return "Send Request";
      if (user.status === USER_STATUS.UNKNOWN && user.privacy === privacyTypes.PUBLIC && !user.isFriend && user.friendship !== "Following")
        return "Follow";
      if (user.status === USER_STATUS.REQUESTED)
        return "REQUEST SENT";
      if (user.status === "accepted" && user.friendship === "Follower" && user.privacy === privacyTypes.PUBLIC)
        return "Following";
      if (user.isFriend || user.friendship === "Following")
        return "Following";
    },
    showShareActivityModal() {
      //TODO: Can't understand this logic, meanwhile I've commented this code.
      this.profileUrl = this.user.privacy === PRIVACY_TYPE.PUBLIC ? `/profile/${this.user.username}` : `/private/${this.user.username}`;
      this.isShowActivityModal = true;
      this.currentStep = 3;
      Emitter.emit(
        "social_sharing_modal",
        "",
        "",
        "",
        "profile",
        this.profileUrl
      );
    }
  },
};
</script>

<style scoped>
#community {
  padding-top: 60px;
  padding-bottom: 20px;
}

#community .community-outer-box {
  width: 100%;
  max-width: 1000px;
  margin-left: auto;
  margin-right: auto;
}

@media screen and (min-width: 768px) {
  #community .community-outer-box {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
  }
}

#community .community-inner-box {
  width: 100%;
  max-width: 1000px;
  margin-left: auto;
  margin-right: auto;
  margin-top: 40px;
}

@media screen and (min-width: 768px) {
  #community .community-inner-box {
    margin-top: 50px;
  }
}

@media screen and (max-width: 767px) {
  #community .community-profile-img-box {
    width: 100%;
  }
}

#community .community-profile-img-box .community-profile-img {
  max-width: 150px;
  max-height: 150px;
  min-width: 150px;
  min-height: 150px;
  -o-object-fit: fill;
  object-fit: fill;
  -o-object-position: center;
  object-position: center;
}

@media screen and (max-width: 767px) {
  #community .community-profile-img-box .community-profile-img {
    max-width: 100px;
    max-height: 100px;
    min-width: 100px;
    min-height: 100px;
    margin-left: auto;
    margin-right: auto;
  }
}

@media screen and (min-width: 768px) {
  #community .community-profile-img-box .community-profile-img {
    margin-right: 20px;
  }
}

@media screen and (min-width: 992px) {
  #community .community-profile-img-box .community-profile-img {
    margin-right: 55px;
  }
}

#community .user-info-box {
  width: 100%;
  max-width: 795px;
}

@media screen and (max-width: 991px) {
  #community .user-info-box {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
  }

  #community .user-info-box .user-name {
    -webkit-box-ordinal-group: 2;
    -ms-flex-order: 1;
    order: 1;
  }

  #community .user-info-box .user-profile-list {
    -webkit-box-ordinal-group: 3;
    -ms-flex-order: 2;
    order: 2;
  }

  #community .user-info-box .activities-btn-box {
    -webkit-box-ordinal-group: 4;
    -ms-flex-order: 3;
    order: 3;
  }

  #community .user-info-box .user-bio-box {
    -webkit-box-ordinal-group: 5;
    -ms-flex-order: 4;
    order: 4;
  }

  #community .user-info-box .community-desc {
    margin-bottom: 45px;
  }

  #community .user-info-box .user-interested-activity-box {
    -webkit-box-ordinal-group: 6;
    -ms-flex-order: 5;
    order: 5;
  }
}

#community .user-info-inner-box {
  min-width: -webkit-fit-content;
  min-width: -moz-fit-content;
  min-width: fit-content;
  width: 100%;
  max-width: 404px;
}

@media screen and (max-width: 767px) {
  #community .user-info-inner-box {
    max-width: 100%;
  }
}

@media screen and (min-width: 992px) {
  #community .user-info-inner-box {
    max-width: 389px;
    float: left;
    margin-bottom: 25px;
  }
}

#community .user-name {
  font-family: "Montserrat", sans-serif;
  font-size: 24px;
  font-weight: 500;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: left;
  margin-top: 20px;
}

@media screen and (max-width: 767px) {
  #community .user-name {
    font-size: 20px;
    font-weight: 600;
    line-height: 24px;
    text-align: center;
  }
}

#community .user-handle {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  margin-top: 5px;
}

@media screen and (max-width: 767px) {
  #community .user-handle {
    font-size: 14px;
    line-height: 17px;
    text-align: center;
  }
}

#community .activities-btn-box {
  min-width: -webkit-fit-content;
  min-width: -moz-fit-content;
  min-width: fit-content;
  width: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-top: 20px;
  row-gap: 15px;
}

@media screen and (max-width: 991px) {
  #community .activities-btn-box {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    row-gap: 10px;
  }
}

@media screen and (min-width: 992px) {
  #community .activities-btn-box {
    max-width: 51%;
    float: left;
  }
}

#community .btn-add-activity,
#community .btn-book-activity,
#community .btn-Unfriend {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 600;
  line-height: 14px;
  letter-spacing: 0em;
  text-align: center;
  padding: 11px;
  width: 100%;
  max-width: 125px;
  color: #ffffff;
  margin-right: 10px;
}

@media screen and (min-width: 992px) {
  #community .btn-add-activity,
  #community .btn-book-activity,
  #community .btn-Unfriend {
    padding: 11px 21px;
    font-size: 15px;
    line-height: 20px;
    margin-right: 15px;
    max-width: 166px;
  }
}

#community .btn-Unfriend {
  margin-right: 0;
}

#community .btn-request-sent {
  background: #caa8f5;
}

#community .btn-share-activity {
  min-width: 37px;
  min-height: 37px;
  max-width: 37px;
  max-height: 37px;
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

@media screen and (min-width: 992px) {
  #community .btn-share-activity {
    min-width: 42px;
    min-height: 42px;
    max-width: 42px;
    max-height: 42px;
  }
}

#community .user-profile-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  width: 100%;
  max-width: 495px;
  margin-top: 25px;
  margin-bottom: 20px;
}

@media screen and (max-width: 767px) {
  #community .user-profile-list {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-column-gap: 25px;
    column-gap: 25px;
    row-gap: 30px;
    max-width: 100%;
  }
}

@media screen and (min-width: 992px) {
  #community .user-profile-list {
    margin-top: 92px;
    margin-bottom: 24px;
  }
}

#community .user-profile-item {
  margin-bottom: 10px;
}

#community .activity-value {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: right;
  margin-right: 10px;
}

#community .activity-label {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

#community .community-title {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 20px;
}

@media screen and (max-width: 767px) {
  #community .community-title {
    font-size: 14px;
    line-height: 17px;
  }
}

#community .community-desc {
  width: 100%;
  max-width: 628px;
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 35px;
}

@media screen and (max-width: 767px) {
  #community .community-desc {
    font-size: 12px;
    line-height: 14px;
  }
}

#community .user-interested-activity-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  max-width: 628px;
  row-gap: 26px;
  padding-bottom: 10px;
}

#community .user-interested-activity-item {
  margin-right: 26px;
}

#community .activity-icon {
  width: 100%;
  max-width: 30px;
}

#community .score-title {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 24px;
  letter-spacing: -0.011em;
  text-align: left;
}

#community .score-point-list {
  -webkit-column-gap: 5px;
  column-gap: 5px;
  row-gap: 5px;
}

#community .score-point-item svg {
  width: 28px;
  height: 28px;
}

#community .activity-score-box {
  -webkit-column-gap: 30px;
  column-gap: 30px;
  row-gap: 17px;
  margin-bottom: 35px;
}

@media screen and (min-width: 768px) {
  #community .activity-score-box {
    margin-bottom: 30px;
  }
}

#community .btn-show-wearable {
  cursor: pointer;
}

@media screen and (min-width: 768px) {
  #community .btn-show-wearable {
    margin-left: 25px;
  }
}

#community .score-point-item.active svg,
#community .score-point-item.active path {
  fill: #690fad;
}

#community .score-desc {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 18px;
  letter-spacing: -0.011em;
  text-align: left;
}

#community .activity-stats-list {
  row-gap: 15px;
  -webkit-column-gap: 23px;
  column-gap: 23px;
  margin-bottom: 60px;
}

@media (max-width: 767px) {
  #community .activity-stats-list {
    -webkit-column-gap: 13px;
    column-gap: 13px;
  }
}

#community .activity-stats-item {
  position: relative;
  -webkit-column-gap: 15px;
  column-gap: 15px;
  padding-right: 17px;
}

@media screen and (max-width: 767px) {
  #community .activity-stats-item {
    -webkit-column-gap: 10px;
    column-gap: 10px;
    padding-right: 14px;
  }

  #community .activity-stats-item svg {
    max-height: 32px;
  }
}

#community .activity-stats-item.active::after {
  position: absolute;
  content: "*";
  right: 0;
  top: 0;
  font-family: "Montserrat", sans-serif;
  font-size: 24px;
  font-weight: 600;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: right;
  color: #690fad;
}

#community .activity-heading {
  font-family: "Montserrat", sans-serif;
  font-size: 10px;
  font-weight: 400;
  line-height: 12px;
  letter-spacing: 0em;
  text-align: left;
}

#community .activity-points {
  font-family: "Montserrat", sans-serif;
  font-size: 19px;
  font-weight: 600;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
}

@media screen and (min-width: 768px) {
  #community .activity-points {
    font-size: 24px;
    line-height: 29px;
  }
}

#community .profile-detail-box .community-title svg {
  margin-left: 10px;
}

#community .profile-detail-box .profile-detail-inner-box {
  display: none;
}

#community .profile-detail-box.active .community-title svg {
  transform: rotate(180deg);
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
}

#community .profile-detail-box.active .profile-detail-inner-box {
  display: block;
}

.close-accordion {
  position: relative;
  overflow: hidden;
  height: 0;
  transition: 0.5s;
}

.open-accordion {
  position: relative;
  overflow: hidden;
  min-height: 15rem;
  transition: 0.5s;
}

.closed-icon {
  transform: rotate(-90deg) !important;
  transition: transform 0.5s;
}

.open-icon {
  transform: rotate(0deg) !important;
  transition: transform 0.5s;
}

.first-letter-capital {
  text-transform: capitalize;
}


</style>
