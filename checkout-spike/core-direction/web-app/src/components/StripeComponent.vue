<template>
  <div class="checkout-container">
    <div class="payment-details">
      <div id="card-element" />
      <div id="payment-request-button" />
    </div>
    <button id="submit-button" @click="handlePayment">
      Submit Payment
    </button>
  </div>
</template>

<script>

export default {
  name: "StripeComponent",
  data() {
    return {
      stripe: null,
      card: null,
      elements: null,
    };
  },
  mounted() {
    this.stripe = Stripe(process.env.VUE_APP_STRIPE_PK_KEY);
    this.elements = this.stripe.elements();
    this.createCardElement();
    this.createPaymentRequestButton();
  },
  methods: {
    createCardElement() {
      this.card = this.elements.create("card", {style: {/* styling options */}});
      this.card.mount("#card-element");
    },
    handlePayment() {
      // You can modify this to integrate with the Payment Request API or simply tokenize the card as before
      this.stripe.createToken(this.card).then(result => {
        // Handle the result
      });
    },
    createPaymentRequestButton() {
      const paymentRequest = this.stripe.paymentRequest({
        country: "US", // specify your country
        currency: "usd",
        total: {
          label: "Total",
          amount: 1000, // amount in cents
        },
        requestPayerName: true,
        requestPayerEmail: true,
      });

      paymentRequest.canMakePayment().then(result => {
        if (result) {
          const prButton = this.elements.create("paymentRequestButton", {
            paymentRequest: paymentRequest,
          });

          // Check if your browser supports Payment Request Button
          paymentRequest.canMakePayment().then(result => {
            if (result) {
              prButton.mount("#payment-request-button");
            } else {
              document.getElementById("payment-request-button").style.display = "none";
            }
          });
        }
      });
    },
  },
};
</script>
<style scoped>
.checkout-container {
  max-width: 400px;
  margin: auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
  font-family: Arial, sans-serif;
}

.payment-details {
  margin-bottom: 20px;
}

#card-element, #payment-request-button {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #f7f7f7;
  margin-bottom: 10px;
}

#submit-button {
  width: 100%;
  padding: 12px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

#submit-button:hover {
  background-color: #0056b3;
}

</style>