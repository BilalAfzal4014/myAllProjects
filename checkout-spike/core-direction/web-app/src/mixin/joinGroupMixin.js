import { mapGetters } from "vuex";
import { joinUserGroup } from "@/apiManager/groups";
import * as toastr from "toastr";

const joinGroupMixin = {
  computed: {
    ...mapGetters({
      userProfile: "getStoreUserProfileGetters",
    }),
  },
  methods: {
    getJoinGroupPayload() {
      return {
        group_id: this.groupDetails.id,
        user_ids: [this.userProfile().userId],
      };
    },
    async joinGroup() {
      try {
        await joinUserGroup(this.getJoinGroupPayload());
        this.groupDetails.is_participant = true;
        toastr.success("Group has been joined successfully.");
      } catch (error) {
        toastr.error(this.extractErrorMessage(error));
      }
    },
    extractErrorMessage(error) {
      return error &&
        error[0] &&
        error[0].response &&
        error[0].response.data &&
        error[0].response.data.errors &&
        error[0].response.data.errors[0] &&
        error[0].response.data.errors[0].error
        ? error[0].response.data.errors[0].error
        : "Something went wrong.";
    },
  },
};

export default joinGroupMixin;
