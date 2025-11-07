<template>
  <div v-if="isShowDetailModalProp" class="wrapper">
    <!-- location-details -->
    <div id="location-details" class="custom-modal m-auto overflow-y-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-body">
              <div class="form-container mx-auto">
                <!-- <form action=""> -->
                <div v-click-outside-modal="hideCalendarModal" class="location-detail-box">
                  <div class="location-detail-header">
                    <div class="grid grid-cols-6 items-center">
                      <p class="current-day col-span-2">
                        {{ dateAndDay.day }}
                      </p>
                      <div class="col-span-2" />
                      <p class="current-date col-span-2">
                        {{ dateAndDay.date }}
                      </p>
                    </div>
                  </div>
                  <div class="location-detail-body">
                    <div v-for="event in multipleEvents" :key="event.startTime"
                         class="activity-item grid grid-cols-6 items-center"
                    >
                      <p class="activity-time col-span-2">
                        {{ event.startTime }} - {{ event.endTime }}
                      </p>
                      <div class="activity-info-box col-span-3">
                        <p class="activity-name">
                          {{ event.activityName }}
                        </p>
                        <p class="activity-place">
                          {{ event.zone_title }}
                        </p>
                      </div>
                      <button class="btn-activity-detail" @click="showDetailModal(event)">
                        View Details
                      </button>
                    </div>
                  </div>
                </div>
                <!-- </form> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: "CalendarDetailModal",
    props: {
        isShowDetailModalProp: {
            type: Boolean,
            required: true
        },
        multipleEvents: {
            type: Array,
            required: true
        },
        dateAndDay: {
            type: Object,
            required: true
        },
    },
    methods: {
        hideCalendarModal(event) {
            this.$emit("hideDetailModal", false);
        },
        showDetailModal(event) {
            this.$emit("showDetailModal", event);
        }
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
}

#location-details .activity-time {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
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