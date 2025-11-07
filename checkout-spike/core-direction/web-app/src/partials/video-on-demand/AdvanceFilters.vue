<template>
  <div>
    <section v-show="showAdvanceFilter" id="on-demand-advance-filters">
      <div class="custom-container">
        <div class="row mx-auto flex flex-col">
          <div class="col-12 duration-box">
            <div class="col-12 duration-box">
              <ul class="filter-list flex items-center flex-wrap">
                <li class="filter-title">
                  Duration:
                </li>
                <li
                  v-for="item in durations"
                  :key="`duration-item-${item.name}`"
                  class="filter-item"
                >
                  <button
                    :class="`filter-link rounded-full ${
                      selectedDurations.includes(item) ? 'active' : ''
                    }`"
                    @click="setDurations(item)"
                  >
                    {{ item.name }}
                  </button>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-12 required-equipment-box">
            <ul class="filter-list flex items-center flex-wrap">
              <li class="filter-title">
                Equipment Required:
              </li>
              <li
                v-for="item in equipments"
                :key="`equipment-item-${item.name}`"
                class="filter-item"
              >
                <button
                  :class="`filter-link rounded-full ${
                    selectedEquipments.includes(item) ? 'active' : ''
                  }`"
                  @click="setEquipments(item)"
                >
                  {{ item.name }}
                </button>
              </li>
            </ul>
          </div>
          <div class="col-12 type-box">
            <ul class="filter-list flex items-center flex-wrap">
              <li class="filter-title">
                Type:
              </li>
              <li
                v-for="item in types"
                :key="`type-item-${item.name}`"
                class="filter-item"
              >
                <button
                  :class="`filter-link rounded-full ${
                    selectedTypes.includes(item) ? 'active' : ''
                  }`"
                  @click="setTypes(item)"
                >
                  {{ item.name }}
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <section id="advance-filter">
      <div class="custom-container">
        <div
          class="advance-filter-menu-btn-box flex items-center justify-center"
          @click="showAdvanceFilter = !showAdvanceFilter"
        >
          <button class="advance-menu-filter-btn flex items-center">
            {{ showAdvanceFilter ? "Hide" : "Apply" }} Filters
            <FilterUpArrow v-if="showAdvanceFilter" />
            <FilterDownArrow v-else />
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import FilterUpArrow from "@/svgs/video-on-demand/FilterUpArrow";
import FilterDownArrow from "@/svgs/video-on-demand/FilterDownArrow";
import {getAdvanceFilterList} from "@/apiManager/video-on-demand";
import * as toastr from "toastr";

export default {
    name: "AdvanceFilters",
    components: {FilterDownArrow, FilterUpArrow},
    props: {
        selectedDurations: {
            type: Array,
            default: null
        },
        selectedEquipments: {
            type: Array,
            default: null
        },
        selectedTypes: {
            type: Array,
            default: null
        }
    },
    data() {
        return {
            showAdvanceFilter: false,
            durations: [],
            equipments: [],
            types: [],
        };
    },
    created() {
        this.getAdvanceFilters();
    },
    methods: {
        setDurations(value) {
            this.$emit("setDurations", value);
        },
        setEquipments(value) {
            this.$emit("setEquipments", value);
        },
        setTypes(value) {
            this.$emit("setTypes", value);
        },
        getAdvanceFilters() {
            getAdvanceFilterList().then((response) => {
                this.durations = response.data.durations;
                this.equipments = this.sortDataByName(response.data.equipments);
                ;
                this.types = this.sortDataByName(response.data.types);
            })
                .catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });
        },
        sortDataByName(data) {
            return data.sort((a, b) => (a.name.toUpperCase() > b.name.toUpperCase() ? 1 : -1));
        }
    }
};
</script>

<style scoped>
#on-demand-advance-filters {
  padding-top: 10px;
  padding-bottom: 30px;
}

#on-demand-advance-filters .filter-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-right: clamp(12px, 2.222vw, 32px);
  padding: 10px;
}

#on-demand-advance-filters .filter-list {
  row-gap: 17px;
  -webkit-column-gap: 15px;
  column-gap: 15px;
}

#on-demand-advance-filters .filter-link:hover {
  background-color: #CAA8F5;
}

#on-demand-advance-filters .filter-link {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
  padding: 8px 16px;
  color: #06070E;
  background-color: #F1F1F1;
  text-transform: capitalize;
}


#on-demand-advance-filters .filter-link.active {
  color: #f2f5ea;
  background-color: #690fad;
}

#on-demand-advance-filters .row {
  width: 100%;
  max-width: 1180px;
  row-gap: 30px;
  -webkit-column-gap: 60px;
  column-gap: 60px;
}

#on-demand-advance-filters .col {
  width: 100%;
}

/*@media (min-width: 768px) {*/
/*  #on-demand-advance-filters .duration-box {*/
/*    max-width: 399px;*/
/*  }*/

/*  #on-demand-advance-filters .required-equipment-box {*/
/*    max-width: 475px;*/
/*  }*/

/*  #on-demand-advance-filters .type-box {*/
/*    max-width: 178px;*/
/*  }*/
/*}*/
</style>