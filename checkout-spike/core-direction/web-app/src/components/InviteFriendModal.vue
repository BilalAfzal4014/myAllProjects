<template>
  <div id="invite-friend-modal" class="custom-modal m-auto overflow-y-auto">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="setShowActivityModal">
              <cross-icon />
            </div>
          </div>
          <div class="modal-body px-3">
            <input
              v-model="filter"
              v-debounce="getFriendList"
              class="search-bar"
              placeholder="Search for friends to send invites..."
              type="text"
            >
            <ul
              v-for="(friend, index) in friendList.users"
              :key="`friend-${index}`"
              class="friend-list"
            >
              <ActivityFriend :activity-id="activityId" :friend="friend" />
            </ul>
            <span v-if="friendList.length === 0" class="no-friends">
              No following found.
            </span>
            <button class="btn-next bg-gradient" @click="setCurrentStep">
              {{ isCalendarModal ? "Go Back" : "Next" }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ActivityFriend from "@/partials/ActivityFriend";
import CrossIcon from "@/svgs/activity-modal/cross-icon";
import * as toastr from "toastr";
import {getConnectionList} from "@/apiManager/user";
import Emitter from "tiny-emitter/instance";


export default {
    name: "InviteFriendModal",
    data() {
        return {
            friendList: [],
            filter: ""
        };
    },
    props: {
        activityId: {
            type: Number,
            default: 0,
        },
        isCalendarModal: {
            type: Boolean,
            required: false,
            default: false
        }

    },
    components: {CrossIcon, ActivityFriend},
    created() {
        this.getFriendList();
    },
    methods: {
        setShowActivityModal: function () {
            this.$emit("setShowActivityModal", false);
            Emitter.emit("refetch_activity_diary_listing", "");

        },
        setCurrentStep: function () {
            if (this.isCalendarModal) {
                this.$emit("goBackToActivityCalendar");
                return false;
            }
            this.$emit("setCurrentStep", 3);
            Emitter.emit("refetch_activity_diary_listing", "");
        },
        getFriendListPayload() {
            return {
                filter: this.filter,
                limit: 50,
                offset: 0,
            };
        },
        getFriendList() {
            getConnectionList(this.getFriendListPayload())
                .then((response) => {
                    this.friendList = response.data;
                })
                .catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });
        },
    },
};
</script>

<style scoped>
#invite-friend-modal .modal-outer-box {
  max-width: 550px;
  background: #ffffff;
  -webkit-box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
}

#invite-friend-modal .modal-body {
  max-width: 450px;
  margin-left: auto;
  margin-right: auto;
}

@media screen and (max-width: 500px) {
  #invite-friend-modal .modal-body {
    max-width: 320px;
  }
}

#invite-friend-modal .search-bar {
  width: 100%;
  background-color: #f1f1f1;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 37px;
  padding: 14px 27px;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}

@media screen and (max-width: 767px) {
  #invite-friend-modal .search-bar {
    padding: 14px 15px;
    font-size: 12px;
    line-height: 14px;
  }
}

#invite-friend-modal .friend-item {
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
  #invite-friend-modal .friend-item {
    -webkit-column-gap: 15px;
    column-gap: 15px;
  }
}

@media screen and (max-width: 389px) {
  #invite-friend-modal .friend-item {
    -webkit-column-gap: 10px;
    column-gap: 10px;
  }
}

#invite-friend-modal .friend-img {
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
  #invite-friend-modal .friend-img {
    min-width: 60px;
    min-height: 60px;
    max-width: 60px;
    max-height: 60px;
  }
}

#invite-friend-modal .friend-info {
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
  #invite-friend-modal .user-status {
    font-size: 10px;
    line-height: 12px;
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
  #invite-friend-modal .btn-invited {
    width: 95px !important;
    font-size: 12px !important;
    line-height: 14px !important;
  }
}

#invite-friend-modal .btn-next {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0;
  text-align: center;
  color: #ffffff;
  display: block;
  width: 100%;
  max-width: 350px;
  padding: 14px;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  margin: 30px auto 75px;
}

@media screen and (max-width: 767px) {
  #invite-friend-modal .btn-next {
    max-width: 215px;
    font-size: 12px;
    font-weight: 600;
    line-height: 14px;
    padding: 12px;
    margin: 30px auto 60px;
  }
}

.no-friends {
  display: flex;
  justify-content: center;
}
</style>
