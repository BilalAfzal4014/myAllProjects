<template>
  <div>
    <div class="addition-info-box mx-auto">
      <user-profile-header :username="user.username" />
      <p class="addition-info-desc">
        Let others get to know you better! You can do this later
      </p>
      <user-avatar-profile @changePicture="uploadImagePath" />
      <div class="bio-box">
        <p class="section-title">
          Add Bio
        </p>
        <div class="bio-field-box">
          <custom-textarea v-model="user.biography" :maxlength="350" :overflow-y="false" :resizable="false"
                           class-names="bio-input-field" input-id="bio" placeholder="Tell use about yourself"
                           @badWordErrorChange="handleBadWordErrorChange" @keydown="wordsCount"
          />
        </div>
        <div class="counter-text text-right">
          {{ bioWordsCount }}/{{ totalWordsBiography }}
        </div>
      </div>

      <set-user-privacy @onChangePrivacy="setUerPrivacy" />
      <div v-if="!isShowUserSearch" class="btn-box flex items-center">
        <button :class="`btn-navigate ${isNextButtonDisabled ? 'btn-disabled':''}`"
                @click="scrollToAddFriend"
        >
          Next
        </button>
      </div>
      <add-friend v-show="isShowUserSearch"
                  id="addUser"
                  :users="userLists"
                  @onSearch="searchFriend"
                  @onSendRequest="sendFriendRequest"
      />

      <div v-if="isShowUserSearch" class="btn-box flex items-center">
        <button :class="`btn-submit ${hasUserSearchFriend ? '':'btn-disabled'}`" @click="showInterest">
          Next
        </button>
        <p class="btn-skip cursor-pointer" @click="showInterest">
          Skip
        </p>
      </div>

      <set-user-interest
        id="addInterest"
        :class="`${isShowUserInterest ? '':'hidden '}`"
        :interests="interestLists"
        @onUpdateUserInterest="updateUserInterest"
      />

      <div :class="`btn-box flex items-center ${isShowUserInterest ? '':'hidden '}`">
        <button :class="`btn-submit ${isNextButtonDisabled ? 'btn-disabled':''}`" :disabled="isNextButtonDisabled"
                @click="updateProfile"
        >
          Save and proceed
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import UserProfileHeader from "@/components/signup/set-user-profile/user-profile-header";
import UserAvatarProfile from "@/components/signup/set-user-profile/set-user-avatar";
import SetUserPrivacy from "@/components/signup/set-user-profile/set-user-privacy";
import AddFriend from "@/components/signup/set-user-profile/add-friend";
import SetUserInterest from "@/components/signup/set-user-profile/set-user-interest";
import {searchUserProfile, sendFollowRequest, updateProfile} from "@/apiManager/user";
import {getInterestList} from "@/apiManager/interest";
import {scroller} from "vue-scrollto/src/scrollTo";
import CustomTextarea from "@/components/form/custom-textarea";

export default {
    name: "SetUserProfile",
    components: {
        CustomTextarea,
        SetUserInterest,
        AddFriend,
        SetUserPrivacy,
        UserAvatarProfile,
        UserProfileHeader,
    },

    data() {
        return {
            user: this.$store.getters.getStoreUserProfileGetters(),
            computedCustomFields: {
                user: {
                    privacy: "private"
                }
            },
            isShowUserSearch: false,
            isShowUserInterest: false,
            totalWordsBiography: 350,
            userFilterPayload: {
                filter: "",
                limit: 5,
                offset: 0,
            },
            userLists: [],
            interestLists: [],
            isImageUploaded: false,
            hasUserSearchFriend: false,
            isNextButtonDisabled: false,

        };
    },
    mounted() {
        this.getInterestLists();
    },
    computed: {
        bioWordsCount() {
            return this.user.biography.length;
        },
    },

    methods: {
        handleBadWordErrorChange(error) {
            this.isNextButtonDisabled = !!error;
        },
        getInterestLists() {
            getInterestList()
                .then((response) => {
                    let interestObject = response.data;
                    interestObject.sort((a, b) => (a.name.toUpperCase() > b.name.toUpperCase() ? 1 : -1));
                    this.interestLists = interestObject.map((interest) => ({
                        ...interest,
                        isInterestSelected: false,
                    }));

                })
                .catch((error) => toastr.error(error));
        },
        scrollToAddFriend() {
            if (this.isNextButtonDisabled) return false;
            this.isShowUserSearch = true;
            setTimeout(() => {
                const scrollToUser = scroller();
                scrollToUser("#addUser");
            }, 200);

        },
        showInterest() {
            this.isShowUserInterest = true;
            setTimeout(() => {
                const scrollToUser = scroller();
                scrollToUser("#addInterest");
            }, 300);
        },
        uploadImagePath(imagePath) {
            this.isImageUploaded = true;
            this.user.picture = imagePath;
        },
        wordsCount(event) {
            const words = event.target.value.trim().split(/\s+/);
            if (words.length >= this.totalWordsBiography) {
                event.preventDefault();
            }
        },
        setUerPrivacy(privacy) {
            this.computedCustomFields.user.privacy = privacy;
        },
        searchFriend(searchKeyword) {
            this.userFilterPayload["filter"] = searchKeyword;
            this.userFilterPayload["limit"] = 5;
            this.userFilterPayload["offset"] = 0;
            this.searchUser();
        },
        searchUser() {
            searchUserProfile(this.userFilterPayload)
                .then((response) => {
                    let userObject = response.data.users;
                    userObject.map((user) => ({...user, isRequestSent: false}));

                    this.userLists = userObject;
                    this.hasUserSearchFriend = true;
                })
                .catch((error) => toastr.error(error));
        },
        sendFriendRequest(userId) {
            sendFollowRequest({followingId: userId})
                .then((response) => {
                    const modifiedUserIndex = this.userLists.findIndex(
                        (user) => user.id === userId
                    );
                    if (this.userLists[modifiedUserIndex].privacy === "public")
                        this.userLists[modifiedUserIndex].status = "accepted";
                    if (this.userLists[modifiedUserIndex].privacy === "private")
                        this.userLists[modifiedUserIndex].status = "requested";
                })
                .catch((error) => toastr.error(error));
        },
        updateUserInterest(interest) {
            if (!this.user.interests.includes(interest.id)) {
                this.addInterest(interest);
                return;
            }
            this.removeInterest(interest);
        },
        addInterest(selectedInterest) {
            this.user.interests.push(selectedInterest.id);
            let objectIndex = this.interestLists.findIndex(
                (interest) => interest.id == selectedInterest.id
            );
            this.$set(this.interestLists[objectIndex], "isInterestSelected", true);
        },
        removeInterest(selectedInterest) {
            let objectIndex = this.interestLists.findIndex(
                (interest) => interest.id == selectedInterest.id
            );
            this.$set(this.interestLists[objectIndex], "isInterestSelected", false);
            const interestListIndex = this.user.interests.indexOf(
                selectedInterest.id
            );
            this.user.interests.splice(interestListIndex, 1);
        },
        updateProfile() {
            delete this.user["id"];
            this.user.privacy = this.computedCustomFields.user.privacy;
            this.user.is_profile_completed = true;
            updateProfile(this.user)
                .then((response) => {
                    if (response.statusCode == 200) {
                        toastr.success("Profile has been updated successfully.");
                        this.$store.dispatch("removeUserProfileInformationAction");
                        let payload = response.data.user;
                        const genderMap = {
                            "m": "Male",
                            "f": "Female",
                            "u": "Unlisted"
                        };

                        payload["gender"] = genderMap[payload["gender"]] || payload["gender"];

                        this.$store.dispatch(
                            "setUserProfileInformationAction",
                            payload
                        );
                        this.$router.push("/community");
                    }
                })
                .catch((error) => {
                    toastr.error(error);
                });
        },
    },
};
</script>
<style scoped>
.counter-text {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: right;
  color: #06070e;
  text-align: right;
}
</style>
