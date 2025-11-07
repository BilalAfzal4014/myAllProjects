import moment from "moment";

const extractDay = (value) => {
  return moment(value, "DD-MM-YYYY").format("DD");
};

export { extractDay };
