<template>
  <section id="friend-filter">
    <div class="custom-container">
      <div class="sub-container">
        <div class="search-friend-box">
          <input v-model="$parent.data.filter" v-debounce="filterData" class="friend-search-field"
                 placeholder="Search By Email, Phone, Full Name"
                 type="text"
          >
          <svg fill="none" height="15" viewBox="0 0 14 15" width="14" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M15.6539 15.2259L11.0786 10.6507C11.9464 9.57931 12.4688 8.2173 12.4688 6.7344C12.4688 3.29677 9.672 0.5 6.23437 0.5C2.79674 0.5 0 3.29677 0 6.7344C0 10.172 2.79677 12.9688 6.2344 12.9688C7.7173 12.9688 9.07931 12.4464 10.1507 11.5786L14.7259 16.1539C14.8541 16.282 15.0618 16.282 15.19 16.1539L15.6539 15.69C15.782 15.5618 15.782 15.354 15.6539 15.2259ZM6.2344 11.6563C3.52032 11.6563 1.31251 9.44848 1.31251 6.7344C1.31251 4.02032 3.52032 1.81251 6.2344 1.81251C8.94848 1.81251 11.1563 4.02032 11.1563 6.7344C11.1563 9.44848 8.94848 11.6563 6.2344 11.6563Z"
              fill="black"
            />
          </svg>
          <!-- add show class to display list -->
          <div class="search-list-box">
            <div class="search-list-header">
              <input class="friend-search-field" placeholder="Search By Email, Phone, Full Name" type="text">
              <svg fill="none" height="15" viewBox="0 0 14 15" width="14" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M15.6539 15.2259L11.0786 10.6507C11.9464 9.57931 12.4688 8.2173 12.4688 6.7344C12.4688 3.29677 9.672 0.5 6.23437 0.5C2.79674 0.5 0 3.29677 0 6.7344C0 10.172 2.79677 12.9688 6.2344 12.9688C7.7173 12.9688 9.07931 12.4464 10.1507 11.5786L14.7259 16.1539C14.8541 16.282 15.0618 16.282 15.19 16.1539L15.6539 15.69C15.782 15.5618 15.782 15.354 15.6539 15.2259ZM6.2344 11.6563C3.52032 11.6563 1.31251 9.44848 1.31251 6.7344C1.31251 4.02032 3.52032 1.81251 6.2344 1.81251C8.94848 1.81251 11.1563 4.02032 11.1563 6.7344C11.1563 9.44848 8.94848 11.6563 6.2344 11.6563Z"
                  fill="black"
                />
              </svg>
            </div>
            <ul class="search-list">
              <li class="search-item active">
                <img alt="" class="search-friend-image" src="/assets/images/friend-slider/friend-05.png">
                <span class="search-friend-name">Daniel Morris</span>
              </li>
              <li class="search-item">
                <img alt="" class="search-friend-image" src="/assets/images/friend-slider/friend-02.png">
                <span class="search-friend-name">Angelica Young</span>
              </li>
              <li class="search-item">
                <img alt="" class="search-friend-image" src="/assets/images/friend-slider/friend-03.png">
                <span class="search-friend-name">Abdul Rehman</span>
              </li>
            </ul>
          </div>
        </div>
        <button class="btn-search-friend">
          Search
        </button>
      </div>
    </div>
  </section>
</template>

<script>
export default {
    name: "CommunitySearchFriend",
    methods : {
        filterData () {
            this.$parent.data.offset = 0;
            this.$emit("filter");
        }
    }
};
</script>

<style scoped>

</style>