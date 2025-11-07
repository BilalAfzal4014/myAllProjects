<template>
  <div v-if="isShowDetailModalProp" class="wrapper">
    <div id="location-details" class="custom-modal m-auto overflow-y-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-body">
              <div class="form-container mx-auto">
                <div v-click-outside-modal="hideCalendarModal" class="location-detail-box">
                  <div class="location-detail-header">
                    <div class="flex justify-between">
                      <p class="current-day">
                        {{ dateAndDay.day }}
                      </p>
                      <p class="current-date">
                        {{ dateAndDay.date }}
                      </p>
                    </div>
                  </div>
                  <div class="location-detail-body">
                    <div v-for="(event,index) in multipleEvents" :key="index"
                         class="activity-item flex justify-between items-center"
                    >
                      <p class="activity-time">
                        {{
                          event.type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY ? convertIS8601ToTime(event.start_time) : event.startTime
                        }} -
                        {{
                          event.type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY ? convertIS8601ToTime(event.end_time) : event.endTime
                        }}
                      </p>
                      <div class="activity-info-box">
                        <p class="activity-name">
                          {{
                            event.type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY ? event.activity_name : event.activityName
                          }}
                        </p>
                        <p class="activity-place">
                          {{
                            event.type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY ? event.activity_type.name : event.actt_name
                          }}
                        </p>
                      </div>
                      <p class="cursor-pointer btn-activity-detail"
                         @click="showActivityDetails(event)"
                      >
                        View Details
                      </p>
                    </div>
                  </div>
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
import {ACTIVITY_TYPES} from "@/common/constants/constants";
import generalMixin from "@/mixin/generalMixin";

export default {
  name: "MultipleActivityDiary",
  mixins: [generalMixin],
  props: {
    isShowDetailModalProp: {
      type: Boolean,
      required: true,
    },
    multipleEvents: {
      type: Array,
      required: true,
    },
    dateAndDay: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      ACTIVITY_TYPES
    };
  },
  methods: {
    hideCalendarModal() {
      this.$emit("hideDetailModal", false);
    },
    showActivityDetails(event) {
      this.$emit("onShowActivityDetail", event);
      this.$emit("hideDetailModal", false);
    },
  }
};
</script>

<style scoped>
#location-details .modal-outer-box {
  max-width: calc(415px + 2rem);
  -webkit-box-shadow: 0px 4px 40px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 4px 40px rgba(0, 0, 0, 0.25);
}

#location-details .location-detail-box {
  background: #FFFFFF;
  border-radius: 11px;
  padding-bottom: 18px;
}

#location-details .location-detail-header {
  background-color: #690FAD;
  padding: 10px 25px;
}

#location-details .current-day,
#location-details .current-date {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 600;
  line-height: 17px;
  letter-spacing: 0em;
  color: #FFFFFF;
}

#location-details .activity-item {
  padding: 22px 23px 8px;
}

#location-details .current-day {
  text-align: left;
}

#location-details .current-date {
  text-align: right;
}

#location-details .activity-info-box {
  border-left: 2px solid rgba(34, 34, 34, 0.45);
  padding-top: 4px;
  padding-bottom: 7px;
  padding-left: 20px;
  width: 40%;
}

#location-details .activity-time {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  width: 20%;
}

#location-details .activity-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  color: #690FAD;
  text-transform: uppercase;
}

#location-details .activity-place {
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-style: normal;
  font-weight: 400;
  line-height: 12px;
  letter-spacing: 0em;
  text-align: left;
  text-transform: capitalize;
}

#location-details .btn-activity-detail {
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-style: normal;
  font-weight: 400;
  line-height: 12px;
  letter-spacing: 0em;
  text-align: right;
  text-decoration: underline;
}
</style>