<template>
  <div class="interest-box">
    <p class="section-title">
      Select your Interests
    </p>
    <p class="section-desc">
      Select to choose from {{ interests.length }} Types of activities. It’ll help us to show you the
      prioritize results in your feed. And don’t worry you can edit them later from your “My Profile”
      Settings.
    </p>
    <ul class="btn-add-activity-list">
      <li v-for="interest in interests" :key="interest.id" class="btn-add-activity-item"
          @click="updateUserInterest(interest)"
      >
        <button>
          <div :class="`btn-filter-activity-type-item ${ interest.isInterestSelected ? 'category-active' : ''} `">
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
</template>

<script>
export default {
  name: "SetUserInterest",
  props: {
    interests: {
      type: Array,
      required: true
    }
  },
  methods: {
    updateUserInterest(interestId) {
      this.$emit("onUpdateUserInterest", interestId);
    }
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
  margin-right: 14px;
  margin-bottom: 20px;
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