import { ACTIVITY_TYPES } from "@/common/constants/constants";

export const getCalendarActivityTypes = (type) => {
    return { type: ACTIVITY_TYPES[type] };
};
