<template>
  <section id="groups">
    <div class="custom-container">
      <div class="section-header flex items-center justify-between">
        <div class="title-box">
          <h4 class="section-title capitalize">
            {{ userGroupsTitle }}
          </h4>
        </div>
      </div>
      <div class="section-body friend-groups">
        <div
          v-for="(group, groupIndex) in groups"
          :key="`group-${group.id}`"
          class="group-card"
        >
          <router-link :to="`/community/group/${group.id}`">
            <div class="group-card-body flex items-center justify-between">
              <div class="group-info-box flex">
                <div class="img-box">
                  <PreviewImage :alt="`${group.name} banner`" :src="group.cover_photo" :use-srcset="true"
                                class="group-img"
                                type="logo"
                  />
                </div>
                <div class="info-box">
                  <p class="group-card-title">
                    {{ group.name }}
                  </p>
                  <p class="group-card-desc capitalize">
                    <strong>{{ group.group_type }} Group</strong> Created on
                    {{ formatLocaleDate(group.created_date) }}
                  </p>
                </div>
              </div>
              <div class="participants-list-box">
                <ul class="activity-list flex items-start">
                  <li
                    v-for="activity in reducedActivityTypes(groupIndex)"
                    :key="`${group.id}-group-activity-type-${activity.id}`"
                    class="activity-item"
                  >
                    <img
                      :src="getActivityTypeUrl(activity.imageName)"
                      alt="Group Activity Type"
                      class="activity-img"
                    >
                  </li>
                </ul>
                <ul class="participants-list flex items-center">
                  <li
                    v-for="participant in getParticipants(group.participants)"
                    :key="`group-card-${participant.id}`"
                    class="participants-item"
                  >
                    <img
                      :src="`${getProfileImageUrl(participant.imageName)}`"
                      alt="Participant image"
                      class="participants-img rounded-full"
                    >
                  </li>
                  <li
                    v-if="group.participants.length > participantsToShow"
                    class="participants-item"
                  >
                    <button
                      class="btn-invite-friend flex items-center justify-center rounded-full"
                    >
                      {{ getParticipantsCount(group.participants) }}+
                    </button>
                  </li>
                </ul>
              </div>
              <div class="action-button-box flex items-center flex-wrap">
                <button class="btn-primary rounded-full">
                  {{ group.is_participant ? "Joined" : "Join" }}
                </button>
              </div>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import constants from "@/constants/constants";
import DefaultImage from "@/assets/images/default_profile_img.png";
import PreviewImage from "@/components/PreviewImage";

export default {
  name: "ProfileFriendGroup",
  components: {PreviewImage},
  props: {
    groups: {
      type: Array,
      default: null,
    },
    userName: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      name: this.userName,
      groupListing: this.groups,
      participantsToShow: 5,
      activityTypesToShow: 4,
      formatOptions: {month: "long", day: "numeric", year: "numeric"},
      userId: JSON.parse(localStorage.getItem("userProfile")).id,
    };
  },
  computed: {
    userGroupsTitle() {
      return `${this.userName}'s Groups`;
    },
  },
  methods: {
    reducedActivityTypes(groupIndex) {
      return this.groups[groupIndex].activity_types.slice(
        0,
        this.activityTypesToShow
      );
    },
    formatLocaleDate(date) {
      const groupCreatedDate = new Date(date);
      return groupCreatedDate.toLocaleDateString("en-US", this.formatOptions);
    },
    getActivityTypeUrl: function (imageName) {
      return constants.getImageUrl("activity/" + imageName);
    },
    getProfileImageUrl(imageName) {
      return imageName ? constants.getImageUrl(imageName) : DefaultImage;
    },
    getParticipants(participants) {
      const allParticipants = participants;
      return allParticipants.filter(
        (item, index) => index < this.participantsToShow
      );
    },
    getParticipantsCount(participants) {
      return participants.length - this.participantsToShow;
    },
    getJoinGroupPayload(groupId) {
      return {
        group_id: groupId,
        user_ids: [this.userId],
      };
    },
  },
};
</script>

<style scoped>
#groups .friend-groups {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
  -webkit-column-gap: 30px;
  column-gap: 30px;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}

@media (min-width: 412px) {
  #groups .friend-groups .group-card {
    padding: 0;
    max-width: 360px;
  }

  #groups .friend-groups .group-card .group-card-body {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-column-gap: 0;
    column-gap: 0;
    row-gap: 0;
  }

  #groups .friend-groups .group-card .group-info-box {
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    width: 100%;
  }

  #groups .friend-groups .group-card .img-box {
    margin-right: 0;
    width: 100%;
    max-width: 100%;
    max-height: 126px;
  }

  #groups .friend-groups .group-card .group-img {
    width: 100%;
    max-width: 100%;
    border-radius: 11px;
    height: 100%;
    max-height: 126px;
    -o-object-fit: cover;
    object-fit: cover;
    -o-object-position: center;
    object-position: center;
  }

  #groups .friend-groups .group-card .info-box {
    width: 100%;
    max-width: 100%;
    padding: 15px 19px 30px;
  }

  #groups .friend-groups .group-card .group-card-title {
    font-size: 16px;
    line-height: 20px;
  }

  #groups .friend-groups .group-card .group-card-desc {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: reverse;
    -ms-flex-direction: column-reverse;
    flex-direction: column-reverse;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    row-gap: 4px;
    font-family: "Montserrat", sans-serif;
    font-size: 12px;
    font-weight: 400;
    line-height: 15px;
    letter-spacing: 0;
    text-align: left;
    margin-bottom: 0;
  }

  #groups .friend-groups .group-card .group-card-desc strong {
    font-family: "Montserrat", sans-serif;
    font-size: 12px;
    font-weight: 700;
    line-height: 15px;
    letter-spacing: 0;
    text-align: left;
  }

  #groups .friend-groups .group-card .activity-list {
    column-gap: 10px;
    -webkit-column-gap: 10px;
    row-gap: 10px;
    padding: 0 0 0 20px;
  }

  #groups .friend-groups .group-card .activity-img {
    max-width: 18px;
    height: 18px;
  }

  #groups .friend-groups .participants-list {
    margin-right: 25px;
  }

  #groups .friend-groups .group-card .participants-list-box {
    width: 100%;
    max-width: 100%;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: space-between;
  }

  #groups .friend-groups .group-card .participants-img,
  #groups .friend-groups .group-card .btn-invite-friend {
    min-width: 30px;
    max-width: 30px;
    min-height: 30px;
    max-height: 30px;
  }

  #groups .friend-groups .group-card .btn-invite-friend {
    font-family: "Montserrat", sans-serif;
    font-size: 12px;
    font-weight: 600;
    line-height: 15px;
    letter-spacing: 0;
    text-align: center;
  }

  #groups .friend-groups .action-button-box {
    padding: 30px 15px 30px;
  }

  #groups .friend-groups .action-button-box .btn-primary {
    width: 100%;
    text-align: center;
    min-width: 185px;
    max-width: 185px;
  }
}

@media (min-width: 768px) {
  #groups {
    margin-bottom: 60px;
  }
}
</style>
