import api from "@/apiManager/apiManager";

export const createPurchaseEvent = (data) => {
  const url = "/purchase-event";
  return api("post", url, data, "common");
};
