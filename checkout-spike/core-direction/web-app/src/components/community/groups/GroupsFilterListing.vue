<template>
  <section id="groups-listing">
    <div id="listing-filter">
      <div
        class="custom-container filter-box flex mx-auto justify-between items-center rounded"
      >
        <div class="input-field-search-activity-box mr-4">
          <input
            v-model="groupName"
            class="location rounded-full"
            placeholder="Search for Group name"
            type="text"
          >
          <SearchIcon />
          <!--          <div class="manual-location-field-box">-->
          <!--            <div class="manual-location-field-header">-->
          <!--              <input-->
          <!--                class="italic"-->
          <!--                placeholder="Search for group name"-->
          <!--                type="text"-->
          <!--              >-->
          <!--              <button>-->
          <!--                <SearchIcon />-->
          <!--              </button>-->
          <!--            </div>-->
          <!--            <div class="manual-location-field-body">-->
          <!--              <ul class="location-list">-->
          <!--                <li class="location-item">-->
          <!--                  Core Direction Sprints-->
          <!--                </li>-->
          <!--                <li class="location-item">-->
          <!--                  Walk & Talk-->
          <!--                </li>-->
          <!--                <li class="location-item">-->
          <!--                  Fitness Enthusiasts-->
          <!--                </li>-->
          <!--              </ul>-->
          <!--            </div>-->
          <!--          </div>-->
        </div>
        <div v-click-outside-parent-element="hideActivityTypeDropdown" class="activity-type-filter-outer-box">
          <div
            class="input-field-category-box mr-2"
            @click="showActivityTypeDropdown = !showActivityTypeDropdown"
          >
            <div class="rounded-full select" disabled>
              <span v-if="selectedActivityTypes.length" class="option">
                {{ selectedActivityTypes.slice(0, 2).toString() }}
                {{ selectedActivityTypes.length > 2 ? '+ ' + (selectedActivityTypes.length - 2) : '' }}
              </span>
              <span v-else class="disabled" value="">
                Select Activity Type
              </span>
            </div>
            <DownArrow />
            <div
              :class="`category-field-box ${
                showActivityTypeDropdown ? 'open' : 'close'
              }`"
            >
              <div class="category-field-header">
                <select class="rounded-full" disabled>
                  <option value="">
                    Select Activity Type
                  </option>
                </select>
                <DownArrow />
              </div>
              <div class="category-field-body">
                <ul class="category-list">
                  <li
                    v-for="{ id, name } in activityTypeOptions"
                    :key="`${id}`"
                    class="category-item"
                  >
                    <div class="form-group">
                      <input
                        :id="`activities-type-${id}`"
                        :checked="selectedActivityTypes.includes(name)"
                        type="checkbox"
                        @click="() => selectActivityOption(name)"
                      >
                      <label :for="`activities-type-${id}`">{{ name }}</label>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div v-click-outside-parent-element="hideCategoryDropdown" class="category-type-filter-outer-box">
          <div

            class="input-field-category-box ml-2"
            @click="showCategoryDropdown = !showCategoryDropdown"
          >
            <select class="rounded-full" disabled>
              <option value="">
                {{
                  selectedGroupCategory.key
                    ? selectedGroupCategory.key
                    : "Select Group Category"
                }}
              </option>
            </select>
            <DownArrow />
            <div
              :class="`category-field-box ${
                showCategoryDropdown ? 'open' : 'close'
              } `"
            >
              <div class="category-field-header">
                <select v-model="selectedGroupCategory.key" class="rounded-full">
                  <option value="">
                    Select Group Category
                  </option>
                </select>
                <DownArrow />
              </div>
              <div class="category-field-body">
                <ul class="category-list">
                  <li
                    class="category-item"
                    @click="
                      selectedGroupCategory = { key: 'Select All', value: '' }
                    "
                  >
                    Select All
                  </li>
                  <li
                    v-for="(value, key) in groupTypes"
                    :key="`category-option-${value}`"
                    class="category-item"
                    @click="selectedGroupCategory = { key, value }"
                  >
                    {{ key }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="search-btn-box ml-4 lg:mb-0 mb-4">
          <button class="rounded-full" @click="getFilteredGroups('search')">
            Search
          </button>
          <button class="rounded-full" @click="getFilteredGroups('reset')">
            Reset
          </button>
        </div>
      </div>
    </div>

    <div class="custom-container">
      <div class="section-header flex items-center justify-between">
        <div class="btn-back-box flex items-center">
          <router-link :to="`/community`">
            <button class="btn-back flex items-center">
              <span class="btn-back-icon-box rounded-full flex items-center justify-center">
                <svg fill="none" height="15" viewBox="0 0 9 15" width="9" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M7.61565 14.8844C7.36208 14.891 7.15649 14.7906 6.98516 14.6164C4.76472 12.4464 2.54427 10.283 0.330687 8.11297C-0.114772 7.67761 -0.107918 7.21547 0.337541 6.77342C2.54427 4.61006 4.75101 2.4534 6.9646 0.296739C7.35523 -0.0850305 7.86237 -0.0984259 8.27356 0.256553C8.4723 0.430693 8.65734 0.631624 8.82867 0.839253C9.07539 1.14735 9.04797 1.5693 8.78755 1.8774C8.73272 1.94438 8.66419 2.00465 8.60251 2.06493C6.88236 3.75276 5.16905 5.44058 3.4489 7.1351C3.1268 7.44989 3.1268 7.44989 3.45575 7.77808C5.1485 9.43242 6.84124 11.0868 8.52713 12.7411C8.65049 12.8683 8.76699 13.0023 8.86979 13.1496C8.95888 13.2769 9 13.4309 9 13.5917C8.98629 14.1007 8.15706 14.891 7.61565 14.8844Z"
                    fill="black"
                  />
                </svg>
              </span>
              Go Back
            </button>
          </router-link>
          <p class="section-title capitalize">
            {{ advanceFilter }} Groups
          </p>
        </div>
        <ul class="filter-list flex items-center">
          <li
            v-for="(item, key) in groupAdvanceFilter"
            :key="`advance-filter-${item}`"
            class="filter-item"
          >
            <button
              :class="`filter-link rounded-full ${
                advanceFilter === item ? 'active' : ''
              }`"
              @click="() => setAdvanceFilter(item)"
            >
              {{ key }}
            </button>
          </li>
        </ul>
      </div>

      <div v-if="userGroups.length > 0" class="section-body">
        <group-card
          v-for="(group, index) in userGroups"
          :key="index"
          :group="group"
        />
      </div>
      <div v-else class="no-groups">
        No Groups
      </div>
    </div>
    <Pagination :current-page="currentPage" :pagination-count="paginationCount" @setCurrentPageValue="setCurrentPage" />
  </section>
</template>

<script>
import GroupCard from "@/components/community/groups/group-card";
import {getFilteredUserGroup} from "@/apiManager/groups";
import * as toastr from "toastr";
import {getActivityType} from "@/apiManager/activities";
import {GROUP_ADVANCE_FILTER, GROUP_CATEGORY_OPTIONS, USER_GROUPS_LIMIT,} from "@/common/constants/constants";
import DownArrow from "@/svgs/wallet/down-arrow";
import SearchIcon from "@/svgs/group-listing/SearchIcon";
import {createArrayWithRange, updateMetaInformation} from "@/utils";
import Pagination from "@/partials/wallet/Pagination";

export default {
  name: "GroupsFilterListing",
  components: {Pagination, SearchIcon, DownArrow, GroupCard},
  data() {
    return {
      userGroups: [],
      activityTypeOptions: [],
      showActivityTypeDropdown: false,
      showCategoryDropdown: false,
      groupTypes: GROUP_CATEGORY_OPTIONS,
      groupAdvanceFilter: GROUP_ADVANCE_FILTER,
      selectedActivityTypes: [],
      selectedGroupCategory: {key: "", value: ""},
      groupName: "",
      advanceFilter: GROUP_ADVANCE_FILTER.JOINED,
      currentPage: 1,
      limit: USER_GROUPS_LIMIT,
      paginationCount: [],
    };
  },
  watch: {
    currentPage() {
      this.getFilteredGroups();
    }
  },
  mounted() {
    this.getFilteredGroups();
    this.getActivityTypes();
    updateMetaInformation("Browse Groups | Core Direction", "", "Browse & Join groups, connect with like-minded people in the wellness community", "Browse Groups | Core Direction", "Browse & Join groups, connect with like-minded people in the wellness community", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/group-filter");
  },
  methods: {
    setCurrentPage(pageNumber) {
      this.currentPage = pageNumber;
    },
    setAdvanceFilter(filterValue) {
      this.advanceFilter = filterValue;
      this.getFilteredGroups();
    },
    getFilteredGroupPayload() {
      return {
        group_name: this.groupName,
        group_type: this.selectedGroupCategory.value,
        activity_type_name: this.selectedActivityTypes,
        filtered_by: this.advanceFilter,
        per_page: this.limit,
        page: this.currentPage,
      };
    },
    selectActivityOption(name) {
      const indexOfName = this.selectedActivityTypes.indexOf(name);
      indexOfName >= 0
        ? this.selectedActivityTypes.splice(indexOfName, 1)
        : (this.selectedActivityTypes = [...this.selectedActivityTypes, name]);
    },
    setGroups(groups) {
      if (this.advanceFilter === this.groupAdvanceFilter.NEWEST) {
        return groups.map((group) => {
          return {...group, isNewest: true};
        });
      }
      return groups;
    },
    getFilteredGroups(filterType) {
      if (filterType === "reset") {
        this.groupName = "";
        this.selectedGroupCategory.value = "";
        this.selectedActivityTypes = [];
        this.current = 1;
        this.limit = 6;
      }
      getFilteredUserGroup(this.getFilteredGroupPayload())
        .then((response) => {
          this.userGroups = this.setGroups(response.data);
          this.paginationCount = createArrayWithRange(1, response.meta?.total_pages);
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
    getActivityTypes() {
      getActivityType()
        .then((response) => {
          this.activityTypeOptions = response;
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
    hideCategoryDropdown() {
      this.showCategoryDropdown = false;
    },
    hideActivityTypeDropdown() {
      this.showActivityTypeDropdown = false;
    }
  },
};
</script>

<style scoped>

.no-groups {
  text-align: center;
}

#groups-listing .btn-back-box {
  -webkit-column-gap: 30px;
  column-gap: 30px;
  row-gap: 30px;
}

#groups-listing .btn-back {
  margin: 0;
}

#groups-listing #listing-filter {
  margin-bottom: 30px;
}

#groups-listing #listing-filter .filter-box {
  padding-top: 18px;
  padding-bottom: 19px;
  border-radius: 0;
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  -ms-border-radius: 0;
  -o-border-radius: 0;
}

#groups-listing #listing-filter .search-btn-box {
  -webkit-column-gap: 30px;
  column-gap: 30px;
}

@media (max-width: 767px) {
  #groups-listing #listing-filter .search-btn-box {
    -webkit-column-gap: 15px;
    column-gap: 15px;
  }
}

#groups-listing #listing-filter .manual-location-field-box,
#groups-listing #listing-filter .category-field-box {
  -webkit-box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 20px 20px 11px 11px;
}

@media (min-width: 992px) {
  #groups-listing #listing-filter .input-field-search-activity-box {
    max-width: 310px;
  }

  #groups-listing #listing-filter .input-field-category-box {
    max-width: 252px;
    display: flex;
  }
}

#groups-listing #listing-filter .location-item,
#groups-listing #listing-filter .category-item {
  font-family: "Montserrat", sans-serif;
  line-height: 20px;
}

#groups-listing #listing-filter .form-group label {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  line-height: 20px;
  font-weight: 400;
  color: #000000;
}

#groups-listing #listing-filter .form-group label::before {
  -webkit-filter: invert(0%) sepia(100%) saturate(0%) hue-rotate(21deg) brightness(97%) contrast(103%);
  filter: invert(0%) sepia(100%) saturate(0%) hue-rotate(21deg) brightness(97%) contrast(103%);
}

#groups-listing #listing-filter .form-group input {
  display: none;
}

#groups-listing #listing-filter .form-group input:checked + label:after {
  top: 1px;
}

#groups-listing #listing-filter .form-group:hover label {
  color: #690fad;
}

#groups-listing #listing-filter .form-group:hover label::before {
  -webkit-filter: unset;
  filter: unset;
}

#groups-listing #listing-filter .btn-map {
  width: 34px;
  height: 34px;
  background-color: #ffffff;
  -webkit-box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
  border-radius: 7px;
  -webkit-border-radius: 7px;
  -moz-border-radius: 7px;
  -ms-border-radius: 7px;
  -o-border-radius: 7px;
}

#groups-listing .section-header {
  margin: 0;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 1rem;
  column-gap: 1rem;
  row-gap: 1rem;
}

#groups-listing .section-body {
  margin-top: 38px;
}

#groups-listing .filter-list {
  -webkit-column-gap: 13px;
  column-gap: 13px;
  row-gap: 13px;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

#groups-listing .filter-link {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0;
  text-align: center;
  color: #690fad;
  background-color: #ffffff;
  padding: 7px;
  width: 100px;
  border: 1px solid #690fad;
}

#groups-listing .filter-link:hover {
  background-color: #690fad;
  color: #ffffff;
}

#groups-listing .filter-link.active {
  background-color: #690fad;
  color: #ffffff;
}

.btn-back {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: 21px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 30px;
}

.btn-back .btn-back-icon-box {
  width: 42px;
  height: 42px;
  background-color: #FFFFFF;
  margin-right: 18px;
}

@media (max-width: 389px) {
  .btn-back {
    font-size: 14px;
  }

  .btn-back .btn-back-icon-box {
    width: 36px;
    height: 36px;
    margin-right: 10px;
  }
}

#groups-listing .open {
  display: block !important;
}

#groups-listing .close {
  display: none !important;
}

#groups-listing .category-field-body {
  max-height: 500px;
  overflow: hidden;
  overflow-y: scroll;
}

#groups-listing .category-field-body::-webkit-scrollbar {
  display: none;
}

@media (max-width: 991px) {
  .activity-type-filter-outer-box,
  .category-type-filter-outer-box {
    width: 100%;
  }

  .activity-type-filter-outer-box .input-field-category-box,
  .category-type-filter-outer-box .input-field-category-box {
    margin-left: 0;
    margin-right: 0;
  }
}

@media (min-width: 992px) {
  #groups-listing .filter-box {
    column-gap: 15px;
  }

  #groups-listing .filter-box .input-field-search-activity-box {
    width: 100%;
    max-width: 310px;
    margin: 0;
  }

  #groups-listing .filter-box .activity-type-filter-outer-box {
    width: 100%;
    max-width: 252px;
  }

  #groups-listing .filter-box .category-type-filter-outer-box {
    width: 100%;
    max-width: 252px;
  }

  #groups-listing .filter-box .category-type-filter-outer-box .input-field-category-box {
    margin: 0;
  }

  #listing-filter .filter-box .search-btn-box {
    max-width: fit-content !important;
    margin: 0;
    column-gap: 15px !important;
    justify-content: flex-end;
  }

  #listing-filter .filter-box .search-btn-box button {
    width: 100%;
    min-width: 122px;
    max-width: 122px;
    padding-left: 0;
    padding-right: 0;
    height: 42px;
  }
}
</style>