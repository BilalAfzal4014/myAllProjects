<template>
  <div>
    <banner-component />
    <hero-component />
    <section class="mb-8">
      <cards :companies="$store.getters.getCompaniesList" />
    </section>
  </div>
</template>

<script>
import HeroComponent from "../partials/hero";
import Cards from "../partials/cards";
import getCompaniesMixin from "@/mixin/getCompaniesMixin";
import {mapGetters} from "vuex";
import BannerComponent from "@/components/BannerComponent";

export default {
    name: "HomePage",
    components: {
        BannerComponent,
        Cards,
        HeroComponent
    },
    mixins: [getCompaniesMixin],
    computed: {
        ...mapGetters["getCompaniesList"]
    },
};
</script>

<style scoped>

</style>