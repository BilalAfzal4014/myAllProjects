<template>
  <div class="wrapper">
    <!-- activity-modal -->
    <div id="activity-modal" class="custom-modal m-auto overflow-y-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="closeActivityDiaryModal">
                <close-icon />
              </div>
            </div>
            <div class="modal-body px-3">
              <div class="booking-card">
                <div class="booking-card-header flex items-start">
                  <div class="business-logo-box">
                    <img :src="isShareActivityUrl ? createdByImageUrl : profileImageUrl" alt="" height="27px"
                         width="27px"
                    >
                  </div>
                  <h3 class="business-name text-left">
                    MY INSPIRATION
                  </h3>
                </div>
                <div class="booking-card-body">
                  <div class="booking-info-box flex items-baseline">
                    <p class="booking-title">
                      {{ eventDetail.activity_name }}
                    </p>
                  </div>
                  <p class="booking-activity-time">
                    <strong>{{ eventDetail.date | formatDate }}</strong> |
                    {{ eventDetail.start_time | convertIS8601ToTime }} -
                    {{ eventDetail.end_time | convertIS8601ToTime }}
                  </p>
                  <ul v-if="eventDetail.type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY || isShareActivityUrl"
                      class="booking-activity-info-list"
                  >
                    <li class="booking-activity-info-item flex items-center flex-wrap">
                      <span class="booking-activity-info-item-icon-box flex justify-center">
                        <booking-slot-icon />
                      </span>
                      <strong>
                        Booking slots:
                      </strong> <span class="ml-2"> {{ totalJoinedUsers }} / {{ totalJoinedUsers }}</span>
                      <span class="tag">Free</span>
                    </li>
                    <li class="booking-activity-info-item flex items-center flex-wrap">
                      <span class="booking-activity-info-item-icon-box flex justify-center">
                        <img
                          :alt="eventDetail.activity_type.name"
                          :src="constants.getImageUrl(`activity/${eventDetail.activity_type.imageName}`)"
                        >
                      </span>
                      <strong class="booking-activity-name">{{
                        eventDetail.activity_type.name
                      }} </strong>
                    </li>
                    <li v-if="eventDetail.formatted_address"
                        class="booking-activity-info-item flex items-center flex-wrap location"
                    >
                      <span class="booking-activity-info-item-icon-box flex justify-center">
                        <pointer-icon />
                      </span>
                      <p class="block">
                        {{ eventDetail?.formatted_address }}
                      </p>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="friend-invited-box" @click="inviteFriend">
                <p class="friend-invited-title">
                  Friends that are invited or joining
                </p>
                <ul class="invited-friend-list">
                  <li v-for="user in eventDetail.fos_user_user" :key="user.username"
                      class="invited-friend-item"
                  >
                    <img :src="constants.getImageUrl(user.fos_user_user_invited_user?.profile_picture)" alt=""
                         class="invited-friend-img"
                    >
                  </li>
                </ul>
              </div>
              <div class="footer-btn-box">
                <button :disabled="isDisabled" class="btn-share" @click="handleJoinOrShareActivityClick">
                  {{ buttonText }}
                </button>

                <button :disabled="isUserCanCancelActivity" class="btn-modal-close"
                        @click="cancelActivityDiary(eventDetail)"
                >
                  Cancel
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
import CloseIcon from "@/svgs/close-icon";
import moment from "moment";
import Emitter from "tiny-emitter/instance";
import PointerIcon from "@/svgs/maps-icon/pointer-icon";
import {joinActivityInvite} from "@/apiManager/activities";
import {hideBodyScroll} from "@/utils";
import {ACTIVITY_TYPES} from "@/common/constants/constants";
import BookingSlotIcon from "@/svgs/booking-card/booking-slot-icon";

export default {
  name: "ActivityDiaryDetail",
  components: {BookingSlotIcon, PointerIcon, CloseIcon},
  props: {
    eventDetail: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      userAvatar: this.$store?.getters?.getStoreUserProfileGetters()?.picture,
      userId: null,
      ACTIVITY_TYPES
    };

  },
  computed: {
    isShareActivityUrl() {
      return !!this.$route.query.dairy_shared;
    },
    createdByImageUrl() {
      return this.constants.getImageUrl(this.eventDetail?.created_by?.profile_picture);
    },
    profileImageUrl() {
      return this.constants?.getImageUrl(this.eventDetail?.profile_picture);
    },
    totalJoinedUsers() {
      return this.eventDetail.fos_user_user.length + 1;
    },
    buttonText() {

      if (this.userId === this.eventDetail?.created_by_id) {
        return "Share with your friends";
      } else if (this.checkUserStatus()) {
        return "Already Joined";
      } else {
        return "Join this Activity";
      }
    },
    isDisabled() {
      return this.checkUserStatus();
    },
    isUserCanCancelActivity() {
      if (this.userId === this.eventDetail?.created_by_id) {
        return false;
      } else {
        return !this.checkUserStatus();
      }
    },

  },
  filters: {
    formatDate: function (value) {
      return moment(value).utc().format("dddd, MMMM Do YYYY");
    },
    extractMinutes: function (value) {
      return new Date(value).toLocaleTimeString("en",
        {timeStyle: "short", hour12: false, timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone});
    },
    convertIS8601ToTime: function (date) {
      return new Date(date).toLocaleTimeString("en",
        {timeStyle: "short", hour12: false, timeZone: "UTC"});
    }
  },
  created() {
    this.userId = JSON.parse(localStorage.getItem("userProfile"))?.id;

  },
  methods: {
    handleJoinOrShareActivityClick() {
      if (this.userId === this.eventDetail.created_by_id) {
        this.shareActivityWithYourFriends();
        return false;
      } else if (!this.checkUserStatus()) {
        this.joinActivity();
      }
    },
    async cancelActivityDiary(detail) {
      this.$emit("onCancelledActivityDiary", detail);
    },

    inviteFriend() {
      this.$emit("inviteFriend");
    },
    shareActivityWithYourFriends() {
      hideBodyScroll();
      this.$emit("onCloseActivityDiaryDetail");
      this.$emit("socialSharing");
      if (this.eventDetail.hasOwnProperty("type") && this.eventDetail.type === "userActivityDairy") {
        Emitter.emit(
          "social_sharing_modal",
          "shareActivity",
          this.eventDetail.slug_url
        );
        return;
      } else {
        Emitter.emit(
          "social_sharing_modal",
          "booking",
          this.eventDetail.slug_url
        );
        return;
      }


    },
    joinActivity() {
      let payload = {
        dataId: this.eventDetail.id
      };
      joinActivityInvite(payload).then((response) => {
        toastr.success("You have successfully joined this activity. This activity is now added to your calender as well.");
        this.$router.push("/community");
        this.$emit("refetchActivities");
      });
    },
    checkUserStatus() {
      let status = false;
      this.eventDetail?.fos_user_user.find((x) => {
        if (x.fos_user_user_invited_user.id === this.userId && x.status === "accepted") {
          return status = true;
        } else {
          return status = false;
        }
      });
      return status;
    },
    closeActivityDiaryModal() {
      this.$store.dispatch("hidePopup");
      this.$emit("onCloseActivityDiaryDetail");
    }
  }

};
</script>

<style scoped>
#activity-modal .modal-outer-box {
  max-width: 460px;
  background: #F1F1F1;
  -webkit-box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
}

#activity-modal .modal-body {
  max-width: 378px;
  margin-left: auto;
  margin-right: auto;
  padding-bottom: 60px;
}

#activity-modal .booking-card {
  margin-bottom: 10px;
}

#activity-modal .booking-card .booking-card-header {
  border-bottom: unset;
}

#activity-modal .friend-invited-box {
  padding: 32px;
  border-radius: 11px 11px 21px 21px;
  background-color: #FFFFFF;
}

#activity-modal .friend-invited-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 12px;
}

#activity-modal .friend-invited-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
}

#activity-modal .invited-friend-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 5px;
  column-gap: 5px;
  row-gap: 5px;
  justify-content: flex-start;
}

#activity-modal .invited-friend-img {
  min-width: 26px;
  min-height: 26px;
  max-width: 26px;
  max-height: 26px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  -o-object-fit: cover;
  object-fit: cover;
  -o-object-position: center;
  object-position: center;
}

#activity-modal .footer-btn-box {
  margin-top: 30px;
}

#activity-modal .footer-btn-box .btn-share,
#activity-modal .footer-btn-box .btn-modal-close {
  padding: 13px;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  display: block;
  width: 100%;
  color: #FFFFFF;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 16px;
  letter-spacing: 0em;
  text-align: center;
}

#activity-modal .footer-btn-box .btn-share {
  background-color: #690FAD;
}

#activity-modal .footer-btn-box .btn-modal-close {
  margin-top: 10px;
  background-color: #757575;
}
</style>