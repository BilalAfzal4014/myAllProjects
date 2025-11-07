import moment from "moment";

export const dateStringIntoObject = (date) => {
    //Date Format yyyy-mm-dd
    return new Date(date);
};

export const isEndDateGreaterStartDate = (startDate, endDate) => {
    return endDate < startDate ? true : false;
};

export const changeDateFormat = (date, currentFormat, changeFormat) => {
    return new Date(moment(date, currentFormat).format(changeFormat));
};
