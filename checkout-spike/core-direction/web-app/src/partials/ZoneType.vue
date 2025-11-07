<template>
  <div v-click-outside-parent-element="hideZoneDropdown" class="input-field-search-box">
    <div class="input-wrapper" @click="isZoneTypeDropdownShow = !isZoneTypeDropdownShow">
      <input :placeholder="zoneText" class="rounded-full" type="text">
      <svg class="zone-type" fill="none" height="16" viewBox="0 0 12 16" width="12" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M6 0C2.69118 0 0 2.55676 0 5.70031C0 6.73559 0.489706 7.84631 0.507353 7.89661C0.666176 8.25288 0.975 8.80615 1.2 9.12889L5.31618 15.0472C5.48382 15.2903 5.73529 15.4286 6 15.4286C6.26471 15.4286 6.51618 15.2903 6.68382 15.0472L10.8 9.12469C11.025 8.80196 11.3338 8.24869 11.4926 7.89242C11.5147 7.84631 12 6.7314 12 5.69612C12 2.55676 9.30882 0 6 0ZM6 8.71813C4.24853 8.71813 2.82353 7.3643 2.82353 5.70031C2.82353 4.03632 4.24853 2.6825 6 2.6825C7.75147 2.6825 9.17647 4.03632 9.17647 5.70031C9.17647 7.3643 7.75147 8.71813 6 8.71813Z"
          fill="black"
        />
      </svg>
    </div>
    <div :class="`manual-location-field-box ${isZoneTypeDropdownShow ? 'block' :'hidden'}`">
      <div class="manual-location-field-body">
        <ul class="location-list">
          <li v-for="zone in zones" :key="zone.id" class="location-item">
            <div class="form-group">
              <input :id="zone.label" :value="zone.id" type="checkbox" @click="addZone(zone)">
              <label :for="zone.label" class="">{{ zone.title }}</label>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import * as toastr from "toastr";
import emitter from "tiny-emitter/instance";

export default {
  name: "ZoneType",
  data() {
    return {
      zones: [],
      selectedZones: [],
      zoneText: "Select Zone Type",
      isZoneTypeDropdownShow: false,
    };
  },
  props: {
    companyId: {
      type: Number,
      required: false
    }
  },
  created() {
    if (this.companyId) {
      this.fetchZones();
    }
    emitter.on("reset_filter", () => {
      this.zones = [];
      this.selectedZones = [];
      this.zoneText = "Select Zone Type";
      this.fetchZones();
    });
  },
  watch: {
    companyId(newVal) {
      if (newVal) {
        this.fetchZones();
      }
    },
  },
  methods: {
    addZone(zone) {
      const zoneIndex = this.selectedZones.findIndex(function (zon) {
        return zon.id === zone.id;
      });
      if (zoneIndex !== -1) {
        this.selectedZones = this.selectedZones.filter((zon) => zon.id !== zone.id);
      } else {
        this.selectedZones.push(zone);
      }
      if (this.selectedZones.length === 0) {
        this.zoneText = "Select Zone Type";
      } else if (this.selectedZones.length === 1) {
        this.zoneText = this.selectedZones[0].title;
      } else {
        this.zoneText = `${this.selectedZones[0].title} +${this.selectedZones.length - 1} more...`;
      }
      this.$emit("clicked", zone.id);
    },
    async fetchZones() {
      try {
        const response = await this.oldApi("get", this.getZoneTypeUrl(), true);
        this.zones = this.transformZonesData(response.data);
      } catch (error) {
        toastr.error(this.getErrorMessage(error));
      }
    },
    getZoneTypeUrl() {
      return `${this.constants.getUrl("getZoneType")}/${this.companyId}`;
    },
    transformZonesData(zones) {
      return zones.map(zone => ({
        ...zone,
        label: `zone${zone.id}`,
      }));
    },
    getErrorMessage(error) {
      if (error && error.response && error.response.data && error.response.data.errors && error.response.data.errors.length > 0) {
        return error.response.data.errors[0].error;
      }
      return "An error occurred";
    },
    hideZoneDropdown() {
      this.isZoneTypeDropdownShow = false;
    }
  }

};
</script>

<style scoped>
.hidden {
  display: none !important;
}

.block {
  display: block !important;
}
</style>