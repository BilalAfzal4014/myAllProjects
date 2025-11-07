import api from "@/apiManager/apiManager";

export const subscribeCorePremiumPlan = (
  tokenId,
  planId,
  cardId,
  saveCard,
  codeName
) => {
  const url = "subscribe-plan";
  let data = codeName
    ? {
      tokenId: tokenId,
      plan_id: planId,
      existingCardId: cardId,
      saveCard: saveCard,
      code: codeName,
    }
    : {
      tokenId: tokenId,
      plan_id: planId,
      existingCardId: cardId,
      saveCard: saveCard,
    };

  return api("post", url, data, "premium");
};

export const getPackageDetail = () => {
  const url = "package-detail";
  return api("get", url, null, "premium");
};

export const validateCoupon = (data) => {
  const url = "validate-coupon";
  return api("post", url, data, "premium");
};

export const getSubscriptionList = () => {
  const url = "get-member-subscriptions";
  return api("get", url, null, "premium");
};

export const cancelUserSubscription = (data) => {
  const url = "cancel-member-subscription";
  return api("post", url, data, "premium");
};

export const getPremiumUserDetails = () => {
  const url = "get-premium-user-details";
  return api("get", url, null, "premium");
};
