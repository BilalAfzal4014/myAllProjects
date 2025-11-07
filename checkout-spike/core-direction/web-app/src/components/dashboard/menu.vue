<template>
  <div>
    <navbar v-if="isLogin" />
    <NonAuthNavbar v-else />
  </div>
</template>

<script>
import Navbar from "@/partials/navbar";
import NonAuthNavbar from "@/partials/nonAuth-navbar";

export default {
    components: {
        Navbar,
        NonAuthNavbar
    },
    computed: {
        isLogin() {
          return !!this.$store.getters.getStoreTokenGetters;
        }
    }

};
</script>