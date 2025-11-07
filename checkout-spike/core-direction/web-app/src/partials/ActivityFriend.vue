<template>
  <li class="friend-item">
    <div class="friend-img-box">
      <!-- eslint-disable-next-line vue/this-in-template -->
      <img :src="this.constants.getImageUrl(friend.profile_picture)" :alt="`${friend.firstname} ${friend.lastname} Profile Image`" class="friend-img">
    </div>
    <div class="friend-info">
      <p class="user-name">
        {{ friend.firstname }} {{ friend.lastname }}
      </p>
      <p class="user-type">
        {{ friend.username }}
      </p>
      <p class="user-status">
        I'm up for challenges
      </p>
    </div>
    <div class="friend-invitation-status">
      <button
        :class="`${invited === 'invited' ? 'btn-invited' : 'btn-invite'}`"
        :disabled="inviteSent"
        @click="inviteFriend"
      >
        {{ invited === "invited" ? "Invite Sent" : "Send Invite" }}
      </button>
    </div>
  </li>
</template>
<script>
import * as toastr from "toastr";
import {sendActivityInvite} from "@/apiManager/activities";

export default {
    name: "ActivityFriend",
    data() {
        return {
            invited: "",
            inviteSent: false
        };
    },
    props: {
        friend: {type: Object, default: null},
        activityId: {type: Number, default: 0},
    },
    methods: {
        getInviteFriendPayload() {
            return {
                userDiaryActivityId: this.activityId,
                userId: this.friend.following_id ? this.friend.following_id : this.friend.id,
            };
        },
        inviteFriend: function () {
            sendActivityInvite(this.getInviteFriendPayload())
                .then((response) => {
                    this.invited = response.data.status;
                    this.inviteSent = true;
                    toastr.success("Invite is sent");
                })
                .catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });
        },
    },
};
</script>

<style scoped>
.friend-item {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  width: 100%;
  -webkit-column-gap: 10px;
  column-gap: 10px;
  margin-bottom: 15px;
}

@media screen and (max-width: 767px) {
  .friend-item {
    -webkit-column-gap: 15px;
    column-gap: 15px;
  }
}

.friend-img {
  min-width: 88px;
  min-height: 88px;
  max-width: 88px;
  max-height: 88px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
}

@media screen and (max-width: 767px) {
  .friend-img {
    min-width: 60px;
    min-height: 60px;
    max-width: 60px;
    max-height: 60px;
  }
}

.friend-info {
  width: 100%;
  word-break: break-all;
}

#invite-friend-modal .user-name {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 8px;
}

@media screen and (max-width: 767px) {
  .user-name {
    font-size: 12px !important;
    line-height: 14px !important;
  }
}

#invite-friend-modal .user-type {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 8px;
}

@media screen and (max-width: 767px) {
  #invite-friend-modal .user-type {
    font-size: 12px !important;
    line-height: 14px !important;
  }
}

#invite-friend-modal .user-status {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 600;
  line-height: 14px;
  letter-spacing: 0;
  text-align: left;
}

@media screen and (max-width: 767px) {
  .user-status {
    font-size: 10px !important;
    line-height: 12px !important;
  }
}

#invite-friend-modal .btn-invited {
  cursor: not-allowed;
  width: 119px;
  color: #ffffff;
  background-color: #caa8f5;
  padding: 11px;
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0;
  text-align: center;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
}

@media screen and (max-width: 767px) {
  #invite-friend-modal .btn-invited {
    width: 95px !important;
    font-size: 12px !important;
    line-height: 14px !important;
  }
}

#invite-friend-modal .btn-invite {
  cursor: pointer;
  width: 119px;
  color: #ffffff;
  background-color: #690fad;
  padding: 11px;
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 19px;
  letter-spacing: 0;
  text-align: center;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
}

@media screen and (max-width: 767px) {
  .btn-invite {
    width: 95px !important;
    font-size: 12px !important;
    line-height: 14px !important;
  }
}
</style>
