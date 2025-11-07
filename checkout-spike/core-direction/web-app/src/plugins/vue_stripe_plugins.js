import Vue from "vue";
import { StripePlugin } from "@vue-stripe/vue-stripe";

const options = {
  pk: process.env.VUE_APP_STRIPE_PK_KEY,
  apiVersion: process.env.VUE_APP_STRIPE_API_VERSION,
};

Vue.use(StripePlugin, options);