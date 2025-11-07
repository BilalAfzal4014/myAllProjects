const shouldShowWearableModal = (userProfile) =>
    !!userProfile && !userProfile.isAppUser;

const isModalClosedByUser = (userProfile, alreadyClosed) => {
    if (alreadyClosed === null) return false;

    if (removeIfNotArray("modalClosedByUser")) {
        const alreadyClosedArray = alreadyClosed || [];
        const userProfileId = userProfile?.id?.toString();

        return (
            !alreadyClosedArray.includes(userProfileId) && !userProfile?.isAppUser
        );
    }

    return shouldShowWearableModal(userProfile);
};

export const showWearableModal = (to, from, next, store) => {
    const alreadyClosed = getItemFromLocalStorage("modalClosedByUser");
    const userProfile = store.getters.getStoreUserProfileGetters();
    const showModal =
    userProfile &&
    (alreadyClosed
        ? isModalClosedByUser(userProfile, alreadyClosed)
        : shouldShowWearableModal(userProfile));

    showModal && store.commit("setShowWearableModal", true);
    next();
};

const getItemFromLocalStorage = (key) => {
    const item = localStorage.getItem(key);
    return item ? JSON.parse(item) : null;
};

const removeItemFromLocalStorage = (key) => localStorage.removeItem(key);

const isArray = (item) => Array.isArray(item);

const removeIfNotArray = (key) => {
    const item = getItemFromLocalStorage(key);
    if (isArray(item)) return true;

    removeItemFromLocalStorage(key);
    return false;
};
