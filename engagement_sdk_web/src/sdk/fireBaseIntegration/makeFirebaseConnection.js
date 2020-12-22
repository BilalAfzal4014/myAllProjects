import firebase from "firebase";
import {getFcmCongigurations} from "../../sdkHelperFunctions/getFcmConfig";

export function intializeFireBase(callback) {

    firebase.initializeApp(getFcmCongigurations());
    const messaging = firebase.messaging();

    messaging.requestPermission()
        .then(function () {
            console.log("Got notification permission");
            return messaging.getToken();
        })
        .then(function (token) {
            //will get token here
            console.log(token);
            localStorage.setItem("fireBase_token", token);
            callback(token);
        })
        .catch(function (err) {
            console.log("Didn't get notification permission", err);
        });

    messaging.onMessage(function (payload) {
        //console.log("Message received. ", JSON.stringify(payload));
        window.appObj.notificationReciever(payload);
    });

    messaging.onTokenRefresh(function () {
        messaging.getToken()
            .then(function (refreshedToken) {
                console.log('Token refreshed.');
            }).catch(function (err) {
            console.log('Unable to retrieve refreshed token ', err);
        });
    });

}