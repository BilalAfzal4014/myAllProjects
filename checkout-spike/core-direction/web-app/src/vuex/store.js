import Vuex from "vuex";
import Vue from "vue";
import mutations from "./mutations";
import actions from "./actions";
import getters from "./getters";
// import VuexPersistence from "vuex-persist";

Vue.use(Vuex);
const store = new Vuex.Store({
  state: {
    token: null,
    userProfile: null,
    headerSearch: "",
    companies: [],
    filteredCompanies: [],
    refreshToken: null,
    showWearableModal: false,
    corePremiumModal: false,
    modalRedeem: false,
    modalRedeemRequiredCode: false,
    modalRedeemCongrats: false,
    modalRedeemData: {},
    modalRedeemCongratsData: {},
    isActivitySharePopupVisible: false,
  },
  mutations,
  actions,
  getters,
  // plugins: [new VuexPersistence().plugin],
});

export default store;
