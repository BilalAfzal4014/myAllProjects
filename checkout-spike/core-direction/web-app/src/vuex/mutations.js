export default {
  setStoreTokenMutations(state, data) {
    state.token = "Bearer " + data;
    localStorage.setItem("token", state.token);
  },
  setUserProfileInformationMutation(state, data) {
    state.userProfile = data;
    localStorage.setItem("userProfile", JSON.stringify(data));
  },
  removeStoreTokenMutations(state) {
    state.token = null;
    localStorage.removeItem("token");
  },
  removeUserProfileInformationMutation(state) {
    state.userProfile = null;
    localStorage.removeItem("userProfile");
  },
  setHeaderSearchKeywordMutation(state, data) {
    state.headerSearch = data;
  },
  setCompaniesList(state, data) {
    state.companies = data;
  },
  setFilteredCompanies(state, data) {
    state.filteredCompanies = data;
  },
  setRefreshToken(state, data) {
    state.refreshToken = data;
    localStorage.setItem("refreshToken", data);
  },
  resetRefreshToken(state) {
    state.refreshToken = null;
    localStorage.removeItem("refreshToken");
  },
  setShowWearableModal(state, value) {
    state.showWearableModal = value;
  },
  setCorePremiumModal(state, payload) {
    state.corePremiumModal = payload;
    state.modalRedeem = false;
    state.modalRedeemRequiredCode = false;
    state.modalRedeemCongrats = false;
  },
  setIsCorePremium(state, payload) {
    state.userProfile.isPremiumUser = payload;
  },
  setIsModalRedeem(state, payload) {
    state.modalRedeem = true;
    state.modalRedeemData = payload;
  },
  setCloseModalRedeem(state) {
    state.modalRedeem = false;
  },
  setModalRedeemRequiredCode(state) {
    state.modalRedeem = false;
    state.modalRedeemRequiredCode = true;
  },
  setCloseModalRedeemRequiredCode(state) {
    state.modalRedeemRequiredCode = false;
  },
  setModalRedeemCongrats(state, payload) {
    state.modalRedeem = false;
    state.modalRedeemRequiredCode = false;
    state.modalRedeemCongrats = true;
    state.modalRedeemCongratsData = payload;
  },
  setCloseModalRedeemCongrats(state) {
    state.modalRedeemCongrats = false;
    state.modalRedeemData = {};
    state.modalRedeemCongratsData = {};
  },
  togglePopup(state, visibility) {
    state.isActivitySharePopupVisible = visibility;
  },
};
