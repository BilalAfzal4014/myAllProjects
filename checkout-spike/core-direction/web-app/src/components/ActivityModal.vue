<template>
  <div id="add-activity-modal" class="custom-modal m-auto overflow-y-auto">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="setShowActivityModal">
              <cross-icon />
            </div>
          </div>
          <div class="modal-body">
            <p class="add-activity-title">
              Activity Diary
            </p>
            <p class="activity-note">
              <info-icon />
              Selecting "Public" under session type will allow your followers to
              view and join your activity. Selecting "Private" will allow direct
              invite with follower view only.
            </p>
            <div class="activity-name-box">
              <label class="activity-field-label" for="activity-name">Activity Name</label>
              <input
                id="activity-name"
                v-model="activityName"
                class="activity-field"
                placeholder="Activity Name"
                type="text"
              >
            </div>
            <div class="activity-type-box">
              <label class="activity-field-label">Activity Type</label>
              <div
                class="selected-activity-type-box"
                @click="showDropdown = !showDropdown"
              >
                <span class="activity-field">
                  {{ activityType.name }}
                </span>
                <arrow-open />
              </div>
              <!-- add show class to display dropdown -->
              <div :class="['activity-type-list-box',{ 'show': showDropdown }]">
                <div class="activity-type-header" @click="showDropdown = false">
                  <arrow-close />
                </div>
                <div class="activity-type-body">
                  <ul class="activity-type-list">
                    <li
                      v-for="({ name, id }, index) in activityTypeOptions"
                      :key="`activity-type${index}`"
                      class="activity-type-item"
                      @click="setActivityType({ name, id })"
                    >
                      {{ name }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="activity-session-type-box">
              <label class="activity-field-label">Session Type</label>
              <div
                class="activity-session-type flex items-center justify-between md:pr-3"
              >
                <div class="form-group">
                  <input
                    id="public"
                    v-model="sessionType"
                    name="session-type"
                    type="radio"
                    value="public"
                  >
                  <label for="public">Public</label>
                </div>
                <div class="form-group">
                  <input
                    id="private"
                    v-model="sessionType"
                    name="session-type"
                    type="radio"
                    value="private"
                  >
                  <label for="private">Private</label>
                </div>
                <div class="form-group">
                  <input
                    id="hidden"
                    v-model="sessionType"
                    name="session-type"
                    type="radio"
                    value="hidden"
                  >
                  <label for="hidden">Only Me</label>
                </div>
              </div>
            </div>
            <div class="date-section-main">
              <div class="date-box">
                <label class="activity-field-label">Start Date & Time</label>
                <div class="activity-date-outer-box">
                  <input
                    id="start_time"
                    ref="inputDate"
                    v-model="activityStartTime"
                    :min="minActivityStartTime"
                    class="input-field"
                    placeholder="00 / 00 / 0000"
                    type="datetime-local"
                  >
                </div>
              </div>
              <div class="time-box">
                <label class="activity-field-label">Duration</label>
                <div class="activity-date-outer-box">
                  <div class="duration custom-dropdown">
                    <select v-model="duration" class="duration-dropdown">
                      <option disabled selected value="">
                        Minutes
                      </option>
                      <option
                        v-for="(durationValue, index) in durationOptions"
                        :key="`durationValue-${index}`"
                        :value="durationValue"
                      >
                        {{ durationValue }}
                      </option>
                    </select>
                    <arrow-open />
                  </div>
                </div>
              </div>
            </div>
            <div class="location-box">
              <label class="activity-field-label">Location</label>
              <div class="selected-activity-type-box location-border">
                <div class="location-input-wrapper">
                  <div class="input-field-search-box mr-4 mb-4">
                    <input
                      v-model.trim="address"
                      type="text"
                      placeholder="Type or Search Location Name"
                    />
                  </div>
                </div>
              </div>
            </div>
            <button
              class="btn-add-inspire rounder-full bg-gradient"
              @click="
                () => {
                  validateSchema() && addActivityDiary();
                }
              "
            >
              Add & Inspire
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CrossIcon from "@/svgs/activity-modal/cross-icon";
import InfoIcon from "@/svgs/company/info-icon";
import ArrowClose from "@/svgs/activity-modal/arrow-close";
import ArrowOpen from "@/svgs/activity-modal/arrow-open";
import {monthNames} from "@/dateConstant";
import {createArrayWithRange, getLocalDateTime} from "@/utils";
import Joi from "joi";
import * as toastr from "toastr";
import moment from "moment";
import {createActivityDiary, getActivityType} from "@/apiManager/activities";
import Emitter from "tiny-emitter/instance";

export default {
  name: "ActivityModal",
  data: function () {
    return {
      timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
      max: moment().format("YYYY-MM-DD"),
      activityName: "",
      activityType: {},
      sessionType: "",
      activityStartTime: null,
      minActivityStartTime: null,
      date: "",
      month: "",
      year: "",
      hour: "",
      minute: "",
      duration: "",
      showDropdown: false,
      activityTypeOptions: [],
      dateOptions: createArrayWithRange(1, 31),
      monthOptions: monthNames,
      yearOptions: createArrayWithRange(2022, 2027),
      hoursOptions: createArrayWithRange(1, 24),
      minutesOptions: createArrayWithRange(0, 59),
      durationOptions: createArrayWithRange(1, 12, 15),
      address:"",
      feedLoaded: false,
      zoom: 7,
    };
  },
  components: {
    ArrowOpen,
    ArrowClose,
    InfoIcon,
    CrossIcon,
  },
  created() {
    this.getActivities();
  },
  mounted() {
    this.activityStartTime = getLocalDateTime();
    let date = new Date();
    this.minActivityStartTime = new Date(date.getFullYear(), date.getMonth() - 1, 2).toISOString().substring(0, 16);
  },
  methods: {
    setActivityType(activityType) {
      this.activityType = activityType;
      this.showDropdown = false;
    },
    dateAdd(date, interval, units) {
      if (!(date instanceof Date))
        return undefined;
      let ret = new Date(date); //don't change original date
      let checkRollover = function () {
        if (ret.getDate() != date.getDate()) ret.setDate(0);
      };
      switch (String(interval).toLowerCase()) {
      case "year"   :
        ret.setFullYear(ret.getFullYear() + units);
        checkRollover();
        break;
      case "quarter":
        ret.setMonth(ret.getMonth() + 3 * units);
        checkRollover();
        break;
      case "month"  :
        ret.setMonth(ret.getMonth() + units);
        checkRollover();
        break;
      case "week"   :
        ret.setDate(ret.getDate() + 7 * units);
        break;
      case "day"    :
        ret.setDate(ret.getDate() + units);
        break;
      case "hour"   :
        ret.setTime(ret.getTime() + units * 3600000);
        break;
      case "minute" :
        ret.setTime(ret.getTime() + units * 60000);
        break;
      case "second" :
        ret.setTime(ret.getTime() + units * 1000);
        break;
      default       :
        ret = undefined;
        break;
      }
      return ret;
    },
    isNumber(value) {
      return typeof value === "number" && isFinite(value);
    },
    getDate: function () {
      return this.activityStartTime;
    },
    getEndTime: function () {
      return this.dateAdd(new Date(
        this.activityStartTime
      ), "minute", this.duration);
    },
    convertTZ(date, tzString) {
      return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));
    },
    getCreateActivityDiaryPayload: function () {
      let obj = {
        activityId: this.activityType.id,
        activityName: this.activityName,
        sessionType: this.sessionType,
        duration: Number(this.duration),
        status: "publish",
        formatted_address: this.address,
        date: this.convertTZ(this.activityStartTime, this.timezone),
        startTime: this.transformDateIntoIsoFormatLocalTime(this.convertTZ(this.activityStartTime, this.timezone)),
        endTime: this.transformDateIntoIsoFormatLocalTime(this.convertTZ(this.getEndTime(), this.timezone)),
      };
      return obj;
    },
    setShowActivityModal: function () {
      this.$emit("setShowActivityModal", false);
    },
    setCurrentStep: function () {
      this.$emit("setCurrentStep", 2);
    },
    setActivity: function (value) {
      this.$emit("setActivity", value);
    },
    validateSchema() {
      let JoiPayLoad = {
        ActivityName: this.activityName.trimStart(),
        ActivityType: this.activityType,
        SessionType: this.sessionType,
        ActivityStartTime: this.activityStartTime,
        Duration: this.duration,
      };
      const schema = Joi.object({
        ActivityName: Joi.string().required(),
        ActivityType: Joi.object({
          id: Joi.number().required(),
          name: Joi.string().required(),
        }),
        ActivityStartTime: Joi.string().required(),
        SessionType: Joi.string().required(),
        Duration: Joi.string().required(),
      });
      const {error} = schema.validate(JoiPayLoad);
      if (error) {
        toastr.error(error);
        return false;
      }
      return true;
    },
    getActivities() {
      getActivityType()
        .then((response) => {
          this.activityTypeOptions = response;
          this.activityType = {
            id: response[0].id,
            name: response[0].name,
          };

        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
    addActivityDiary: function () {
      createActivityDiary(this.getCreateActivityDiaryPayload())
        .then((response) => {
          this.setActivity(response.data);
          toastr.success("Activity Diary is successfully created");
          Emitter.emit("refetch_activity_diary_listing", "");
          this.setCurrentStep();
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
    transformDateIntoIsoFormatLocalTime(date) {
      const utcDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60000));
      return utcDate.toISOString();
      ;
    }
  },

};
</script>

<style scoped>
.date-section-main {
  display: flex;
  justify-content: space-between;
  width: 100%;
}

.date-section-main {
  -webkit-column-gap: 5px;
  column-gap: 5px;
}

.vue-map-container {
  height: 400px;
}

#add-activity-modal .modal-outer-box {
  max-width: 550px;
  background: #ffffff;
  -webkit-box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
}

#add-activity-modal .modal-body {
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 89px;
}

#add-activity-modal .add-activity-title {
  font-family: "Montserrat", sans-serif;
  font-size: 36px;
  font-weight: 700;
  line-height: 42px;
  letter-spacing: 0;
  text-align: center;
  margin-bottom: 30px;
}

@media screen and (max-width: 767px) {
  #add-activity-modal .add-activity-title {
    font-size: 32px;
    line-height: 37px;
  }
}

#add-activity-modal .activity-note {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-column-gap: 10px;
  column-gap: 10px;
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0;
  text-align: left;
  padding: 10px;
  -webkit-box-shadow: 0 0 4px 0 #00000040;
  box-shadow: 0 0 4px 0 #00000040;
  border-radius: 11px;
  -webkit-border-radius: 11px;
  -moz-border-radius: 11px;
  -ms-border-radius: 11px;
  -o-border-radius: 11px;
  margin-bottom: 30px;
}

#add-activity-modal .activity-note svg {
  min-width: 24px;
}

#add-activity-modal .activity-field-label {
  display: block;
  width: 100%;
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0;
  text-align: left;
  color: #000000;
}

#add-activity-modal .activity-field {
  border-bottom: 1px solid #000;
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0;
  text-align: left;
  padding: 6px 5px 11px;
  width: 100%;
  color: #000000;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}

#add-activity-modal .activity-name-box,
#add-activity-modal .activity-type-box,
#add-activity-modal .activity-session-type {
  margin-bottom: 20px;
}

#add-activity-modal .activity-type-box {
  position: relative;
}

#add-activity-modal .selected-activity-type-box {
  display: flex;
  justify-content: space-between;
  position: relative;
}

#add-activity-modal .selected-activity-type-box .location-input-wrapper {
  width: 85%;
}

#add-activity-modal .selected-activity-type-box .activity-field {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  padding-right: 40px;
}

#add-activity-modal .selected-activity-type-box .arrow-open {
  position: absolute;
  right: 20px;
  top: 13px;
}

#add-activity-modal .activity-type-list-box {
  display: none;
  position: absolute;
  top: 18px;
  left: 0;
  right: 0;
  width: 100%;
  padding: 0 5px 16px;
  background: #ffffff;
  -webkit-box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 20px 20px 11px 11px;
  z-index: 1;
}

#add-activity-modal .activity-type-header {
  height: 31px;
}

#add-activity-modal .arrow-close {
  margin-left: auto;
  margin-top: 8px;
  margin-right: 16px;
}

#add-activity-modal .activity-type-list {
  max-height: 200px;
  height: 100%;
  overflow: hidden;
  overflow-y: scroll;
}

#add-activity-modal .activity-type-list::-webkit-scrollbar {
  display: none;
}

#add-activity-modal .activity-type-item {
  margin: 10px 16px;
  cursor: pointer;
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0;
  text-align: left;
  display: block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}

#add-activity-modal .activity-session-type {
  margin-top: 14px;
}

#add-activity-modal .activity-session-type label {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0;
  text-align: left;
  color: #000000;
}

@media screen and (max-width: 389px) {
  #add-activity-modal .activity-session-type label {
    font-size: 12px;
  }

  .form-group {
    padding-left: 26px;
  }

  .form-group input:checked + label:after {
    top: 1px;
  }
}

#add-activity-modal,
.activity-session-type,
.form-group,
input:checked,
+ label:after {
  top: 1px;
}

#add-activity-modal .activity-session-type .form-group input:checked + label {
  color: #690fad;
}

#add-activity-modal .date-box,
#add-activity-modal .time-box {
  position: relative;
  margin-bottom: 25px;
}

#add-activity-modal .date-box select,
#add-activity-modal .time-box select {
  width: 100%;
  cursor: pointer;
  padding: 7px 25px 8px 14px;
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0;
  text-align: left;
  color: #000000;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-color: transparent;
}

#add-activity-modal .custom-dropdown {
  background-color: #caa8f5;
  border-radius: 7px;
  overflow: hidden;
  position: relative;
  width: 100%;
}

#add-activity-modal .custom-dropdown svg {
  position: absolute;
  top: 13px;
  right: 7px;
}

#add-activity-modal .activity-date-outer-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-column-gap: 5px;
  column-gap: 5px;
  margin-top: 2px;
}

#add-activity-modal .day,
#add-activity-modal .year,
#add-activity-modal .hour {
  max-width: 96px;
}

#add-activity-modal .month {
  max-width: 148px;
}

#add-activity-modal .minutes {
  max-width: 120px;
}

#add-activity-modal .duration {
  max-width: 124px;
}

#add-activity-modal .btn-navigation {
  position: absolute;
  right: 11px;
  top: 0;
  -webkit-box-shadow: 0 0 2px 0 #00000040;
  box-shadow: 0 0 2px 0 #00000040;
  width: 34px;
  height: 34px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  background-color: #ffffff;
  border: none;
  border-radius: 7px;
  -webkit-border-radius: 7px;
  -moz-border-radius: 7px;
  -ms-border-radius: 7px;
  -o-border-radius: 7px;
}

#add-activity-modal .location-box {
  position: relative;
  margin-bottom: 30px;
}

#add-activity-modal .location-box .activity-type-header .activity-field {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-style: italic;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0;
  text-align: left;
}

#add-activity-modal .location-box .activity-type-header .btn-navigation {
  top: 4px;
}

#add-activity-modal .location-box .activity-type-list-box {
  top: 14px;
}

#add-activity-modal .btn-add-inspire {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0;
  text-align: center;
  display: block;
  width: 100%;
  padding: 14px;
  color: #ffffff;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
}

#add-activity-modal .pop-up-btn-wrapper {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

#add-activity-modal .set-location {
  font-family: "Montserrat", sans-serif;
  font-weight: 600;
  text-align: center;
  display: inline-block;
  padding: 2px 10px;
  margin: 0 0 0 5px;
  color: #ffffff;
  border-radius: 5px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  -ms-border-radius: 5px;
  -o-border-radius: 5px;
}

#add-activity-modal .form-group input {
  visibility: hidden;
}

#add-activity-modal .show {
  display: block;
}

.location-inputs {
  padding: 1rem 0 1rem 0;
}

.location-border {
  width: 100%;
  margin: 0.5rem 0 0.5rem 0;
  border-bottom: 1px solid black;
}

.input-field-search-box input {
  width: 100%;
}

input[type="text"]:disabled {
  background: white;
}

.gm-style-iw.gm-style-iw-c {
  padding: 0;
}

.gm-style-iw-d {
  padding: 0 !important;
  overflow: hidden !important;
  background: transparent;
  border-radius: 0.5rem;
}

.gm-style-iw-d .section-body {
  margin-top: 0 !important;
}

.gm-style .gm-style-iw-d {
  overflow: hidden !important;
}

.gm-style .gm-style-iw {
  overflow: hidden !important;
  padding: 0 !important;
}

.gm-style .gm-style-iw .section-body {
  margin-top: 0 !important;
}

#add-activity-modal .date-section-main .date-box {
  max-width: 251px;
}

@media (max-width: 767px) {
  #add-activity-modal .date-section-main .date-box {
    max-width: 212px;
  }
}

@media (max-width: 374px) {
  #add-activity-modal .date-section-main .date-box {
    max-width: 179px;
  }
}

#add-activity-modal #start_time {
  width: 100%;
  cursor: pointer;
  padding: 6px 7px 7px;
  height: 35px;
  font-family: "Montserrat", sans-serif;
  font-size: 16px !important;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0;
  text-align: left;
  color: #000000;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-color: #caa8f5;
  border-radius: 7px;
}

#add-activity-modal .activity-session-type-box .form-group input:checked + label:after {
  top: 0px !important;
}
</style>
