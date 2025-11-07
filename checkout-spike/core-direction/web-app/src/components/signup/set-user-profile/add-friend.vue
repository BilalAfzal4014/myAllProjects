<template>
  <div class="add-friend-box">
    <p class="section-title">
      Follow Friends
    </p>
    <div class="friend-search-header">
      <input v-model="search" class="friend-input-field rounded-full"
             placeholder="Search for friends to send invites..." type="text" @keyup.enter="searchFriend"
      >
      <button class="btn-find-friend rounded-full" @click="searchFriend">
        Search
      </button>
    </div>
    <div class="friend-search-body">
      <ul class="friend-list">
        <li v-for="user in users" :key="user.id" class="friend-item flex items-center">
          <img v-if="user.profile_picture" :src="constants.getImageUrl(user.profile_picture)" alt=""
               class="friend-img rounded-full"
          >
          <img v-else alt="" class="friend-img rounded-full" src="../../../assets/images/user-avatar-01.png">
          <div class="friend-info">
            <p class="user-handle">
              {{ user.username }}
            </p>
            <p class="user-name">
              {{ user.firstname }} {{ user.lastname }}
            </p>
            <p class="user-status">
              {{ user.privacy }}
            </p>
          </div>
          <button
            :class="`${ (user.status === 'accepted' || user.status === 'requested') ? 'btn-add-friend': ''} rounded-full btn-action`"
            :disabled="setDisableButton(user.status)" @click="sendFriendRequest(user.id)"
          >
            <span v-if=" user.privacy === 'public'">
              {{ user.status === "accepted" ? "Following" : "Follow" }}
            </span>
            <span v-if=" user.privacy === 'private'">
              {{ user.status === "requested" ? "Requested" : "Send Request" }}
            </span>
          </button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
    name: "AddFriend",
    props: {
        users: {
            type: Array,
            required: true,
        }
    },
    data() {
        return {
            search: "",
        };
    },
    methods: {
        setDisableButton(userStatus) {
            return userStatus === "accepted" || userStatus === "requested";
        },
        searchFriend() {
            this.$emit("onSearch", this.search);
        },
        sendFriendRequest(userId) {
            this.$emit("onSendRequest", userId);
        }

    }
};
</script>

<style scoped>

</style>