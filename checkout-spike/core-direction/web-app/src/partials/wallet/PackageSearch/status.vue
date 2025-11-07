<template>
  <div
    v-click-outside-parent-element="hideStatusDropdown"
    class="input-field-search-box"
  >
    <div @click="isStatusShown = !isStatusShown">
      <select class="rounded-full" disabled>
        <option value="">
          Select Status
        </option>
      </select>
      <down-arrow />
    </div>
    <div :class="`manual-location-field-box ${isStatusShown ? '' : 'hidden'}`">
      <div class="manual-location-field-body">
        <ul class="location-list">
          <li class="location-item">
            <div class="form-group">
              <input
                id="selectAll"
                :checked="areAllStatusesSelected"
                type="checkbox"
                @change="toggleAllStatuses"
              >
              <label for="selectAll">Select All</label>
            </div>
          </li>
          <li
            v-for="(stat, index) in status"
            :key="index"
            class="location-item"
          >
            <div class="form-group">
              <input
                :id="stat"
                v-model="selectedStatus"
                :value="stat"
                type="checkbox"
                @change="emitSelectedStatus"
              >
              <label :for="stat">{{ stat }}</label>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import DownArrow from "@/components/signup/down-arrow";

export default {
    name: "Status",
    components: {DownArrow},
    data() {
        return {
            status: ["Active", "Expired"],
            selectedStatus: [],
            isStatusShown: false,
        };
    },
    computed: {
        areAllStatusesSelected() {
            return this.selectedStatus.length === this.status.length;
        },
    },
    methods: {
        toggleAllStatuses() {
            if (this.areAllStatusesSelected) {
                this.selectedStatus = [];
            } else {
                this.selectedStatus = [...this.status];
            }
            this.emitSelectedStatus();
        },
        emitSelectedStatus() {
            this.$emit("getStatus", this.selectedStatus);
        },
        hideStatusDropdown() {
            this.isStatusShown = false;
        },
    },
};
</script>
