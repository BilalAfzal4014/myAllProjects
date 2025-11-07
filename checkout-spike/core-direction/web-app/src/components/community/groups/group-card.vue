<template>
  <router-link :to="`/community/group/${group.id}`">
    <div class="group-card">
      <div class="group-card-body flex items-center justify-between flex-wrap">
        <div v-if="group.isNewest" class="tab-box">
          <span class="newest-tag">Newest</span>
        </div>
        <div class="group-info-box flex">
          <div class="img-box">
            <PreviewImage :alt="`${group.name} logo`" :src="group.logo" :use-srcset="true" class="group-img"
                          type="logo"
            />
          </div>
          <div class="info-box">
            <p class="group-card-title">
              {{ group.name }}
            </p>
            <p class="group-card-desc">
              <strong>{{ groupTypeMap[group.group_type] }}</strong>
              Created on {{ formatDate }}
            </p>
            <group-activity-list :activity-types="limitedActivityTypes" />
          </div>
        </div>
        <div class="participants-list-box">
          <group-participant-list :friends="group.participants" />
        </div>
        <div class="action-button-box flex items-center flex-wrap">
          <router-link :to="groupPageLink">
            <button class="btn-primary rounded-full" @click="onButtonClick">
              {{ actionButtonText }}
            </button>
          </router-link>
        </div>
      </div>
    </div>
  </router-link>
</template>

<script>
import GroupActivityList from "@/components/community/groups/group-activity-list";
import GroupParticipantList from "@/components/community/groups/group-participant-list";
import {GROUP_TYPE} from "@/common/constants/constants";
import {joinUserGroup} from "@/apiManager/groups";
import * as toastr from "toastr";
import PreviewImage from "@/components/PreviewImage";

export default {
  name: "GroupCard",
  components: {PreviewImage, GroupParticipantList, GroupActivityList},
  data() {
    return {
      userId: JSON.parse(localStorage.getItem("userProfile")).id,
    };
  },
  computed: {
    formatDate() {
      return this.date.toLocaleDateString("en-US", this.formatOptions);
    },
    groupTypeMap() {
      return {
        [GROUP_TYPE.ACTIVITY]: "Activity Group",
        [GROUP_TYPE.BUSINESS]: "Business Group",
        [GROUP_TYPE.CORPORATE]: "Corporate Group",
      };
    },
    groupPageLink() {
      return this.group.is_participant ? `/community/group/${this.group.id}` : "";
    },
    actionButtonText() {
      return this.group.is_participant ? "View more" : "Join";
    },
    date() {
      return new Date(this.group.created_date);
    },
    formatOptions() {
      return {month: "long", day: "numeric", year: "numeric"};
    },
    limitedActivityTypes() {
      return this.group.activity_types.filter((_, index) => index < 4);
    },

  },
  props: {
    group: {
      type: Object,
      required: true
    }
  },
  methods: {
    getJoinGroupPayload() {
      return {
        group_id: this.group.id,
        user_ids: [this.userId],
      };
    },
    onButtonClick() {
      if (!this.group.is_participant) {
        this.joinGroup();
      }
    },
    async joinGroup() {
      try {
        const payload = this.getJoinGroupPayload();
        await joinUserGroup(payload);
        this.group.is_participant = true;
      } catch (error) {
        toastr.error(error[0].response.data.errors[0].error);
      }
    },
  }
};
</script>

<style scoped>

</style>