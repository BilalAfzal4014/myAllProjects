<template>
  <form id="payment-form">
    <div class="form-row">
      <label for="card-element">
        Credit or debit card
      </label>
      <div id="card-element">
        <!-- A Stripe Element will be inserted here. -->
      </div>

      <!-- Used to display Element errors. -->
      <div id="card-errors" role="alert" />
    </div>

    <label class="add_checkbox" for="add_card">
      <input id="add_card" v-model="saveCard" type="checkbox"> save user card
    </label>
  </form>
</template>

<script>
import toastr from "toastr";
// should be constants
const stripe = Stripe(process.env.VUE_APP_STRIPE_API_KEY);
const elements = stripe.elements();


export default {
    props: {
        makePaymentWithCreditCardInfoGetter: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            card: null,
            token: null,
            saveCard: false
        };
    },
    mounted() {
        this.mountCard();
    },
    beforeDestroy() {
        this.card.destroy();
    },
    methods: {
        mountCard() {
            const style = {
                base: {
                    fontSize: "16px",
                    color: "#32325d",
                },
            };
            this.card = elements.create("card", {style});
            this.card.mount("#card-element");
        },
        async createToken() {
            try {
                const {token, error} = await stripe.createToken(this.card);
                if (error) {
                    toastr.error(error.message);
                    const errorElement = document.getElementById("card-errors");
                    errorElement.textContent = error.message;
                    this.$emit("sendPaymentResponse", {validation: "failed"});
                } else {
                    this.token = token;
                    this.$emit("sendPaymentResponse", {
                        validation: "success",
                        token: this.token,
                        save_card: this.saveCard
                    });
                    this.stripeTokenHandler(token);
                }
            } catch (e) {
                this.$emit("sendPaymentResponse", {
                    validation: "failed"
                });
            }
        },
        stripeTokenHandler(token) {
            const form = document.getElementById("payment-form");
            const hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "stripeToken");
            hiddenInput.setAttribute("value", token.id);
            form.appendChild(hiddenInput);
        }
    },
    watch: {
        makePaymentWithCreditCardInfoGetter(newVal, oldVal) {
            this.createToken();
        }
    }
};

</script>
<style scoped>
    input#add_card {
        -moz-appearance: auto !important;
        -webkit-appearance: auto !important;
        appearance: auto!important;
    }
</style>