<template>
  <div>
    <GeneralModal :modal-heading="'Group Leave Modal'"
                  :modal-is-open="modal.isLeaveModalOpen"
                  :modal-title="'Group Leave'"
                  @closeModal="closeLeaveModal"
    >
      <template v-slot:modalBody>
        <leave-group-modal-confirmation @closeModal="closeLeaveModal"
                                        @leaveGroup="modalOpenSuccessfulLeave"
        />
      </template>
    </GeneralModal>
    <GeneralModal :companySlug="companySlug"
                  :modal-heading="'Group Leave Modal'"
                  :modal-is-open="modal.isModalOpenSuccessfulLeave"
                  :modal-title="'Group Leave'"
                  @closeModal="modalCloseSuccessfulLeave"
    >
      <template v-slot:modalBody>
        <group-success-cancellation-modal :companySlug="companySlug" />
      </template>
    </GeneralModal>
    <div class="group-profile-header">
      <div class="custom-container">
        <div class="group-profile-banner-box">
          <PreviewImage
            :alt="`${groupDetails?.name}`"
            :src="`${groupDetails?.cover_photo}`"
            :useSrcset="true"
            type="thumbnail"
          />
        </div>
      </div>
    </div>
    <div id="group-profile">
      <div class="group-info-box-outer-box">
        <div class="custom-container">
          <div class="group-info-box">
            <div class="modal-box-main-outer-box flex">
              <div class="group-profile-info-box flex items-start">
                <div class="group-profile-logo-box rounded-full">
                  <img
                    :src="`${getImageUrl(groupDetails?.logo)}?optimizer=image&format=webp&width=110`"
                    alt="Group Logo"
                    class="rounded-full"
                  >
                </div>
                <div
                  class="group-info-outer-box flex sm:items-center justify-between block mt-4"
                >
                  <div class="group-info-inner-box">
                    <p class="group-info-title">
                      {{ groupDetails?.name }}
                    </p>
                    <p class="group-info-date">
                      Created on
                      {{ formatDate(groupDetails?.created_date) }}
                    </p>
                    <p class="group-info-desc">
                      {{ groupDetails?.privacy }} Group
                    </p>
                  </div>
                </div>
              </div>
              <div v-if="groupDetails?.is_cod_official" class="modal-box-main">
                <div class="modal-box flex items-center">
                  <div class="activity-box-note">
                    <GroupInfoIcon />
                  </div>
                  <p class="leading">
                    This is the official group Core Direction Group.
                    <br>
                    You've been added in this group by default for selecting
                    your interest.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="custom-container flex items-center justify-between my-4">
        <button class="flex items-center justify-between font-medium btn-back" @click="$router.go(-1)">
          <span
            class="btn-back-icon-box rounded-full flex items-center justify-center"
          >
            <BackArrowIcon />
          </span>
          Go Back
        </button>
        <div class="btn-box" @click="updateGroupStatus">
          <button class="btn-joined rounded-full flex items-center sm:mb-0">
            <JoinArrowIcon v-if="groupDetails?.is_participant" />
            {{ groupDetails?.is_participant ? "Joined" : "Join" }}
          </button>
          <button
            v-if="showLeaveButton"
            class="btn-leave flex items-center justify-between mt-0"
            @click="leaveGroup"
          >
            Leave Group
            <LeaveIcon />
          </button>
        </div>
      </div>

      <div class="activity-type-filter-box">
        <div class="custom-container">
          <div class="flex items-center justify-between">
            <div class="title-box">
              <h4 class="section-title">
                Group Activity Types
              </h4>
            </div>
          </div>
          <div class="swiper-container mass-events-swiper-container my-5">
            <div id="activity-type-filter-outer-box" class="swiper-wrapper">
              <ActivityTypeCard
                v-for="activityType in groupDetails?.activity_types"
                :key="`activity-type-card-${activityType.id}`"
                :activity-type="activityType"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="friend-search-list">
      <div class="custom-container">
        <div class="friend-header">
          <p class="section-title">
            Group Participants
            <span class="found-results">({{ total }})</span>
          </p>
        </div>
        <div class="friend-section-body">
          <div class="friend-search-results">
            <ParticipantCard
              v-for="user in participants"
              :key="`friends-tile${user.id}`"
              :user="user"
              @updateUserObject="event => updateUserObject(event)"
            />
          </div>
        </div>
        <search-friend-pagination v-if="participants.length" :count="total" :limit="data.limit" :offset="data.offset"
                                  @fetch-data="getGroupDetail"
        />
      </div>
    </div>
  </div>
</template>

<script>
import ParticipantCard from "@/partials/group/ParticipantCard";
import SearchFriendPagination from "@/partials/SearchFriendPagination";
import {getGroupDetails, joinUserGroup, leaveUserGroup,} from "@/apiManager/groups";
import * as toastr from "toastr";
import DefaultGroupBannerImage from "../assets/images/event-consultancy.png";
import constants from "@/constants/constants";
import ActivityTypeCard from "@/partials/group/ActivityTypeCard";
import GroupInfoIcon from "@/svgs/group/GroupInfoIcon";
import BackArrowIcon from "@/svgs/group/BackArrowIcon";
import JoinArrowIcon from "@/svgs/group/JoinArrowIcon";
import LeaveIcon from "@/svgs/group/LeaveIcon";
import GeneralModal from "@/partials/modal/general-modal";
import LeaveGroupModalConfirmation from "@/partials/leave-group-modal-confirmation";
import GroupSuccessCancellationModal from "@/partials/group-success-cancellation-modal";
import PreviewImage from "@/components/PreviewImage";
import {updateMetaInformation} from "@/utils";

export default {
  name: "GroupDetails",
  components: {
    PreviewImage,
    GroupSuccessCancellationModal,
    LeaveGroupModalConfirmation,
    GeneralModal,
    LeaveIcon,
    JoinArrowIcon,
    BackArrowIcon,
    GroupInfoIcon,
    ActivityTypeCard,
    ParticipantCard,
    SearchFriendPagination
  },
  data() {
    return {
      groupDetails: {},
      participants: [],
      showLeaveButton: false,
      formatOptions: {month: "long", day: "numeric", year: "numeric"},
      userId: JSON.parse(localStorage.getItem("userProfile")).id,
      data: {
        limit: 12,
        offset: 0
      },
      modal: {
        isLeaveModalOpen: false,
        isModalOpenSuccessfulLeave: false
      },
      total: 0,
      companySlug: "",
    };
  },
  created() {
    this.getGroupDetail();
  },
  methods: {
    closeLeaveModal() {
      this.modal.isLeaveModalOpen = false;
    },
    modalOpenSuccessfulLeave() {
      this.closeLeaveModal();
      this.groupLeaveApiCall();
    },
    async groupLeaveApiCall() {
      try {
        const response = await leaveUserGroup(this.getLeaveGroupPayload());
        const {company_slug} = response.data;
        this.setCompanySlug(company_slug);
        this.updateGroupDetails();
      } catch (error) {
        this.showLeaveGroupError(error);
      } finally {
        this.modal.isModalOpenSuccessfulLeave = true;
      }
    },
    setCompanySlug(slug) {
      this.companySlug = slug;
    },
    showLeaveGroupError(error) {
      toastr.error(error);
    },
    updateGroupDetails() {
      this.groupDetails.is_participant = false;
      this.showLeaveButton = false;
    },
    modalCloseSuccessfulLeave() {
      this.modal.isModalOpenSuccessfulLeave = false;
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString("en-US", this.formatOptions);
    },
    getImageUrl(imageName) {
      return imageName
        ? constants.getImageUrl(imageName)
        : DefaultGroupBannerImage;
    },
    getGroupDetailPayload() {
      return {
        group_id: this.$route.params.id,
        limit: this.data.limit,
        offset: this.data.offset
      };
    },
    getJoinGroupPayload() {
      return {
        group_id: this.groupDetails.id,
        user_ids: [this.userId],
      };
    },
    getLeaveGroupPayload() {
      return {
        group_id: this.groupDetails.id,
      };
    },
    async getGroupDetail() {
      try {
        const response = await getGroupDetails(this.getGroupDetailPayload());

        if (!response.data) {
          this.handleError(response.message);
          return;
        }

        this.setGroupDetails(response.data);
      } catch (error) {
        this.handleException(error);
      }
    },
    setGroupDetails(data) {
      this.groupDetails = data.group;
      this.participants = data.group.participants;
      this.total = data.participantsCount;
      this.updateMetaData();
    },
    handleError(message) {
      toastr.error(message);
      this.$router.push("/");
    },
    handleException(error) {
      const errorMessage = error?.[0]?.response?.data?.errors?.[0]?.error || "An unexpected error occurred";
      toastr.error(errorMessage);
    },
    joinGroup() {
      joinUserGroup(this.getJoinGroupPayload())
        .then((response) => {
          this.groupDetails.is_participant = true;
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
    leaveGroup() {
      this.modal.isLeaveModalOpen = true;
    },
    updateGroupStatus() {
      this.groupDetails.is_participant
        ? (this.showLeaveButton = !this.showLeaveButton)
        : this.joinGroup();
    },
    updateUserObject(event) {
      event.type === "unfollow" ? this.updateUserToUnfollow(event.id) : this.updateUserToFollow(event.id);
    },
    getUserById(userId) {
      return this.participants.find((user) => user.id === userId);

    },


    updateUserToUnfollow(userId) {
      const user = this.getUserById(userId);
      if (!user) return;

      const index = this.participants.indexOf(user);
      this.$set(this.participants, index, {
        ...user,
        status: "unknown",
        isFriend: false
      });
    },

    updateUserToFollow(userId) {
      const user = this.getUserById(userId);
      if (!user) return;

      const index = this.participants.indexOf(user);
      this.$set(this.participants, index, {
        ...user,
        status: user.privacy === "public" ? "accepted" : "requested",
        isFriend: user.friendship === "follower" && user.privacy === "public" ? true : user.isFriend
      });
    },

    updateMetaData() {
      this.pageTitle = this.groupDetails?.name || "Group";
      this.pageImage = this.groupDetails?.cover_photo || "cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp";
      const pageUrl = window.location.href;
      updateMetaInformation(this.pageTitle + " | Core Direction", "", "Join the Challenge! Earn points and climb the leaderboard to win!", this.pageTitle + " | Core Direction", "Join the Challenge! Earn points and climb the leaderboard to win!", "https://cdn.coredirection.com/" + this.pageImage + "?optimizer=image&format=webp&width=1200&quality=80", pageUrl);
    },
  },
};
</script>

<style scoped>
@import "../assets/css/group-details.css";
</style>