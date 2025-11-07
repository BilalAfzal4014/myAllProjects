<template>
  <div class="custom-container group-card-container">
    <div class="section-body">
      <group-card
        v-for="(group, index) in businessGroups"
        :key="index"
        :group="group"
      />
    </div>
  </div>
</template>

<script>
import GroupCard from "@/components/community/groups/group-card";
import {getBusinessGroups} from "@/apiManager/groups";
import * as toastr from "toastr";

export default {
  name: "CompanyGroup",
  components: {GroupCard},
  data() {
    return {
      businessGroups: []
    };
  },
  watch: {
    companyId(newValue){
      if(newValue && this.$store.state.token){
        this.getBusinessGroupList();
      }
    }
  },
  props: {
    companyId: {
      type: Number,
      required: false
    }
  },
  beforeMount() {
    if (this.companyId && this.$store.state.token) {
      this.getBusinessGroupList();
    }
  },
  methods: {
    getBusinessGroupsPayload() {
      return {
        company_id: parseInt(this.companyId)
      };
    },
    getBusinessGroupList() {
      getBusinessGroups(this.getBusinessGroupsPayload())
        .then((response) => {
          this.businessGroups = response.data;
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
  }
};
</script>

<style scoped>
.group-card-container {
  padding-bottom: 100px;
}
</style>