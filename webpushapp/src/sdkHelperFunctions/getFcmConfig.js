import store from "../store/store";

export function getFcmCongigurations() {
    return store.getState().fcmConfiguration;
}


store.subscribe(getFcmCongigurations);