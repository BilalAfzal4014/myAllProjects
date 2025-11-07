export default {
  setStoreTokenAction({ commit }, data) {
    commit("setStoreTokenMutations", data);
  },
  removeStoreTokenAction({ commit }) {
    commit("removeStoreTokenMutations");
  },
  setUserProfileInformationAction({ commit }, data) {
    commit("setUserProfileInformationMutation", data);
  },
  removeUserProfileInformationAction({ commit }) {
    commit("removeUserProfileInformationMutation");
  },
  setHeaderSearchKeywordAction({ commit }, data) {
    commit("setHeaderSearchKeywordMutation", data);
  },
  setWearableModal({ commit }, value) {
    commit("setShowWearableModal", value);
  },
  showPopup({ commit }) {
    commit("togglePopup", true);
  },
  hidePopup({ commit }) {
    commit("togglePopup", false);
  },
};
