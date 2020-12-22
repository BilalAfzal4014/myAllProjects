import {createStore, combineReducers, applyMiddleware} from "redux";
import logger from "redux-logger";
import thunk from "redux-thunk";
import fcmConfiguration from "./reducers/fcmConfigurationReducer";
import userLoginToken from "./reducers/userLoginReducer";

const store = createStore(
    combineReducers({
        fcmConfiguration,
        userLoginToken
    }),
    {},
    applyMiddleware(logger, thunk)
);

export default store;