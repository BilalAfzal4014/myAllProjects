<template>
  <button>
    <div class="btn-filter-activity-type">
      <div class="btn-filter-activity-type-icon-box">
        <img :src="`${getActivityTypeUrl()}`" alt="activity img">
      </div>
      <p class="btn-filter-activity-type-content-box">
        {{ activityType.name }}
      </p>
    </div>
  </button>
</template>
<script>
import constants from "@/constants/constants";

export default {
  name: "ActivityTypeCard",
  props: {
    activityType: {
      type: Object,
      default: null,
    },
  },
  methods: {
    getActivityTypeUrl: function () {
      return constants.getImageUrl("activity/" + this.activityType.imageName);
    },
  },
};
</script>

<style scoped>
.btn-filter-activity-type {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  padding: 10px;
  gap: 10px;
  background: #ffffff;
  -webkit-box-shadow: 0px 21.7045px 39.4626px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 21.7045px 39.4626px rgba(0, 0, 0, 0.1);
  border-radius: 11px;
}

.btn-filter-activity-type .btn-filter-activity-type-icon-box svg,
.btn-filter-activity-type .btn-filter-activity-type-icon-box img {
  max-width: 50px;
  margin-left: 0 !important;
}

.btn-filter-activity-type .btn-filter-activity-type-icon-box svg,
.btn-filter-activity-type .btn-filter-activity-type-icon-box path {
  fill: #000000;
}

.btn-filter-activity-type .btn-filter-activity-type-content-box {
  font-family: "Montserrat";
  font-style: normal;
  font-weight: 500;
  font-size: 12px;
  line-height: 14px;
}

.btn-filter-activity-type:hover {
  background: #690fad;
}

.btn-filter-activity-type:hover .btn-filter-activity-type-icon-box svg,
.btn-filter-activity-type:hover .btn-filter-activity-type-icon-box path {
  fill: #ffffff;
}

.btn-filter-activity-type:hover .btn-filter-activity-type-content-box {
  color: #ffffff;
}

.btn-filter.active,
.btn-filter-activity-type.active {
  background: #690fad;
}

.btn-filter.active .btn-filter-icon-box svg,
.btn-filter.active .btn-filter-icon-box path,
.btn-filter.active .btn-filter-activity-type-icon-box svg,
.btn-filter.active .btn-filter-activity-type-icon-box path,
.btn-filter-activity-type.active .btn-filter-icon-box svg,
.btn-filter-activity-type.active .btn-filter-icon-box path,
.btn-filter-activity-type.active .btn-filter-activity-type-icon-box svg,
.btn-filter-activity-type.active .btn-filter-activity-type-icon-box path {
  fill: #ffffff;
}

.btn-filter.active .btn-filter-content-box,
.btn-filter.active .btn-filter-activity-type-content-box,
.btn-filter-activity-type.active .btn-filter-content-box,
.btn-filter-activity-type.active .btn-filter-activity-type-content-box {
  color: #ffffff;
}
</style>
