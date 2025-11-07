import {
  corporateEmployeeOnboarding,
  userAttributesSetting,
} from "@/apiManager/user";
import toastr from "toastr";

const hook = (to, from, next) => {
  const token = localStorage.getItem("token");
  const targetPage = localStorage.getItem("targetPage");
  let userProfile = JSON.parse(localStorage.getItem("userProfile"));
  if (to.path === "/" || to.path === "/signup") {
    if (token) {
      return next("/community");
    }
  }
  if (to.path === "/corepremium" && !token) {
    localStorage.setItem("targetPage", "corepremium");
  }

  if (
    targetPage &&
    targetPage === "corepremium" &&
    (to.path === "/community" || to.path === "/listing")
  ) {
    localStorage.removeItem("targetPage");
    return next("/corepremium");
  }
  if (to.meta.auth && !token) {
    const query = { redirect: to.fullPath };
    return next({ path: "/", query });
  }
  if (
    to.path === "/complete-profile-page" &&
    userProfile &&
    userProfile.is_profile_completed
  ) {
    return next("/community");
  }
  if (token && to.path !== "/complete-profile-page") {
    const slug = JSON.parse(localStorage.getItem("corporate_invite_slug"));
    if (slug) {
      corporateEmployeeOnboarding(slug)
        .then(async (response) => {
          if (response?.statusCode === 403) {
            toastr.error(response?.data);
            localStorage.removeItem("corporate_invite_slug");
            next();
          } else if (response?.statusCode === 200) {
            toastr.success(response.message);
            localStorage.removeItem("corporate_invite_slug");
            await updateUser();
            next(`/booking/${slug}`);
          } else {
            next();
          }
        })
        .catch((e) => {
          localStorage.removeItem("corporate_invite_slug");
          next();
        });
    }
  }
  next();
};

async function updateUser() {
  try {
    const res = await userAttributesSetting();
    if (res.statusCode === 200) {
      const userData = JSON.parse(localStorage.getItem("userProfile"));
      localStorage.setItem(
        "userProfile",
        JSON.stringify({ ...userData, isPremiumUser: res.data.isPremiumUser })
      );
      this.$store.commit("setIsCorePremium", res.data.isPremiumUser);
    }
  } catch (e) {
    return e?.message;
  }
}

export default hook;
