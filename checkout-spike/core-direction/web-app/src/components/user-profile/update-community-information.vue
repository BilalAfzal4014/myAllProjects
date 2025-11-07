<template>
  <div class="custom-container">
    <div class="community-detail-box mx-auto">
      <div class="account-privacy-box flex items-center">
        <p class="community-title">
          Account Privacy
        </p>
        <div class="account-privacy-checkbox flex items-center">
          <div class="form-group">
            <input id="public" v-model="user.privacy" name="profile-privacy" type="radio" value="public">
            <label for="public">Public</label>
          </div>
          <div class="form-group">
            <input id="private" v-model="user.privacy" name="profile-privacy" type="radio" value="private">
            <label for="private">Private</label>
          </div>
        </div>
      </div>
      <div class="user-bio-box">
        <p class="community-title">
          Bio
        </p>
        <div class="textarea-field-box">
          <textarea v-model="user.biography" maxlength="500" @keydown="wordsCount" />
          <span class="character-limit">{{ bioWordsCount }}/500</span>
        </div>
      </div>
      <div class="user-interest-box">
        <p class="community-title">
          Selected Interests
        </p>
        <ul class="user-interest-list flex items-center">
          <li v-for="(interest,index) in user.interests" :key="'selectedInterest-'+index" class="user-interest-item">
            <img :src="constants.getImageUrl(`activity/${interest.imageName}`)">
            <button class="btn-remove" @click="removeInterest(interest)">
              <svg fill="none" height="20" viewBox="0 0 21 20" width="21" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M17.7735 2.92582C13.8716 -0.975273 7.52616 -0.975273 3.62433 2.92582C-0.275818 6.82691 -0.275818 13.1742 3.62433 17.0752C5.57524 19.0254 8.13727 20 10.6993 20C13.2614 20 15.8225 19.0253 17.7735 17.0752C21.6745 13.1742 21.6745 6.82691 17.7735 2.92582ZM14.8254 12.9482C15.1514 13.2742 15.1514 13.8011 14.8254 14.1271C14.6628 14.2897 14.4494 14.3714 14.2359 14.3714C14.0225 14.3714 13.8091 14.2897 13.6465 14.1271L10.6993 11.179L7.75291 14.1263C7.58951 14.2888 7.37605 14.3706 7.16345 14.3706C6.95003 14.3706 6.73657 14.2888 6.57399 14.1263C6.24802 13.8003 6.24802 13.2725 6.57399 12.9474L9.52038 10.0001L6.57317 7.05284C6.2472 6.72686 6.2472 6.1991 6.57317 5.87394C6.89832 5.54796 7.42607 5.54796 7.75205 5.87394L10.6992 8.82121L13.6465 5.87394C13.9724 5.54796 14.4993 5.54796 14.8253 5.87394C15.1513 6.1991 15.1513 6.72686 14.8253 7.05284L11.8781 10.0001L14.8254 12.9482Z"
                  fill="#690FAD"
                />
              </svg>
            </button>
          </li>
          <li v-show="!isShowInterestList" class="user-interest-item btn-add-user-interest"
              @click="isShowInterestList = !isShowInterestList"
          >
            <svg fill="none" height="24" viewBox="0 0 25 24" width="25" xmlns="http://www.w3.org/2000/svg">
              <path clip-rule="evenodd"
                    d="M13.1445 1C13.1445 0.447715 12.6968 0 12.1445 0C11.5922 0 11.1445 0.447715 11.1445 1V11L1.14453 11C0.592246 11 0.144531 11.4477 0.144531 12C0.144531 12.5523 0.592247 13 1.14453 13L11.1445 13V23C11.1445 23.5523 11.5922 24 12.1445 24C12.6968 24 13.1445 23.5523 13.1445 23V13L23.1445 13C23.6968 13 24.1445 12.5523 24.1445 12C24.1445 11.4477 23.6968 11 23.1445 11L13.1445 11V1Z"
                    fill="black"
                    fill-rule="evenodd"
              />
            </svg>
          </li>
          <li v-show="isShowInterestList" class="user-interest-item btn-add-user-interest"
              @click="isShowInterestList = !isShowInterestList"
          >
            <svg fill="none" height="2" viewBox="0 0 25 2" width="25" xmlns="http://www.w3.org/2000/svg">
              <path clip-rule="evenodd"
                    d="M1.14258 0C0.590293 0 0.142578 0.447716 0.142578 1C0.142578 1.55229 0.590293 2 1.14258 2L23.1426 2C23.6949 2 24.1426 1.55228 24.1426 0.999998C24.1426 0.447713 23.6949 -1.90735e-06 23.1426 -1.90735e-06L1.14258 0Z"
                    fill="black"
                    fill-rule="evenodd"
              />
            </svg>
          </li>
        </ul>
        <!-- btn-add-activity-list -->
        <ul v-show="isShowInterestList" class="btn-add-activity-list">
          <li v-for="(interest,index) in $data.interestLists" :key="'interests-'+index"
              class="btn-add-activity-item"
              @click="addInterest(interest)"
          >
            <button>
              <div :class="`btn-filter-activity-type-item ${interest.isSelected ? 'category-active' : ''}`">
                <div class="btn-filter-activity-type-icon-box">
                  <img :src="constants.getImageUrl(`activity/${interest.imageName}`)">
                </div>
                <p class="btn-filter-activity-type-content-box">
                  {{ interest.name }}
                </p>
              </div>
            </button>
          </li>
        </ul>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <button class="btn-cancel rounded-full mb-5 ml-auto" @click="$router.go(-1)">
          Cancel
        </button>
        <button class="btn-update-profile bg-gradient rounded-full mb-5 mr-5" @click="updateProfile">
          Update Profile
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import interestMixin from "@/mixin/interestMixin";

export default {
  name: "UpdateCommunityInformation",
  mixins: [interestMixin],
  props: {
    user: {
      type: Object,
      required: true,
    }
  },
  data() {
    return {
      totalWordsBiography: 500,
      isShowInterestList: false,
    };
  },
  computed: {
    bioWordsCount() {
      return this.user.biography.length;
    },
  },
  methods: {
    wordsCount(event) {
      if (this.user.biography.length >= this.totalWordsBiography) {
        if (event.keyCode >= 48 && event.keyCode <= 90) {
          event.preventDefault();
          return;
        }
      }
    },
    addInterest(selectedInterest) {
      let isUserInterestExist = this.user.interests.filter(interest => interest["id"] === selectedInterest.id);
      if (isUserInterestExist.length === 0) {
        this.user.interests.push(selectedInterest);
        const updatedInterestLists = this.interestLists.map(interest => {
          if (interest.id === selectedInterest.id) {
            return {...interest, isSelected: true};
          }
          return interest;
        });
        this.interestLists = updatedInterestLists;
      }
    },
    updateInterestSelectedValue() {
      this.user.interests.forEach(selectedInterest => {
        let interestObject = this.interestLists.findIndex((interest) => {
          return interest.id == selectedInterest.id;
        });
        if (interestObject != -1) {
          this.interestLists[interestObject].isSelected = true;
        }

      });

    },
    removeInterest(selectedInterest) {
      const updatedInterestLists = this.interestLists.map(interest => {
        if (interest.id === selectedInterest.id) {
          return {...interest, isSelected: false};
        }
        return interest;
      });
      this.interestLists = updatedInterestLists;
      const interestListIndex = this.user.interests.findIndex(interest => interest.id === selectedInterest.id);
      this.user.interests.splice(interestListIndex, 1);
    },
    updateProfile() {
      if (this.user.biography.length > 501) {
        toastr.error("Biography length must be less than or equal to 500 characters long.");
        return false;
      }
      this.$emit("updateProfile", JSON.stringify(this.user));
    }
  },
  updated() {
    this.updateInterestSelectedValue();
  }
};
</script>

<style scoped>
.btn-filter-activity-type-item {
  width: 104px;
  height: 115.69px;
  padding: 17px 15px;
  background: #ffffff;
  -webkit-box-shadow: 0px 22px 40px rgb(0 0 0 / 10%);
  box-shadow: 0px 22px 40px rgb(0 0 0 / 10%);
  border-radius: 11px;
}

.btn-filter-activity-type-item:hover {
  background: #690FAD;
  color: #FFFFFF;

}

.btn-filter-activity-type-item:hover img {
  filter: invert(1);
}

.category-active > img {
  filter: invert(1);
}

#profile .btn-filter-activity-type-item {
  -webkit-box-shadow: 0px 1px 4px rgb(0 0 0 / 25%);
  box-shadow: 0px 1px 4px rgb(0 0 0 / 25%);
}

.btn-filter-activity-type-item .btn-filter-activity-type-content-box {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 14.06px;
  text-align: left;
}

li.btn-add-activity-item button {
  display: inline-flex;
}
</style>