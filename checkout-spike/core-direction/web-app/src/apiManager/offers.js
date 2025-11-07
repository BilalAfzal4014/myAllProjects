import api from "@/apiManager/apiManager";

export const getOffers = (slug,limit,offset) => {
  const url = `/deals-offer/facility/${slug}?limit=${limit}&offset=${offset}`;
  return api("get", url, null , "offers");
};

export const getRedeemOffer = (id,code=null) => {
  const url = `/deals-offer/redeem/${id}`;
  return api("post", url, {redemption_code : code} , "offers");
};


export const getOffersList = (limit, offset) => {
  const url = `/deals-offer/redemptions/?limit=${limit}&offset=${offset}`;
  return api("get", url, null , "offers");
};