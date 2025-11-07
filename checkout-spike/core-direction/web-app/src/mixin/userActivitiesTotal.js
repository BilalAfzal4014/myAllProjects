const userActivitiesTotal = {
  methods: {
    getTotalPrice() {
      let totalPrice = this.calculateTotalPrice();
      return (totalPrice + this.getVatPercentage(totalPrice)).toFixed(2);
    },
    calculateTotalPrice() {
      let totalPrice = 0;
      for (let user of this.users) {
        totalPrice += user.package.discounted_price
          ? parseFloat(user.package.discounted_price)
          : parseFloat(user.package.price);
      }
      return totalPrice;
    },
    getVatPercentage(totalPrice) {
      return (this.getVatValue() / 100) * totalPrice;
    },
    getVatValue() {
      return parseInt(process.env.VUE_APP_VAT_VALUE);
    },
  },
};
export default userActivitiesTotal;
