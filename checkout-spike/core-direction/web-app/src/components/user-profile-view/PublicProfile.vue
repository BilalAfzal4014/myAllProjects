<template>
  <div>
    <social-information-user :user-detail="userDetails" :user-interests="userInterests" />
    <calendar-activity-listing v-if="userDetails.username" :username="userDetails.username" />
    <ProfileFriendCard :following="following"
                       :following-count="userDetails.following ? userDetails.following.length : 0"
                       :user-name="userDetails.firstname"
    />
    <ProfileFriendGroup :groups="groups" :user-name="userDetails.firstname" />
  </div>
</template>

<script>
import SocialInformationUser from "@/components/community/social-information-user";
import {getUserProfile} from "@/apiManager/user";
import * as toastr from "toastr";
import ProfileFriendCard from "@/partials/profile/ProfileFriendCard";
import ProfileFriendGroup from "@/partials/profile/ProfileFriendGroup";
import {getGroupsByUsername} from "@/apiManager/groups";
import CalendarActivityListing from "@/components/community/calendar-activity-listing";

export default {
  name: "PublicProfile",
  components: {CalendarActivityListing, ProfileFriendGroup, ProfileFriendCard, SocialInformationUser},
  data() {
    return {
      userDetails: {},
      userInterests: [],
      following: [],
      groups: []
    };
  },
  created() {
    this.onQueryChanged();
  },
  watch: {
    "$route.query": {
      immediate: true,
      handler: "onQueryChanged",
    },
  },
  methods: {
    onQueryChanged() {
      this.getUser();
      this.getgroups();
    },
    getUserPayload() {
      return this.$route.params.username;
    },
    getUser() {
      getUserProfile(this.getUserPayload())
        .then((response) => {
          this.userDetails = response.data;
          this.userInterests = response.data.userInterests;
          this.following = response.data.following;
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
    getGroupsPayload() {
      return {
        username: this.$route.params.username
      };
    },
    getgroups() {
      getGroupsByUsername(this.getGroupsPayload())
        .then((response) => {
          this.groups = response.data;
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    }
  }
};
</script>

<style scoped></style>