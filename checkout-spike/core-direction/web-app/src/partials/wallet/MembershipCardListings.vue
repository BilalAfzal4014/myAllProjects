<template>
  <membership-card :membership-package="membershipPackages" />
</template>

<script>
import MembershipCard from "../../wallet/membership/MembershipCard";

export default {
  name: "MembershipCardListings",
  components: {MembershipCard},
  props: {
    membershipPackages: {
      type: Array,
      required: true
    }
  }
};
</script>

<style scoped>

</style>