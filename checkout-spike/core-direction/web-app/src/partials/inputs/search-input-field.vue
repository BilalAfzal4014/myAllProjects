<template>
  <div>
    <input v-model="search" class="rounded-full" placeholder="Activity Provider Name" type="search"
           @keyup.enter="searchCompany"
    >
    <magnify-icon @search="searchCompany" />
  </div>
</template>

<script>
import MagnifyIcon from "../../svgs/magnify-icon";

export default {
    name: "SearchInputField",
    components: {MagnifyIcon},
    data() {
        return {
            search: ""
        };
    },
    watch: {
        search() {
            if (!this.search) {
                this.$store.commit("setFilteredCompanies", []);
            }
        }
    },
    methods: {
        searchCompany() {
            if (!this.search) {
                toastr.error("Provider name should not be empty.");
                return;
            }
            if (this.$route.name === "Listing") {
                this.$store.dispatch("setHeaderSearchKeywordAction", this.search);
            } else {
                this.$router.push({name: "Listing", query: {keyword: this.search}}).catch((error) => {
                    toastr.error(error);
                });
            }

        }
    }
};
</script>

<style scoped>

</style>