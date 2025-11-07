<template>
  <main id="main">
    <SubscribeCorePremiumModal v-if="showSubscribeCorePremium" @close="closeSubscribeCorePremium" />
    <social-information-user v-if="user" :user-detail="user" :user-interests="interests" />
    <calendar-activity-listing v-if="user?.username" :username="user?.username" />
    <explore-friends />
    <corporates-list />
    <community-challenges />
    <explore-groups />
  </main>
</template>

<script>
import SocialInformationUser from "@/components/community/social-information-user";
import CalendarActivityListing from "@/components/community/calendar-activity-listing";
import ExploreFriends from "@/components/community/explore-friends";
import ExploreGroups from "@/components/community/explore-groups";
import {getUserCommunityInformation} from "@/apiManager/user";
import * as toastr from "toastr";
import CommunityChallenges from "@/components/community/CommunityChallenges";
import CorporatesList from "@/components/CorporateUser/CorporatesList.vue";
import SubscribeCorePremiumModal from "@/components/modals/SubscribeCorePremiumModal";

export default {
  name: "CommunityPage",
  components: {
    SubscribeCorePremiumModal,
    CorporatesList,
    ExploreGroups,
    ExploreFriends,
    CalendarActivityListing,
    SocialInformationUser,
    CommunityChallenges
  },
  data() {
    return {
      user: null,
      interests: [],
      showSubscribeCorePremium: false,
    };
  },
  watch: {
    "$route.query": {
      immediate: true,
      handler: "fetchCommunityInfo",
    },
  },
  created() {
    this.fetchCommunityInfo();
  },
  methods: {
    showSubscriptionModal() {
      if (this.isLogin()) {
        if (!localStorage.getItem("hasVisitedPaymentPage")) {
          this.showSubscribeCorePremium = true;
          localStorage.setItem("hasVisitedPaymentPage", "true");
        }
      }
    },
    closeSubscribeCorePremium() {
      this.showSubscribeCorePremium = false;
    },
    isLogin() {
      return !!this.$store.getters.getStoreTokenGetters;
    },
    async fetchCommunityInfo() {
      try {
        const response = await getUserCommunityInformation();
        this.user = response.user;
        this.interests = response.interests;
        if (!this.user.isPremiumUser) {
          this.showSubscriptionModal();
        }
      } catch (error) {
        toastr.error(error.message);
      }
    }
  }
};
</script>

<style>
.group-card {
  position: relative;
  overflow: hidden;
  padding: 37px;
  background: #ffffff;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 11px;
  margin-bottom: 30px;
}

.group-card .tab-box {
  -webkit-clip-path: polygon(0 0, 0% 100%, 100% 0);
  clip-path: polygon(0 0, 0% 100%, 100% 0);
  left: 0;
  top: 0;
  position: absolute;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 100px;
  height: 100px;
  background-color: #690fad;
}

.group-card .newest-tag {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 600;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
  color: #ffffff;
  -webkit-transform: rotate(-45deg);
  transform: rotate(-45deg);
  margin-top: -30px;
  margin-left: -30px;
}

@media (max-width: 411px) {
  .group-card {
    padding: 30px;
  }
}

.group-card .group-card-body {
  width: 100%;
  max-width: 1060px;
  margin-left: auto;
  margin-right: auto;
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 2rem;
}

@media (max-width: 411px) {
  .group-card .group-card-body {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    row-gap: 0;
    flex-wrap: wrap;
  }
}

@media (min-width: 768px) {
  .group-card .group-info-box {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
  }
}

@media (max-width: 767px) {
  .group-card .group-info-box {
    -webkit-column-gap: 1rem;
    column-gap: 1rem;
    row-gap: 1rem;
  }
}

@media (max-width: 411px) {
  .group-card .group-info-box {
    row-gap: 15px;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
  }
}

.group-card .img-box {
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
}

@media (min-width: 768px) {
  .group-card .img-box {
    margin-right: 36px;
    width: 126px;
    max-width: 126px;
    height: 126px;
    max-height: 126px;
  }
}

@media (max-width: 767px) {
  .group-card .img-box {
    width: 110px;
    max-width: 110px;
    height: 110px;
    max-height: 110px;
  }
}

@media (max-width: 411px) {
  .group-card .img-box {
    width: 110px;
    max-width: 110px;
    height: 110px;
    max-height: 110px;
    margin: auto;
  }
}

.group-card .group-img {
  width: 100%;
  max-width: 126px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  -o-object-fit: cover;
  object-fit: cover;
}

@media (min-width: 768px) {
  .group-card .group-img {
    margin-right: 36px;
    width: 126px;
    max-width: 126px;
    height: 126px;
    max-height: 126px;
  }
}

@media (max-width: 767px) {
  .group-card .group-img {
    width: 110px;
    max-width: 110px;
    height: 110px;
    max-height: 110px;
  }
}

@media (max-width: 411px) {
  .group-card .group-img {
    width: 110px;
    max-width: 110px;
    height: 110px;
    max-height: 110px;
  }
}

.group-card .info-box {
  width: 100%;
  max-width: 320px;
}

.group-card .group-card-title {
  font-family: "Montserrat", sans-serif;
  font-size: 24px;
  font-weight: 700;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 5px;
}

@media (max-width: 411px) {
  .group-card .group-card-title {
    text-align: center;
    font-size: 18px;
    line-height: 22px;
  }
}

.group-card .group-card-desc {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 17px;
}

.group-card .group-card-desc strong {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  margin-right: 15px;
}

@media (max-width: 411px) {
  .group-card .group-card-desc {
    margin-bottom: 20px;
    text-align: center;
  }

  .group-card .group-card-desc strong {
    margin-right: 10px;
    font-size: 14px;
    line-height: 17px;
  }
}

.group-card .activity-list {
  -webkit-column-gap: 15px;
  column-gap: 15px;
  row-gap: 15px;
}

@media (max-width: 411px) {
  .group-card .activity-list {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
}

.group-card .activity-img {
  height: 100%;
  max-height: 26px;
  max-width: 26px;
}

.group-card .participants-list-box {
  width: 100%;
  max-width: 216px;
}

@media (max-width: 411px) {
  .group-card .participants-list {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    margin-top: 20px;
    margin-bottom: 35px;
  }
}

.group-card .participants-item {
  margin-right: -12px;
}

@media (max-width: 411px) {
  .group-card .participants-item {
    margin-right: -10px;
  }
}

.group-card .participants-img {
  min-width: 46px;
  max-width: 46px;
  min-height: 46px;
  max-height: 46px;
  border: 1px solid #ffffff;
}

@media (max-width: 411px) {
  .group-card .participants-img {
    min-width: 36px;
    max-width: 36px;
    min-height: 36px;
    max-height: 36px;
  }
}

.group-card .btn-invite-friend {
  min-width: 46px;
  max-width: 46px;
  min-height: 46px;
  max-height: 46px;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  row-gap: 2px;
  font-family: "Montserrat", sans-serif;
  font-size: 9px;
  font-weight: 500;
  line-height: 11px;
  letter-spacing: 0em;
  text-align: center;
  background-color: #690fad;
  color: #f2f5ea;
}

@media (max-width: 411px) {
  .group-card .btn-invite-friend {
    min-width: 36px;
    max-width: 36px;
    min-height: 36px;
    max-height: 36px;
    font-size: 7px;
    line-height: 8px;
  }
}

.group-card .action-button-box {
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
}

@media (max-width: 411px) {
  .group-card .action-button-box {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
}

.group-card .btn-primary {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  padding: 11px 35px;
  text-align: left;
  color: #f2f5ea;
  background-color: #690fad;
}

.group-card .activity-img-box {
  background-color: #690fad;
  padding: 21px;
}

.group-card .friend-activity-img-box {
  background-color: #f2f5ea;
  border: 1.5px solid #690fad;
  padding: 24px;
}

.group-card .friend-activity-img-box .group-icon-box {
  padding: 0;
  background: transparent;
  right: -10px;
  bottom: -4px;
  -webkit-box-shadow: unset;
  box-shadow: unset;
}
</style>
