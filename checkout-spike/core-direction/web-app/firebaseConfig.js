import firebase from "firebase/compat/app";
import "firebase/compat/auth";


const firebaseConfig = {
    apiKey: "AIzaSyBEXpNzcUcxZxq0Pf7lBRuAaWqhzJY3bz4",
    authDomain: "core-direction-546df.firebaseapp.com",
    databaseURL: "https://core-direction-546df.firebaseio.com",
    projectId: "core-direction-546df",
    storageBucket: "core-direction-546df.appspot.com",
    messagingSenderId: "106858314386",
    appId: "1:106858314386:web:8b0f579867bd1717dd844b",
    measurementId: "G-6QR5XK34ML"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const googleProvider = new firebase.auth.GoogleAuthProvider();
const facebookProvider = new firebase.auth.FacebookAuthProvider();
const appleProvider = new firebase.auth.OAuthProvider("apple.com");
const auth = firebase.auth();
export {
    auth,
    googleProvider,
    facebookProvider,
    appleProvider
};