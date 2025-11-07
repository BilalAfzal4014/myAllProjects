<template>
  <main id="main">
    <community-search-friend @filter="getConnections" />
    <section id="friend-search-list">
      <div class="sub-container">
        <div class="friend-section-header">
          <p class="section-title">
            {{ data.filter ? "Results" : this.$route.query.type === 'followers' ? 'Followers' : 'Following' }}
            <span class="found-results"> {{ `(${total})` }}</span>
          </p>
          <button class="btn-back" @click="$router.push('/community')">
            <span class="icon-box"><go-back-arrow /></span>
            Go Back
          </button>
        </div>
        <div class="friend-section-body">
          <div v-if="connectionList.length" class="friend-search-results">
            <search-friend-card v-for="(connection,index) in processedConnectionList" :key="index"
                                :connection-detail="connection"
                                @updateUserObject="event => updateUserObject(event)"
            />
          </div>
          <span v-else>
            No Record Found
          </span>
        </div>
      </div>
    </section>
    <search-friend-pagination v-if="connectionList.length" :count="total" :limit="data.limit" :offset="data.offset"
                              @fetch-data="getConnections"
    />
  </main>
</template>

<script>
import CommunitySearchFriend from "@/components/community/search-friend/community-search-friend";
import SearchFriendCard from "@/components/community/search-friend/search-friend-card";
import GoBackArrow from "@/svgs/go-back-arrow";
import {getConnectionList, getMyFollowers} from "@/apiManager/user";
import SearchFriendPagination from "@/partials/SearchFriendPagination";
import * as toastr from "toastr";
import {updateMetaInformation} from "@/utils";

export default {
  name: "SearchFriend",
  components: {GoBackArrow, SearchFriendCard, CommunitySearchFriend, SearchFriendPagination},
  data() {
    return {
      connectionList: [],
      total: 0,
      data: {
        filter: "",
        limit: 20,
        offset: 0
      }
    };
  },
  mounted() {
    if (this.$route.query.type === "followers") {
      this.getUserFollowers();
    } else {
      this.getConnections();
    }
    updateMetaInformation("Followers & Following | Core Direction", "", "View your Folllowers & Following", "Followers & Following | Core Direction", "View your Folllowers & Following", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/community/friend");
  },
  computed: {
    processedConnectionList() {
      if (this.$route.query.type === "followers") {
        return this.connectionList.map(connection => ({
          ...connection,
          id: connection.follower_id
        }));
      } else if (this.$route.query.type === "following") {
        return this.connectionList.map(connection => ({
          ...connection,
          id: connection.following_id
        }));
      } else {
        return this.connectionList.map(connection => ({
          ...connection,
          id: connection.id ?? connection.following_id
        }));
      }
    }
  },
  methods: {
    async getConnections() {
      try {
        const {username} = this.$route.query;
        const requestData = username ? {...this.data, username} : {...this.data};
        const response = await getConnectionList(requestData);
        this.total = this.data.filter ? response?.data?.totalUsersCount : response?.data?.totalCount;
        this.connectionList = response.data.users;
      } catch (error) {
        const errorMessage = error?.[0]?.response?.data?.errors?.[0]?.error;
        if (errorMessage) {
          toastr.error(errorMessage);
        }

      }
    },
    updateUserObject(event) {
      event.type === "unfollow" ? this.updateUserToUnfollow(event.id) : this.updateUserToFollow(event.id);
    },
    getUserById(userId) {
      if (this.$route.query.type === "followers") {
        return this.connectionList.find((user) => user.follower_id === userId);
      } else if (this.$route.query.type === "following") {
        return this.connectionList.find((user) => user.following_id === userId);
      } else {
        return this.connectionList.find((user) => user.id === userId || user.following_id === userId);
      }
    },


    updateUserToUnfollow(userId) {
      const user = this.getUserById(userId);
      if (!user) return;

      const index = this.connectionList.indexOf(user);
      this.$set(this.connectionList, index, {
        ...user,
        status: "unknown",
        isFriend: false
      });
    },

    updateUserToFollow(userId) {
      const user = this.getUserById(userId);
      if (!user) return;

      const index = this.connectionList.indexOf(user);
      this.$set(this.connectionList, index, {
        ...user,
        status: user.privacy === "public" ? "accepted" : "requested",
        isFriend: user.friendship === "follower" && user.privacy === "public" ? true : user.isFriend
      });
    },
    async getUserFollowers() {
      try {
        const {username} = this.$route.query;
        const queryParams = username ? `?username=${username}` : "";
        const response = await getMyFollowers(queryParams);
        this.total = response.data.users.length;
        this.connectionList = response.data.users;
      } catch (error) {
        toastr.error(error);
      }
    }

  }
};
</script>

<style>
#friend-filter {
  padding-left: 10px;
  padding-right: 10px;
}

@media screen and (max-width: 767px) {
  #friend-filter {
    margin-top: 40px;
    margin-bottom: 40px;
  }
}

@media screen and (min-width: 768px) {
  #friend-filter {
    margin-top: 60px;
    margin-bottom: 35px;
  }
}

#friend-filter .custom-container {
  padding-left: 10px;
  padding-right: 10px;
  background-color: #FFFFFF;
  -webkit-box-shadow: 0px 22px 40px 0px #0000001A;
  box-shadow: 0px 22px 40px 0px #0000001A;
}

@media screen and (max-width: 767px) {
  #friend-filter .custom-container {
    padding-top: 22px;
    padding-bottom: 21px;
  }
}

@media screen and (min-width: 768px) {
  #friend-filter .custom-container {
    padding-top: 18px;
    padding-bottom: 19px;
  }
}

#friend-filter .sub-container {
  padding-left: 0px;
  padding-right: 0px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

@media screen and (max-width: 767px) {
  #friend-filter .sub-container {
    -webkit-column-gap: 10px;
    column-gap: 10px;
  }
}

@media screen and (min-width: 768px) {
  #friend-filter .sub-container {
    -webkit-column-gap: 25px;
    column-gap: 25px;
  }
}

#friend-filter .search-friend-box {
  width: 100%;
  position: relative;
}

#friend-filter .search-friend-box svg {
  position: absolute;
  top: 15px;
  right: 15px;
}

@media screen and (max-width: 767px) {
  #friend-filter .search-friend-box svg {
    top: 11px;
  }
}

#friend-filter .search-list-box {
  display: none;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  z-index: 1;
  background-color: #FFFFFF;
  overflow: hidden;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 20px 20px 11px 11px;
  -webkit-border-radius: 20px 20px 11px 11px;
  -moz-border-radius: 20px 20px 11px 11px;
  -ms-border-radius: 20px 20px 11px 11px;
  -o-border-radius: 20px 20px 11px 11px;
}

#friend-filter .search-list-box .friend-search-field {
  background-color: #FFFFFF;
  font-style: italic;
}

#friend-filter .search-list-box .search-list {
  border-top: 1px solid #000;
  margin-left: 6px;
  margin-right: 6px;
  padding-top: 9px;
}

#friend-filter .search-list-box .search-item {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  width: 100%;
  margin-bottom: 15px;
  cursor: pointer;
}

#friend-filter .search-list-box .search-item:hover {
  color: #690FAD;
}

#friend-filter .search-list-box .search-item.active {
  color: #690FAD;
}

#friend-filter .search-list-box .search-friend-image {
  min-width: 30px;
  max-width: 30px;
  min-height: 30px;
  max-height: 30px;
  -o-object-fit: cover;
  object-fit: cover;
  -o-object-position: center;
  object-position: center;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  margin-right: 20px;
  margin-left: 15px;
}

#friend-filter .search-list-box .search-friend-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

#friend-filter .search-list-box.show {
  display: block;
}

#friend-filter .friend-search-field {
  width: 100%;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  padding: 12px 16px;
  padding-right: 35px;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  background-color: #F1F1F1;
  color: #000000;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}

@media screen and (max-width: 767px) {
  #friend-filter .friend-search-field {
    font-size: 12px;
    line-height: 15px;
    padding: 9px 16px;
    padding-right: 35px;
    height: 37px;
  }
}

#friend-filter .btn-search-friend {
  padding: 11px 33px;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  color: #FFFFFF;
  background-color: #690FAD;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
}

@media screen and (max-width: 767px) {
  #friend-filter .btn-search-friend {
    font-size: 12px;
    line-height: 15px;
    padding: 11px 26px;
    height: 37px;
  }
}

@media screen and (min-width: 1060px) {
  #friend-filter + #friend-search-list {
    padding-bottom: 115px;
  }

  #friend-search-list .sub-container {
    padding: 0;
  }
}

@media screen and (max-width: 159px) {
  #friend-filter + #friend-search-list {
    padding-bottom: 60px;
  }
}

#friend-search-list .friend-section-header {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
}

@media screen and (max-width: 767px) {
  #friend-search-list .friend-section-header {
    margin-bottom: 30px;
  }
}

@media screen and (min-width: 768px) {
  #friend-search-list .friend-section-header {
    margin-bottom: 57px;
  }
}

#friend-search-list .section-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 500;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: left;
}

#friend-search-list .section-title .found-results {
  font-weight: 400;
  margin-left: 15px;
}

@media screen and (max-width: 767px) {
  #friend-search-list .section-title {
    font-size: 18px;
    line-height: 22px;
  }
}

#friend-search-list .btn-back {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: 21px;
  letter-spacing: 0em;
  text-align: right;
}

@media screen and (max-width: 767px) {
  #friend-search-list .btn-back {
    font-size: 14px;
    line-height: 17px;
  }
}

#friend-search-list .btn-back .icon-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 42px;
  height: 42px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  background-color: #FFFFFF;
}

#friend-search-list .friend-search-results {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
  -webkit-column-gap: 30px;
  column-gap: 30px;
  row-gap: 30px;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}

@media screen and (max-width: 767px) {
  #friend-search-list .friend-search-results {
    -webkit-column-gap: 15px;
    column-gap: 15px;
    row-gap: 15px;
  }
}

.friend-card {
  cursor: pointer;
  padding: 10px;
  background-color: #FFFFFF;
  border-radius: 10px;
  -webkit-border-radius: 10px;
  -moz-border-radius: 10px;
  -ms-border-radius: 10px;
  -o-border-radius: 10px;
  -webkit-box-shadow: 0px 3.71106px 3.71106px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 3.71106px 3.71106px rgba(0, 0, 0, 0.25);
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: start;
  -ms-flex-pack: start;
  justify-content: flex-start;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  width: 100%;
  max-width: 186px;
  height: 199px;
}

@media screen and (max-width: 500px) {
  .friend-card {
    max-width: 100%;
  }
}

.friend-card .friend-img {
  width: 100px;
  height: 100px;
  margin: 20px auto 19px;
  -o-object-fit: fill;
  object-fit: fill;
  -o-object-position: center;
  object-position: center;
  border-radius: 50%;
}

@media screen and (max-width: 767px) {
  .friend-card .friend-img {
    width: 94px;
    height: 94px;
    margin: 16px auto 17px;
  }
}

.friend-card .friend-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
}

</style>