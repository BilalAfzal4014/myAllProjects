import {createStore, combineReducers, applyMiddleware} from "redux";
import sampleReducer from "./reducers/sample-reducer";


const store = createStore(
    combineReducers({
        sampleReducer
    }),
    {},
    //applyMiddleware()
);


export default store;