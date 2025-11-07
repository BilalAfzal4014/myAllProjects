const notificationMixin = {
  methods: {
    navigateToCommunity(notification) {
      const intendedQuery = {
        dairy_shared: "true",
        slug: notification.data.slug_url,
      };
      if (
        typeof this.$route.query.slug === "undefined" ||
        this.$route.query.slug !== intendedQuery.slug
      ) {
        this.$router.push({ path: "/community", query: intendedQuery });
      }
      this.$store.dispatch("showPopup");
    },
  },
};
export default notificationMixin;
