import Vue from "vue";
import App from "./App.vue";
import router from "./router/index";
import store from "./vuex/store";
import hook from "./guards/hook";
import apiCall from "./apiManager/oldApiManager";
import constants from "./constants/constants";
import VueRouter from "vue-router";
import * as VueGoogleMaps from "vue2-google-maps";
import Calendar from "v-calendar/lib/components/calendar.umd";
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import VueTelInput from "vue-tel-input";
import "vue-tel-input/dist/vue-tel-input.css";
import VueSocialSharing from "vue-social-sharing";
import { CONSTANT } from "@/constants";
import "../src/assets/index.css";
import VueScrollTo from "vue-scrollto";
import vueDebounce from "vue-debounce";
import VueSocketIO from "vue-socket.io";
import { io } from "socket.io-client";
import "./plugins/vee_validate";

Vue.use(VueScrollTo);
Vue.use(VueSocialSharing);
Vue.use(VueTelInput);
Vue.use(vueDebounce, {
  defaultTime: "500ms",
});
Vue.component("calendar", Calendar);
Vue.component("date-picker", DatePicker);
Vue.use(VueGoogleMaps, {
  load: {
    key: process.env.VUE_APP_GOOGLE_MAP_KEY,
    libraries: "places", // This is required if you use the Autocomplete plugin
  },
});
const options = {
  reconnection: true,
  reconnectionDelay: 500,
  maxReconnectionAttempts: Infinity,
  transportOptions: {
    polling: {
      extraHeaders: {
        Authorization:
          localStorage.getItem("token") !== null
            ? `${localStorage.getItem("token")}`
            : null,
      },
    },
  },
};
Vue.use(
  new VueSocketIO({
    debug: true,
    connection: io(process.env.VUE_APP_SOCKET_IO_URL, options), //options object is Optional
    vuex: {
      store,
      actionPrefix: "SOCKET_",
      mutationPrefix: "SOCKET_",
    },
  })
);
Vue.use(VueRouter, { history: true });

Vue.directive("click-outside", {
  bind(el, binding, vnode) {
    el.clickOutsideEvent = (event) => {
      if (
        !(el === event.target || el.contains(event.target)) &&
        !event.target.classList.contains("inside-navbar") &&
        !event.target.parentNode.classList.contains("inside-navbar") &&
        !event.target.parentElement.classList.contains("inside-navbar") &&
        !event.target.classList.contains("hamburger-menu-wrapper") &&
        !event.target.parentNode.classList.contains("hamburger-menu-wrapper") &&
        !event.target.parentElement.classList.contains("hamburger-menu-wrapper")
      ) {
        vnode.context[binding.expression](event);
      }
    };
    document.body.addEventListener("click", el.clickOutsideEvent);
  },
  unbind(el) {
    document.body.removeEventListener("click", el.clickOutsideEvent);
  },
});

Vue.directive("click-outside-parent-element", {
  bind: function (el, binding, vnode) {
    el.clickOutsideEvent = function (event) {
      if (!(el === event.target || el.contains(event.target))) {
        vnode.context[binding.expression](event);
      }
    };
    document.body.addEventListener("click", el.clickOutsideEvent);
  },
  unbind: function (el) {
    document.body.removeEventListener("click", el.clickOutsideEvent);
  },
});
Vue.directive("click-outside-crop-image-modal", {
  bind: function (el, binding, vnode) {
    el.clickOutsideEvent = function (event) {
      if (
        !(el === event.target || el.contains(event.target)) &&
        !event.target.classList.contains("btn-img-upload")
      ) {
        vnode.context[binding.expression](event);
      }
    };
    document.body.addEventListener("click", el.clickOutsideEvent);
  },
  unbind: function (el) {
    document.body.removeEventListener("click", el.clickOutsideEvent);
  },
});
Vue.directive("click-outside-modal", {
  bind: function (el, binding, vnode) {
    el.clickOutsideModalEvent = function (event) {
      if (
        !(el == event.target || el.contains(event.target)) &&
        !event.target.classList.contains("multiple-detail-booking-modal") &&
        event.target.parentNode.nodeName !== "svg" &&
        !event.target.classList.contains("wrapper")
      ) {
        vnode.context[binding.expression](event);
      }
    };
    document.body.addEventListener("click", el.clickOutsideModalEvent);
  },
  unbind: function (el) {
    document.body.removeEventListener("click", el.clickOutsideModalEvent);
  },
});
Vue.prototype.oldApi = apiCall;
Vue.prototype.constants = constants;
router.beforeEach(hook);
Vue.mixin({
  data() {
    return {
      CONSTANT,
    };
  },
});

new Vue({
  el: "#app",
  router,
  store,
  render: (h) => h(App),
});
