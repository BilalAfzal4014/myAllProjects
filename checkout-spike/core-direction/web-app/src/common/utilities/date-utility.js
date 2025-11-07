import {monthNames} from "../../dateConstant";
import {dayNames} from "../../dateConstant";

export const getUpcomingDaysUpto = (numberOfDays) => {
    let today = new Date();
    let futureDays = [];
    for (let  day = 0; day <= numberOfDays; day++) {
        let currentDate = new Date();
        currentDate.setDate(today.getDate() + day);
        futureDays.push({
            "day":getDayName(currentDate.getDay()),
            "date":currentDate.getDate() + " " + getMonthName(currentDate.getMonth()) + " " + currentDate.getFullYear()
        });
    }
    return futureDays;

};
export const getMissingDatesBetweenTwoDates = (startDate,endDate) => {
    let startDateSplit = startDate.split("-");
    let endDateSplit = endDate.split("-");
    let dates = [new Date(startDateSplit[0],startDateSplit[1],startDateSplit[2]), new Date(endDateSplit[0],endDateSplit[1],endDateSplit[2])];
    let missingDatesArray = [];
    let start = new Date(startDateSplit[0],startDateSplit[1],startDateSplit[2]);
    missingDatesArray.push({
        "day":getDayName(start.getDay()),
        "date":start.getDate() + " " + getMonthName(start.getMonth()) + " " + start.getFullYear()
    });
    for (var i = 1; i <= dates.length; i++)
    {
        var daysDiff = ((dates[i] - dates[i - 1]) / 86400000) - 1;
        for (var j = 1; j <= daysDiff; j++)
        {
            var missingDate = new Date(dates[i - 1]);
            missingDate.setDate(dates[i - 1].getDate() + j);
            missingDatesArray.push({
                "day":getDayName(missingDate.getDay()),
                "date":missingDate.getDate() + " " + getMonthName(missingDate.getMonth()) + " " + missingDate.getFullYear()
            });
        }
    }

    let end  = new Date(endDateSplit[0],endDateSplit[1],endDateSplit[2]);
    missingDatesArray.push({
        "day":getDayName(end.getDay()),
        "date":end.getDate() + " " + getMonthName(end.getMonth()) + " " + end.getFullYear()
    });
    return missingDatesArray;
};

const getMonthName = (monthIndex) => monthNames[monthIndex];
const getDayName = (dayIndex) => dayNames[dayIndex];

