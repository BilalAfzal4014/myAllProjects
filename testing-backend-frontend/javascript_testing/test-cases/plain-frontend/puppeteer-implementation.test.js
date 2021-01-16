const puppeteer = require("puppeteer");
const clipboardy = require('clipboardy');

test("test google with fake browser", async () => {

    const browser = await puppeteer.launch({
        headless: false,
        slowMo: 80,
        args: ["--window-size=1920,1080"]
    });

    const page = await browser.newPage();
    //await page.goto("https://www.google.com");
    await page.goto("https://www.google.com");
    /*await page.click("input#id");
    await page.type("#id", "Bilal Afzal synavos");*/

    await page.click('[class="gLFyf gsfi"]');
    await page.type('[class="gLFyf gsfi"]', "Bilal Afzal synavos");
    //await page.type(String.fromCharCode(13));

    //await page.keyboard.press(String.fromCharCode(13));


    const [response] = await Promise.all([
        page.waitForNavigation(), // other wise page object will lose the context of the page
        page.keyboard.press(String.fromCharCode(13)),
    ]);

    //clipboardy.writeSync("aaa");

    //await page.goto(response._url);

    //console.log("browser pages", await browser.pages());

    // await page.goBack();

    let result = await page.$eval("#result-stats", el => el.textContent);

    console.log("result", result);
    expect(result.toLowerCase().match("about")).toBeTruthy();

     await browser.close();


    // we can write javascript inside evaluate callback
    /* await page.evaluate(() => {
        let elements = document.getElementsByClassName('showGoals');
        for (let element of elements)
            element.click();
    });*/

    /*
    let result = await = page.$eval(".className", el => el.textContent);
    expect(result).toBe("some string");
    */

}, 10000);