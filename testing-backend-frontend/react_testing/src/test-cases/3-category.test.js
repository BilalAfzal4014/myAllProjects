import {render, cleanup, waitFor, fireEvent} from "@testing-library/react";
import Category from "../components/auth-components/category/category";
import {createStore, combineReducers} from "redux";
import {Provider} from "react-redux";
import $ from "jquery";


jest.mock("../components/auth-components/category/category-api");
afterEach(cleanup);

//can import reducer and store from application as well, but anyways writing my own

function categoryReducer(state = [], action) {

    switch (action.type) {
        case "INSERT_USER":
            state = [...state, action.payLoad];
            break;
        case "REMOVE_USER":
            let index = 0;
            for (let user of state) {
                if (user.id === action.payLoad.id) {
                    let newState = [...state];
                    newState.splice(index, 1);
                    state = newState;
                }
                index++;
            }
            break
    }

    return state;
}


function renderComponentWithRedux(Component) {

    const store = createStore(
        combineReducers({
            sampleReducer: categoryReducer //name of the reducer property should be same
        }),
    );
furniture
    return {
        ...render(
            <Provider store={store}>
                {/*<Component/>*/}
                {Component}
            </Provider>
        )
    }

}


test("test category component", async function () {


    //const {debug} = renderComponentWithRedux(Category);
    const {getByText, debug} = renderComponentWithRedux(<Category/>);

    await waitFor(() => debug, {timeout: 5000});
    debug();

    fireEvent.click(getByText("remove user"));
    debug();

    let tableString = $("table tbody").html();

    //can place this json on some centralize place
    let tableData = [{
        id: 1,
        name: "ABC"
    }, {
        id: 2,
        name: "XYZ"
    }];

    let makeTableString = ``;
    for(let row of tableData){
        makeTableString += `<tr>`;
        makeTableString += `<td>${row.id}</td>`;
        makeTableString += `<td>${row.name}</td>`;
        makeTableString += `</tr>`;
    }

    console.log("tableString", tableString);
    console.log("makeTableString", makeTableString);

    expect(tableString).toBe(makeTableString);

});


//testing of component connected with redux
// testing of data table