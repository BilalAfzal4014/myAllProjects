<template>
  <div id="friend-search-list" class="other-user-list">
    <div class="custom-container">
      <div class="friend-section-header">
        <p class="section-title">
          {{ userName }}’s Friends ({{ followingCount }})
        </p>
      </div>
      <div class="friend-section-body">
        <div class="friend-search-results">
          <FriendCard v-for="friend in following" :key="`friend-listing-${friend.id}`" :friend="friend" class="friend-card" />
          <button class="friend-card">
            <img alt="Add Friend Icon" class="friend-img" src="../../assets/images/btn-see-more-01.png">
            <span class="profile-status-heading">
              See More
            </span>
            <img alt="" src="../../assets/images/btn-see-more-02.png">
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FriendCard from "@/partials/group/FriendCard";

export default {
  name: "ProfileFriendCard",
  components: {FriendCard},
  props: {
    following: {
      type: Array,
      default: null
    },
    userName: {
      type: String,
      default: null
    },
    followingCount: {
      type: Number,
      default: null
    }
  },
};
</script>

<style scoped>
#friend-search-list {
  margin-bottom: 60px;
}
.friend-card {
  cursor: pointer;
  padding: 10px;
  background-color: #FFFFFF;
  border-radius: 11px;
  -webkit-box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
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
  height: 248px;
}
@media screen and (max-width: 500px) {
  .friend-card {
    max-width: 100%;
  }
}
.friend-card .friend-img {
  width: 130px;
  height: 130px;
  margin: 15px auto;
  -o-object-fit: fill;
  object-fit: fill;
  -o-object-position: center;
  object-position: center;
  border-radius: 50%;
}
@media screen and (max-width: 767px) {
  .friend-card .friend-img {
    width: 117px;
    height: 117px;
  }
}
.friend-card .profile-status {
  padding: 4px;
  width: 67px;
  color: #FFFFFF;
  background: #690FAD;
  border-radius: 4.50262px;
  -webkit-border-radius: 4.50262px;
  -moz-border-radius: 4.50262px;
  -ms-border-radius: 4.50262px;
  -o-border-radius: 4.50262px;
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 500;
  line-height: 12px;
  letter-spacing: 0;
  text-align: center;
  margin-bottom: 20px;
}
.friend-card .friend-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0;
  text-align: center;
}
.friend-card .profile-status-heading{
  margin-bottom: 20px;
}
</style>