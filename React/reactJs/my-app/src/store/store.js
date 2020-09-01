import {createStore, combineReducers, applyMiddleware} from "redux";
import logger from "redux-logger"; // make a complete log of of each action dispatched
import thunk from "redux-thunk"; // for asyc task i.e writing ajax call inside actions
import promise from "redux-promise-middleware"; // for asyc task i.e writing ajax call inside actions
import myLogger from "./middleWares/customMiddlware1"; // my custom middleware

import registerReducer from "./reducers/registerReducer";
import unRegisterReducer from "./reducers/unRegisterReducer";
import tokenReducer from "./reducers/tokenReducer";


const store = createStore(
    combineReducers({
        registerReducer,
        unRegisterReducer,
        tokenReducer
    }), // action type will be unique for all reducers that means action type is on store level
    {},
    applyMiddleware(myLogger, logger, thunk, promise)
);

export default store;