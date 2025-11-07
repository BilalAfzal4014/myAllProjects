import Emitter from "tiny-emitter/instance";
import { loginUser } from "@/apiManager/user";
import * as toastr from "toastr";
import { removePrefixFromObject } from "@/utils";

const loginMixin = {
  data() {
    return {
      isLogin: localStorage.getItem("token"),
    };
  },
  methods: {
    checkIfLogin() {
      if (!this.isLogin) {
        const body = document.querySelector("body");
        body.classList.add("overflow-y-hidden");
        Emitter.emit("sign_in_modal", "show sign in modal");
        return false;
      }
      return true;
    },

    signin(email, password, isReload = false, isNotificationShown = false) {
      loginUser({
        username: email,
        password: password,
      }).then((response) => {
        if (isNotificationShown) {
          toastr.success("You have logged in successfully.");
        }
        let payload = removePrefixFromObject(response.data.user);
        const genderMapping = {
          m: "Male",
          f: "Female",
          u: "Unlisted",
        };

        payload["gender"] =
          genderMapping[payload["gender"]] || payload["gender"];
        this.$store.dispatch("setUserProfileInformationAction", payload);
        this.$store.dispatch("setStoreTokenAction", response.data.jwtToken);
        this.$store.commit("setRefreshToken", response.data.refreshToken);
        this.email = "";
        this.password = "";
        this.handleReload(isReload);
      });
    },
    handleReload(isReload) {
      if (!isReload) {
        return;
      }

      const { query, path } = this.$route;
      const { userProfile } = this.$store.state;

      const defaultRoute = "/community";
      const premiumRoute = "/listing";
      const corePremiumRoute = "/corepremium";
      const isPremiumUser = userProfile?.isPremiumUser;
      const targetPage = localStorage.getItem("targetPage");
      if (this.$route.name === "Booking") {
        return;
      }
      if (targetPage && targetPage === "corepremium") {
        this.redirectTo(corePremiumRoute);
        localStorage.removeItem("targetPage");
      } else if (typeof query.redirect !== "undefined") {
        this.redirectTo(query.redirect);
      } else if (path === corePremiumRoute && isPremiumUser) {
        this.redirectTo(premiumRoute);
      } else {
        this.redirectTo(defaultRoute);
      }
    },
    redirectTo(path) {
      this.$router.push(path);
    },
  },
};

export default loginMixin;
