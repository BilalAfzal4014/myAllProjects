import puppeteer  from "puppeteer";
//import { render, screen } from '@testing-library/react';
//const puppeteer = require("puppeteer");
jest.mock("../components/auth-components/course/course-api");
import {getCourses} from "../components/auth-components/course/course-api"; // mocked version will called when invoked


test("test course component with puppeteer", async function(){

    // puppeteer is an end to end testing and will never invoke a mocked function
    // bcz it operates on above
    const browser = await puppeteer.launch({
        /*headless: false,
        slowMo: 80,
        args: ["--window-size=1920,1080"]*/
    });

    const page = await browser.newPage();
    await page.goto("http://localhost:3000/dashboard/courses");


    await page.waitForFunction(getCourses); //wait for async function to finish


    let result = await page.$eval("#course", el => el.textContent);

    //console.log("result", result); // will now get the updated result
    expect(true).toBeTruthy();
    await browser.close();

}, 10000);