import {render, fireEvent, waitFor, act} from '@testing-library/react';
import Categories from "../../views/pages/categories";
import React from "react"
import $ from "jquery";

jest.mock("../../../src/api/categories-api");
import {saveCategory} from "../../../src/api/categories-api";

describe("Save category test cases", function () {

    test("save category while checking errors as well", async function () {

        const file = new File(['(⌐□_□)'], 'testing-image.png', {type: 'image/png'});

        window.URL.createObjectURL = jest.fn(function (selectedFile) {
            console.info("selected file is", selectedFile);
            //return selectedFile.path;
            return "http://dummy-url";
        });

        const {getByText, debug} = render(<Categories/>);

        getByText("Create Parent Category");

        fireEvent.click(getByText("Create Parent Category"));

        fireEvent.change($("#name")[0], {
            target: {
                value: "category-name"
            }
        });

        fireEvent.blur($("#name")[0]);

        expect($("#name").val()).toBe("category-name");

        fireEvent.change($("#commission")[0], {
            target: {
                value: 1
            }
        });


        await waitFor(() => fireEvent.submit($("#category-save-btn")[0]), {timeout: 5000});
        expect(saveCategory).toBeCalledTimes(0);

        /*await act(async () => {
          fireEvent.change($("#choose-file")[0], {
            target: {files: [file]}
          });
          await new Promise((r) => setTimeout(r, 100));
        });*/

        await act(async () => {
            await waitFor(() => fireEvent.change($("#choose-file")[0], {
                target: {files: [file]}
            }), {timeout: 5000});
        });

        expect($(".dz-img").attr("src")).toBe("http://dummy-url");

        await waitFor(() => fireEvent.submit($("#category-save-btn")[0]), {timeout: 5000});
        expect(saveCategory).toBeCalledTimes(1);

    });

    test("fetch category and update", async function () {

        const {getByText, debug} = render(<Categories/>);

        fireEvent.click($(".Category_action_wrapper")[0]);

        await act(async () => {
            await waitFor(() => fireEvent.click($(".Category_action_content .Add_sub_cat")[1]), {timeout: 5000});
        });

        getByText("Edit Category");
        expect($("#name").val()).toBe("febys-test-category");
        expect($("#slug").val()).toBe("febys-test-category");
        expect($("#commission").val()).toBe("0");
        expect($(".dz-img").attr("src")).toBe("http://dummy-url");


        await act(async () => {
            await waitFor(() => fireEvent.submit($("#category-save-btn")[0]), {timeout: 5000});
        });
        expect(saveCategory).toBeCalled();

        //debug(undefined, 300000);
    });

});
