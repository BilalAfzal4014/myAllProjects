<template>
  <div class="business-profile-activities-filter">
    <div class="filter-box flex  mx-auto justify-between items-center rounded">
      <provider :providers="membershipPackages" :reset-providers="resetProviders" @getProvider="getProvider" />
      <status @getStatus="getStatus" />
      <div class="search-btn-box">
        <button class=" rounded-full" @click="searchPackage">
          Search
        </button>
      </div>
      <div class="reset-btn-box">
        <button class="reset_filter rounded-full" @click="resetFilter">
          Reset All
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Provider from "./PackageSearch/Provider";
import Status from "./PackageSearch/status";

export default {
    name: "PackageSearch",
    components: {Status, Provider},
    data() {
        return {
            selectedProviders: [],
            selectedStatus: [],
            resetProviders: 0
        };

    },
    props: {
        membershipPackages: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        getProvider(providers) {
            this.selectedProviders = providers;
        },
        getStatus(value) {
            this.selectedStatus = value;
        },
        resetFilter() {
            this.resetProviders++;
            this.$emit("reset", "");
        },
        searchPackage() {
            this.$emit("search", {
                providers: this.selectedProviders,
                status: this.selectedStatus,
            });
        }


    }
};
</script>