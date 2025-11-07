<template>
  <section id="groups">
    <div class="custom-container">
      <group-header />
      <div v-if="userGroups.length" class="section-body">
        <group-card
          v-for="(group, index) in userGroups"
          :key="index"
          :group="group"
        />
      </div>
      <span v-else class="no-group">
        No Group found
      </span>
    </div>
    <Pagination :current-page="currentPage" :pagination-count="paginationCount" @setCurrentPageValue="setCurrentPage" />
  </section>
</template>

<script>
import GroupHeader from "@/components/community/groups/group-header";
import GroupCard from "@/components/community/groups/group-card";
import * as toastr from "toastr";
import {getMyGroups} from "@/apiManager/groups";
import {USER_GROUPS_LIMIT} from "@/common/constants/constants";
import {createArrayWithRange} from "@/utils";
import Pagination from "@/partials/wallet/Pagination";

export default {
  name: "ExploreGroups",
  components: {Pagination, GroupCard, GroupHeader},
  data() {
    return {
      userGroups: [],
      currentPage: 1,
      paginationCount: []
    };
  },
  mounted() {
    this.fetchUserGroups();
  },
  watch: {
    currentPage: {
      immediate: true,
      handler: "fetchUserGroups"
    }
  },
  methods: {
    async setCurrentPage(pageNumber) {
      this.currentPage = pageNumber;
      await this.fetchUserGroups();
    },
    getMyGroupsPayload() {
      return {
        per_page: USER_GROUPS_LIMIT,
        page: this.currentPage
      };
    },
    async fetchUserGroups() {
      try {
        const response = await getMyGroups(this.getMyGroupsPayload());
        this.userGroups = response.data;
        if (this.userGroups.length) {
          this.paginationCount = createArrayWithRange(1, response.meta.total_pages);
        }
      } catch (error) {
        toastr.error(error.message);
      }
    }
  }
};
</script>

<style scoped>

.no-group {
  display: block;
  text-align: center;
}
@media (min-width: 768px) {
  #groups{
    margin-bottom: 60px;
  }
}

</style>