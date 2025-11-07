<template>
  <div>
    <BusinessProfileFilter :companyId="companyId"
                           @reset="$emit('reset')"
                           @searched="searchFilter"
    />
    <div class="business-profile-activities-page">
      <business-profile-activities :activities="activities" :company_slug="company_slug"
                                   @updateActivityStatus="changeActivityStatus"
      />
    </div>
  </div>
</template>

<script>
import BusinessProfileActivities from "../../BusinessProfileActivites";
import BusinessProfileFilter from "@/partials/BusinessProfileFilter";

export default {
  name: "Activities",
  components: {BusinessProfileFilter, BusinessProfileActivities},
  props: {
    activities: {
      type: Array,
      required: true
    },
    company_slug: {
      type: String,
      required: true,
    },
    companyId: {
      type: Number,
      required: false
    }

  },
  methods: {
    searchFilter(payload) {
      this.$emit("searched", payload);
    },
    changeActivityStatus(data) {
      this.$emit("updateActivityStatus", data);
    }
  }
};
</script>

<style scoped>
.business-profile-activities-page {
  padding-bottom: 100px;
}
</style>