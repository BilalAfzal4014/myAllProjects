import api from "./apiManager";

export const getChallengesList = (data) => {
    const url = "/get-challenges";
    return api("post", url, data, "gamification");
};

export const getChallengeById = (data) => {
    const url = "/get-challenge-by-id";
    return api("post", url, data, "gamification");
};

export const getChallengeParticipents = (data) => {
    const url = "/get-challenge-cache-participants";
    return api("post", url, data, "gamification");
};

export const getUserGamification = (data) => {
    const url = "/get-user-gamification";
    return api("post", url, data, "gamification");
};

export const leaveChallenge = (data) => {
    const url = "/join-challenge";
    return api("post", url, data, "gamification");
};

export const getBusinessChallenges = (data) => {
    const url = "/get-business-challenges";
    return api("post", url, data, "gamification");
};

export const updateCodCorePoints = (data) => {
    const url = "/update-cod-core-points";
    return api("get", url, null, "gamification");
};

export const updateBookingCheckInCorePoints = (data) => {
    const url = "/update-booking-checkin-core-points";
    return api("post", url, data, "gamification");
};
