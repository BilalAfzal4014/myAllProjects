import {render, screen, waitFor, findByText, fireEvent, cleanup} from '@testing-library/react';
import Course from "../components/auth-components/course/course";
import Name from "../components/auth-components/name/name";
//import React from "react";

//import {getCourses} from "../components/auth-components/course/course-api";
jest.mock("../components/auth-components/course/course-api");

afterEach(cleanup);

test("test course component async behaviour", async function () {
    //screen is kind of a same object(but not similar) return by render function
    const {getByText, container, getByTestId, debug} = render(<Course/>);
    //debug()
    // explore below line for async api call
    //await waitFor(() => expect(getCourses).toHaveBeenCalledTimes(1))

    //await findByText(container,'MOCKED-Databases', {}, {timeout: 5000}) //container meaning is for whole page
    //await waitFor(() => getByText("MOCKED-Databases"), {timeout: 5000}) //both above and this line are same

    await findByText(getByTestId("course"), 'MOCKED-Databases'); // only element with course test id
    //expect(queryByTestId("course")).toBeNull();


    //await screen.getByText("Databases")


});

test("test course component onChange event", function () {

    const {getByText, getByTestId} = render(<Course/>);

    fireEvent.change(getByTestId("firstName"), {
        target: {
            value: "Bilal"
        }
    });

    fireEvent.change(getByTestId("lastName"), {
        target: {
            value: "Afzal"
        }
    });

    //console.log(getByTestId("h1-firstName").getAttribute('value')) //in-case of input field

    expect(getByTestId("h1-firstName").textContent).toBe("Bilal");
    getByText("Afzal");

});

test("test course component onClick event", function () {
    const {getByText, getByTestId} = render(<Course/>);

    //both will work the same
    //fireEvent.click(getByText("increment counter"))
    //fireEvent.click(getByTestId("increment-counter"))

    fireEvent.click(document.getElementById("incBtn")); //vanilla js will also work for selection of element
    expect(getByTestId("counter").textContent).toBe("1");
});

test("test name component props", function () {

    /*
    const sampleFun = jest.fn();
    //pass this function in props i.e. incrementCounter
    expect(sampleFun).toBeCalled();
    */

    let called = false;
    const {getByText} = render(<Name firstName={"Bilal"} lastName={"Afzal"} incrementCounter={function () {
        called = true
    }} counter={0}/>);

    getByText("Bilal");
    getByText("Afzal");

    fireEvent.click(getByText("increment counter"));

    expect(called).toBe(true);
});