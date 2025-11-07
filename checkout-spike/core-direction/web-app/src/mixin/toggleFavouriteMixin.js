import * as toastr from "toastr";
import {
  FAVOURITE_MESSAGES,
  FAVOURITE_STATUS,
  SHOW_SIGN_IN_MODAL_MESSAGE,
  SIGN_IN_MODAL_EVENT,
} from "@/common/constants/constants";
import Emitter from "tiny-emitter/instance";
import { updateCompanyFavouriteStatus } from "@/apiManager/companies";

const toggleFavouriteMixin = {
  methods: {
    handleFavourite(companyId, isFavourite, isAlwaysFavourite) {
      if (!this.authToken) {
        Emitter.emit(
          isAddToFavorite ? "sign_in_modal" : SIGN_IN_MODAL_EVENT,
          isAddToFavorite ? "show sign in modal" : SHOW_SIGN_IN_MODAL_MESSAGE
        );
        return;
      }

      let favStatusKey = isFavourite
        ? FAVOURITE_STATUS.unfavourite
        : FAVOURITE_STATUS.favourite;
      let message = FAVOURITE_MESSAGES[favStatusKey];
      if (isAlwaysFavourite) {
        message = FAVOURITE_MESSAGES[FAVOURITE_STATUS.unfavourite];
        favStatusKey = FAVOURITE_STATUS.unfavourite;
        this.$emit("removeFavouriteCompany", companyId);
      }
      const payload = { companyId, status: favStatusKey };
      updateCompanyFavouriteStatus(payload)
        .then((response) => {
          this.unFavoriteBusinessInState(companyId);
          Emitter.emit("favourite_button");
          toastr.success(response.message);
          if (this.$route.path === "/favourite-companies") {
            this.$parent.getFavouriteCompanies();
          }
        })
        .catch((error) => toastr.error(error));
    },

    toggleFavourite(companyId, isFavourite, isAlwaysFavourite) {
      this.handleFavourite(companyId, isFavourite, isAlwaysFavourite);
    },

    addToFavorite(companyId, isFavourite) {
      if (this.$route.path === "/favourite-companies") {
        this.handleFavourite(companyId, true, false);
        return false;
      }
      this.handleFavourite(companyId, isFavourite, false);
    },
    unFavoriteBusinessInState(id) {
      let companiesListCopy = [...this.companiesList];
      for (let i = 0; i < companiesListCopy.length; i++) {
        const object = companiesListCopy[i].company.find(
          (obj) => obj.id === id
        );
        if (object) {
          let index = companiesListCopy[i].company.indexOf(object);
          companiesListCopy[i].company[index].is_fvt =
            !companiesListCopy[i].company[index].is_fvt;
          this.$store.commit("setCompaniesList", companiesListCopy);
          break;
        }
      }
    },
  },
};

export default toggleFavouriteMixin;
