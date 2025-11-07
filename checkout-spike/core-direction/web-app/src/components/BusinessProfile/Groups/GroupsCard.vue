<template>
  <div class="groups-card">
    <div class="card-body">
      <div class="group-img-box">
        <img :alt="groupName" :src="microServicesBaseUrl+groupLogoUrl+'?optimizer=image&format=webp'" class="group-img" height="64" width="64">
      </div>
      <div class="group-content-box">
        <p class="group-card-title">
          {{ groupName }}
        </p>
        <div class="group-content-inner-box">
          <p class="group-card-desc">
            Created on {{ new Date(groupCreatedDate).toDateString() }}
          </p>
          <p class="group-card-subtitle">
            {{ groupType }} Group
          </p>
        </div>
        <ul v-if="false" class="activity-list">
          <li v-for="(activityType,index) in groupActivityTypes" :key="index" class="activity-item">
            <img :alt="activityType?.name" :src="activitiesBaseUrl+activityType?.imageName" class="activity-img"
                 height="16"
                 width="16"
            >
          </li>
        </ul>
      </div>
    </div>
    <div class="card-footer">
      <div class="participants-list-box">
        <ul v-if="groupParticipants.length > 0" class="participants-list">
          <li v-for="(participant,index) in groupParticipants" :key="index" class="participants-item" @click="groupPageLink">
            <img :alt="participant?.firstname" :src="microServicesBaseUrl+participant?.profile_picture"
                 class="participants-img"
            >
          </li>
        </ul>
      </div>
      <div class="btn-box">
        <!--        <button v-if="isGroupParticipant && groupPrivacy === 'public'" class="btn-invite">-->
        <!--          Send Invite-->
        <!--        </button>-->
        <button
          v-if="(groupPrivacy === 'private' && isGroupParticipant) || groupPrivacy === 'public'"
          class="btn-detail" @click="groupPageLink"
        >
          View More
        </button>
        <button
          v-if="groupPrivacy !== 'private' && !isGroupParticipant"
          class="btn-detail"
          @click="joinGroup"
        >
          Join
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {BASE_URLS} from "@/common/constants/constants";
import JoinGroupMixin from "@/mixin/joinGroupMixin";

export default {
  name: "GroupsCard",
  mixins: [JoinGroupMixin],
  props: {
    groupDetails: {
      type: Object
    },
    groupId: {
      type: Number,
      default: null,
    },
    groupName: {
      type: String,
      default: "",
    },
    groupCreatedDate: {
      type: String,
      default: "",
    },
    groupType: {
      type: String,
      default: "",
    },
    groupLogoUrl: {
      type: String,
      default: "",
    },
    isGroupParticipant: {
      type: Boolean,
      default: false,
    },
    groupPrivacy: {
      type: String,
      default: "",
    },
    groupActivityTypes: {
      type: Array,
      default: () => [],
    },
    groupParticipants: {
      type: Array,
      default: () => [],
    }

  },
  computed: {
    microServicesBaseUrl() {
      return BASE_URLS.ASSETS_MEDIA_MICRO_SERVICE_PREFIX;
    },
    activitiesBaseUrl() {
      return BASE_URLS.ASSETS_PREFIX_ACTIVITY;
    },
  },
  methods: {
    groupPageLink() {
      this.$router.push(`/community/group/${this.groupId}`);
    },
  }

};
</script>

<style lang="scss" scoped>
.groups-card {
  display: flex;
  flex-direction: column;
  row-gap: 16px;
  padding: 32px;
  background: #FFFFFA;
  box-shadow: 0px 12px 16px rgba(28, 4, 47, 0.08), 0px 4px 6px rgba(28, 4, 47, 0.03);
  border-radius: 8px;
  @media (max-width: 767px) {
    padding: 20px;
  }

  .card-body {
    display: flex;
    align-items: center;
    column-gap: 16px;

    .group-img-box {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      -webkit-border-radius: 50%;
      -moz-border-radius: 50%;
      -ms-border-radius: 50%;
      -o-border-radius: 50%;
      overflow: hidden;
      box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25);

      .group-img {
        min-width: 64px;
        min-height: 64px;
        max-width: 64px;
        max-height: 64px;
        width: 100%;
        height: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        object-position: center;
      }
    }

    .group-content-box {
      display: flex;
      flex-direction: column;
      row-gap: 16px;
      @media (max-width: 767px) {
        row-gap: 8px;
      }

      .group-card-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 16px;
        font-weight: 700;
        line-height: 20px;
        letter-spacing: 0em;
        text-align: left;
        width: 100%;
        height: 40px;
        color: #06070E;
        @media (max-width: 767px) {
          height: 34px;
          font-size: 14px;
          line-height: 17.07px;
        }
      }

      .group-content-inner-box {
        display: flex;
        flex-direction: column;
        row-gap: 4px;

        .group-card-desc,
        .group-card-subtitle {
          font-family: 'Montserrat', sans-serif;
          font-size: 14px;
          line-height: 17px;
          letter-spacing: 0em;
          text-align: left;
          text-transform: capitalize;
          color: #06070E;
          @media (max-width: 767px) {
            font-size: 12px;
            line-height: 15px;
          }
        }

        .group-card-desc {
          font-weight: 400;
        }

        .group-card-subtitle {
          font-weight: 600;
        }
      }

      .activity-list {
        display: flex;
        align-items: center;
        column-gap: 8px;

        .participants-img {
          width: 16px;
          height: 16px;
          @media (min-width: 768px) {
            width: 12px;
            height: 12px;
          }
        }
      }
    }
  }

  .card-footer {
    display: flex;
    align-items: center;
    column-gap: 20px;
    justify-content: space-between;

    .participants-list {
      display: flex;
      align-items: center;

      .participants-item {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -o-border-radius: 50%;
        border: 0.5px solid #FFFFFF;
        overflow: hidden;
        margin-left: -16px;
        cursor: pointer;

        &:first-child {
          margin-left: 0;
        }

        .participants-img {
          width: 32px;
          height: 32px;
          width: 100%;
          object-fit: cover;
          object-position: center;
        }
      }
    }

    .btn-box {
      display: flex;
      align-items: center;
      column-gap: 4px;
      justify-content: center;
      @media (min-width: 768px) {
        width: 100%;
      }

      .btn-invite,
      .btn-detail {
        padding: 16px 32px;
        color: #690FAD;
        background-color: #F2F5EA;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 700;
        line-height: 17px;
        letter-spacing: 0em;
        text-align: center;
        border-radius: 30px;
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        -ms-border-radius: 30px;
        -o-border-radius: 30px;
        @media (max-width: 767px) {
          font-size: 12px;
          line-height: 15px;
          padding: 12px 14px;
        }
      }

      .btn-invite {
        color: #690FAD;
        background-color: #F2F5EA;
      }

      .btn-detail {
        background-color: #690FAD;
        color: #fff;

        &:hover {
          background-color: #8C14E6;
        }

        &:active,
        &.active {
          color: #690FAD;
          background-color: #CAA8F5;
        }
      }
    }
  }
}
</style>
