export const stripeMixin = {
  data() {
    return {
      stripe: null,
      cardElements: null,
    };
  },
  methods: {
    initializeStripe() {
      this.stripe = this.$stripe;
      const elements = this.stripe.elements();
      this.cardElements = {
        cardNumber: elements.create("cardNumber"),
        cardExpiry: elements.create("cardExpiry"),
        cardCvc: elements.create("cardCvc"),
      };
      this.mountCardElements();
    },
    mountCardElements() {
      this.cardElements.cardNumber.mount("#card-number");
      this.cardElements.cardExpiry.mount("#card-expiry");
      this.cardElements.cardCvc.mount("#card-cvc");
    },
    async createStripeToken() {
      const { token, error } = await this.stripe.createToken(
        this.cardElements.cardNumber
      );
      if (error) {
        this.handleError(error);
      } else {
        return token;
      }
    },
    handleError(error) {
      toastr.error(error);
    },
    destroyStripeElements() {
      Object.values(this.cardElements).forEach((element) => element.destroy());
    },
  },
  mounted() {
    this.initializeStripe();
  },
  beforeDestroy() {
    this.destroyStripeElements();
  },
};
