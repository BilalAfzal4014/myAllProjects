<template>
  <div v-click-outside-parent-element="hideProviderDropdown" class="input-field-category-box">
    <div @click="isProviderShown = !isProviderShown">
      <select class=" rounded-full" disabled>
        <option value="">
          Select Provider Name
        </option>
      </select>
      <down-arrow />
    </div>
    <div :class="`category-field-box ${isProviderShown ? '':'hidden'}`">
      <div class="category-field-body">
        <ul class="category-list">
          <li class="category-item">
            <div class="form-group">
              <input
                id="activities-type"
                :checked="areAllProvidersSelected"
                type="checkbox"
                @change="selectAll"
              >
              <label for="activities-type">Select All</label>
            </div>
          </li>

          <li
            v-for="(provider, index) in clonedProviders"
            :key="'provider' + index"
            class="category-item"
          >
            <div class="form-group">
              <input
                :id="'provider' + index"
                v-model="selectedProvider"
                :value="provider.company_id"
                class="providers"
                type="checkbox"
                @change="emitSelectedProvider"
              >
              <label :for="'provider' + index">{{ provider.company_title }}</label>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import DownArrow from "../../../svgs/wallet/down-arrow";
import _ from "lodash";

export default {
    name: "Provider",
    components: {DownArrow},
    data() {
        return {
            selectedProvider: [],
            isProviderShown: false,
            clonedProviders: [],
        };
    },
    props: {
        resetProviders: {
            type: Number,
            required: true
        },
        providers: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        areAllProvidersSelected() {
            return this.selectedProvider.length === this.clonedProviders.length;
        },
    },
    watch: {
        resetProviders: function () {
            this.clearSelectedCheckboxes();
        },
        providers: {
            deep: true,
            handler() {
                this.cloneUniqueProviders();
            },
        },
    },

    methods: {
        removeDuplicates(providers) {
            return _.uniqBy(providers, "company_title");
        },
        cloneProviders(providers) {
            return _.cloneDeep(providers);
        },
        cloneUniqueProviders() {
            if (!this.clonedProviders.length) {
                const uniqueProviders = this.removeDuplicates(this.providers);
                this.clonedProviders = this.cloneProviders(uniqueProviders);
            }
        },
        clearSelectedCheckboxes() {
            this.selectedProvider = [];
        },
        selectAll() {
            if (this.areAllProvidersSelected) {
                this.selectedProvider = [];
            } else {
                this.selectedProvider = this.clonedProviders.map((provider) => provider.company_id);
            }
            this.$emit("getProvider", this.selectedProvider);
        },
        emitSelectedProvider() {
            this.$emit("getProvider", this.selectedProvider);
        },
        hideProviderDropdown() {
            this.isProviderShown = false;
        },


    }


};
</script>

<style scoped>

</style>