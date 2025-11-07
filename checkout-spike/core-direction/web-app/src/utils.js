export function updateMetaInformation(
  metaTitle,
  metaKeywords,
  metaDescription,
  metaOgTitle = null,
  metaOgDescription = null,
  metaOgImage = null,
  metaOgURL = null
) {
  document.title = metaTitle;
  document
    .querySelector("meta[name=\"title\"]")
    .setAttribute("content", metaTitle);
  document
    .querySelector("meta[name=\"description\"]")
    .setAttribute("content", metaDescription);
  document
    .querySelector("meta[name=\"keywords\"]")
    .setAttribute("content", metaKeywords);
  document
    .querySelector("meta[property=\"og:title\"]")
    .setAttribute("content", metaOgTitle || "Core Direction");
  document
    .querySelector("meta[property=\"og:description\"]")
    .setAttribute("content", metaOgDescription || "Join our #inspiringmovement");
  document
    .querySelector("meta[property=\"og:image\"]")
    .setAttribute("content", metaOgImage || "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,format=auto/landing-page/assets/img/og_img.jpg");
  document
    .querySelector("meta[property=\"og:url\"]")
    .setAttribute("content", metaOgURL || "https://my.coredirection.com/");
}

export function removeAllActiveClassesFromCategories() {
  const activeClass = document.querySelectorAll(".btn-filter");
  [].forEach.call(activeClass, (el) => {
    el.classList.remove("category-active");
    el.children[0].children[0].classList.remove("image-color-white");
  });
  const activityActiveClass = document.querySelectorAll(
    ".btn-filter-activity-type"
  );
  [].forEach.call(activityActiveClass, (el) => {
    el.classList.remove("category-active");
    el.children[0].children[0].classList.remove("image-color-white");
  });
}

export function removeClassFromElement(htmlElement, className) {
  htmlElement.forEach((element) => {
    element.classList.remove(className);
  });
}

export const removePrefixFromObject = (payload) => {
  for (let keyName in payload) {
    if (keyName.startsWith("custom:")) {
      let replaced_key = keyName.replace("custom:", "");
      payload[replaced_key] = payload[keyName];
      delete payload[keyName];
    }
    if (keyName.startsWith("cognito:")) {
      let replaced_key = keyName.replace("cognito:", "");
      payload[replaced_key] = payload[keyName];
      delete payload[keyName];
    }
  }
  return payload;
};
export const changeObjectIntoArray = (payload) => {
  for (let keyName in payload) {
    if (keyName.startsWith("interests")) {
      if (payload[keyName].length < 1) return payload;
      let isObject = payload[keyName][0].hasOwnProperty("id");
      if (isObject) {
        payload[keyName] = payload[keyName].map((obj) => obj.id);
      }
    }
  }
  return payload;
};
export const createArrayWithRange = (start, end, multiplier = 1) => {
  let newArray = [];
  for (let i = start; i <= end; i++) {
    newArray.push(String(i * multiplier));
  }
  return newArray;
};

export const daysInMonth = (month, year) => {
  return new Date(year, month, 0).getDate();
};

export const getDurationInDays = (start, end = new Date()) => {
  const startingTime = new Date(start);
  const endingTime = new Date(end);
  const diffTime = Math.abs(endingTime - startingTime);
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
};

export const TimeFormatAMPM = (date) => {
  let hours = date.getHours();
  let minutes = date.getMinutes();
  let ampm = hours >= 12 ? "PM" : "AM";
  hours = hours % 12;
  hours = hours ? hours : 12;
  minutes = minutes < 10 ? "0" + minutes : minutes;
  return hours + ":" + minutes + " " + ampm;
};

export const dashDateFormat = (date) => {
  const dateObject = new Date(date);
  const formattedDate = dateObject.getDate();
  const formattedMonth = dateObject.getMonth();
  const formattedYear = dateObject.getFullYear();
  return `${formattedDate}/${formattedMonth}/${formattedYear}`;
};

export const getFormattedTime = (dateObject) => {
  const date = new Date(dateObject);
  return `${String(date.getHours()).padStart(2, "0")}:${String(
    date.getMinutes()
  ).padStart(2, "0")}`;
};

export const setUniqueElement = (array, element) => {
  const indexOfName = array.indexOf(element);
  indexOfName >= 0
    ? array.splice(indexOfName, 1)
    : (array = [...array, element]);
  return array;
};

export const hideBodyScroll = () => {
  const body = document.querySelector("body");
  body.classList.add("overflow-y-hidden");
};
export const showBodyScroll = () => {
  const body = document.querySelector("body");
  body.classList.remove("overflow-y-hidden");
};

export const scrollToTopOnSameRoute = () => {
  document.getElementById("app").scrollIntoView();
};
export const changeDateFormatYearMonthDate = (date) => {
  let dateObject = new Date(removeOrdinalsFromDate(date));
  let year = dateObject.toLocaleString("default", { year: "numeric" });
  let month = dateObject.toLocaleString("default", { month: "2-digit" });
  let day = dateObject.toLocaleString("default", { day: "2-digit" });
  return `${year}-${month}-${day}`;
};

export const removeOrdinalsFromDate = (date) => {
  return date.replace(/(\d+)(st|nd|rd|th)/, "$1");
};

export const getLocalDateTime = () => {
  let today = new Date();
  today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
  today.setSeconds(today.getSeconds(), 0);
  return today.toISOString().slice(0, today.toISOString().length - 8);
};
