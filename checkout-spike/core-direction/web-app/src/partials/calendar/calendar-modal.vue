<template>
  <div id="modal-wrapper">
    <div v-if="isModalShowProp" class="wrapper">
      <div id="activity-modal" class="custom-modal m-auto hidden overflow-y-auto" style="display: block;">
        <div class="modal-center">
          <div class="modal-outer-box">
            <div class="modal-inner-box">
              <div class="modal-header">
                <div class="btn-modal-close ml-auto" @click="hideCalendarModal()">
                  <close-icon />
                </div>
              </div>
              <div class="modal-body px-3">
                <div class="booking-card">
                  <div class="booking-card-header flex items-start">
                    <div class="business-logo-box">
                      <img :src="constants.getImageUrl(`member/${activityDetail.facilityImage}`)" alt="" height="27px"
                           width="27px"
                      >
                    </div>
                    <h3 class="business-name text-left">
                      {{ activityDetail.facility }}
                    </h3>
                  </div>
                  <div class="booking-card-body">
                    <div class="booking-info-box flex items-baseline">
                      <p class="booking-title">
                        {{ activityDetail.activityName }}
                      </p>
                      <div class="booking-more-info-box">
                        <button class="booking-desc-icon-box">
                          <info-icon />
                        </button>
                        <div class="booking-desc-box">
                          <p class="booking-desc">
                            {{ activityDetail.description }}
                          </p>
                        </div>
                      </div>
                    </div>
                    <p class="booking-activity-time">
                      <strong>{{ activityDetail.class_date }}</strong> | {{ activityDetail.startTime }} -
                      {{ activityDetail.endTime }}
                    </p>
                    <ul class="booking-activity-info-list">
                      <li class="booking-activity-info-item flex items-center flex-wrap">
                        <span class="booking-activity-info-item-icon-box flex justify-center">
                          <booking-slot-icon />
                        </span>
                        <strong>Booking slots:</strong>&nbsp; {{ activityDetail.booked_slots }}/{{
                          activityDetail.slots
                        }}
                        <span class="tag">{{ activityDetail.is_free ? "Free" : "Paid" }}</span>
                      </li>
                      <li class="booking-activity-info-item flex items-center flex-wrap">
                        <span class="booking-activity-info-item-icon-box flex justify-center">
                          <img :src="constants.getImageUrl(`activity/${activityDetail.activityImage}`)">
                        </span>
                        <strong class="booking-activity-name">{{ activityDetail.activityName }} </strong>
                      </li>
                      <li class="booking-activity-info-item flex items-center flex-wrap">
                        <span class="booking-activity-info-item-icon-box flex justify-center">
                          <pointer-icon />
                        </span>
                        <strong class="block">{{ activityDetail.zone_title }}&nbsp;&nbsp;&nbsp;</strong>
                      </li>
                    </ul>
                    <div v-if="activityDetail.online_class">
                      <p>
                        <span class="meeting-information-title">Meeting URL: </span>
                        <a :href="activityDetail.login_url_online"
                           class="meeting-information-description text-link"
                           target="_blank"
                        >
                          {{ activityDetail.login_url_online }}
                        </a>
                      </p>
                      <p>
                        <span class="meeting-information-title">Meeting ID: </span>
                        <span class="meeting-information-description">{{ activityDetail.meeting_id_online }}</span>
                      </p>
                      <p>
                        <span class="meeting-information-title">Meeting Password: </span>
                        <span class="meeting-information-description">{{ activityDetail.login_password_online }}</span>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="footer-btn-box">
                  <button class="btn-share" @click="shareActivity">
                    Share with your friends
                  </button>
                  <button class="btn-modal-close" @click="cancelBooking()">
                    Cancel
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import CloseIcon from "@/svgs/close-icon";
import BookingSlotIcon from "@/svgs/booking-card/booking-slot-icon";
import PointerIcon from "@/svgs/maps-icon/pointer-icon";
import InfoIcon from "@/svgs/company/info-icon";

export default {
  name: "CalendarModal",
  components: {InfoIcon, PointerIcon, BookingSlotIcon, CloseIcon},
  props: {
    isModalShowProp: {
      type: Boolean,
      required: true
    },
    activityDetail: {
      type: Object,
      required: true
    },
  },
  filters: {
    isoToUtc: function (value) {
      return moment(value).utc().format("DD-MM-YYYY");
    }
  },
  methods: {
    hideCalendarModal() {
      this.$emit("onHideCalendarModal");
    },
    cancelBooking() {
      this.$emit("cancelBooking", true);
    },
    shareActivity() {
      this.$emit("onShareActivityWithYourFriends");
    }

  }
};
</script>

<style scoped>
#activity-modal .modal-outer-box {
  max-width: 460px;
  background: #F1F1F1;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
}

.meeting-information-title, .meeting-information-description {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  line-height: 14.63px;

}

.meeting-information-description {
  font-weight: 400;
}

.meeting-information-title {
  font-weight: 700;
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

#activity-modal .footer-btn-box .btn-share, #activity-modal .footer-btn-box .btn-modal-close {
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

@media screen and (max-width: 576px) {
  #activity-modal .modal-body {
    width: 300px !important;
  }
}

.booking-card .booking-more-info-box {
  top: 0px !important;
}

.text-link {
  color: rgba(59, 130, 246, 1);
}
</style>