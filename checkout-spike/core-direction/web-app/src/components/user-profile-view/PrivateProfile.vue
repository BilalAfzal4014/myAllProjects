<template>
  <div>
    <social-information-user :user-detail="userDetails" :user-interests="userInterests" />
    <section id="account-notice">
      <div class="custom-container">
        <p class="account-notice-desc">
          This Account is Private.
        </p>
        <p class="account-notice-desc">
          To See <strong>{{ userDetails.firstname }}’s</strong> details and join activities together Send a <strong>“Friend
            Request”.</strong>
        </p>
      </div>
    </section>
  </div>
</template>

<script>
import SocialInformationUser from "@/components/community/social-information-user";
import {getUserProfile} from "@/apiManager/user";
import * as toastr from "toastr";

export default {
    name: "PrivateProfile",
    components: {SocialInformationUser},
    data() {
        return {
            userDetails: {},
            userInterests: []
        };
    },
    created() {
        this.getUser();
    },
    methods: {
        getUserPayload() {
            return this.$route.params.username;
        },
        getUser() {
            getUserProfile(this.getUserPayload())
                .then((response) => {
                    this.userDetails = response.data;
                    this.userInterests = response.data.userInterests;
                })
                .catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });
        }
    }
};
</script>

<style scoped>
#account-notice {
  background-color: #F1F1F1;
  padding-top: 60px;
}

#account-notice .custom-container {
  background: #FFFFFF;
  padding-top: 164px;
  padding-bottom: 178px;
}

@media (max-width: 767px) {
  #account-notice .custom-container {
    padding-top: 100px;
    padding-bottom: 90px;
  }
}

#account-notice .account-notice-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 400;
  line-height: 29px;
  letter-spacing: 0;
  text-align: center;
  margin-bottom: 10px;
}

@media (max-width: 767px) {
  #account-notice .account-notice-desc {
    font-size: 16px;
    line-height: 19px;
  }
}

#account-notice .account-notice-desc strong {
  font-weight: 600;
}
</style>