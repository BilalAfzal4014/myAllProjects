const api = require('./src/api');
const fs = require('fs');

function fetchCurrencies() {
    return api('get', 'https://api-coding-challenge.neofinancial.com/currency-conversion?seed=63277');
}

function translateData(currencyConversions) {
    const conversionHash = {};
    const distinctCountriesHash = {};
    const distinctCurrencies = [];
    currencyConversions.forEach((conversion) => {
        conversionHash[`${conversion.fromCurrencyCode}_${conversion.toCurrencyCode}`] = conversion.exchangeRate;
        distinctCountriesHash[conversion.fromCurrencyCode] = getCountryName(conversion.fromCurrencyName);
        distinctCountriesHash[conversion.toCurrencyCode] = getCountryName(conversion.toCurrencyName);
    });
    for (const key in distinctCountriesHash) {
        distinctCurrencies.push(key);
    }
    return [conversionHash, distinctCurrencies, distinctCountriesHash];
}

function getCountryName(longName){
    let countryName = '';
    const words = longName.split(' ');
    if(words.length === 1)
        return words[0];

    for(let i = 0; i < words.length - 1; i++){
        const word = words[i];
        countryName += !i ? word: ` ${word}`;
    }
    return countryName;
}

function findBestRateWithLeastConversion(fromCurrency, toCurrency, currencyConversionHash, distinctCurrencies, visitPath, calculatedPrice) {
    if (fromCurrency === toCurrency) {
        return [fromCurrency, calculatedPrice, 1];
    }
    let [path, price, visits] = ['', 0, Number.MAX_SAFE_INTEGER];
    for (let i = 0; i < distinctCurrencies.length; i++) {
        const rate = currencyConversionHash[`${fromCurrency}_${distinctCurrencies[i]}`];
        const exchangeExist = !!rate;
        const pathVisitYet = !!visitPath[`${fromCurrency}_${distinctCurrencies[i]}`];

        if (exchangeExist && !pathVisitYet) {
            visitPath[`${fromCurrency}_${distinctCurrencies[i]}`] = true;
            const [currPath, currPrice, currVisits] = findBestRateWithLeastConversion(distinctCurrencies[i], toCurrency,
                currencyConversionHash,
                distinctCurrencies, visitPath, calculatedPrice * rate);
            if ((currPrice > price) || (currPrice === price && currVisits < visits)) {
                path = currPath;
                price = currPrice;
                visits = currVisits
            }
            delete visitPath[`${fromCurrency}_${distinctCurrencies[i]}`];
        }
    }
    return !path ? [path, 0, Number.MAX_SAFE_INTEGER] : [`${fromCurrency} | ${path}`, price, visits + 1];
}


async function generateCurrencyConversionCsv() {
    const fromCurrency = 'CAD';
    const currencies = await fetchCurrencies()
    const [currencyConversionHash, distinctCurrencies, distinctCountriesHash] = translateData(currencies);
    let csvHeaders = `Currency,Country,Amount,Path\n`;
    const writeStream = fs.createWriteStream(`./csv/${(new Date()).toISOString()}.csv`);
    writeStream.write(csvHeaders);
    for(const toCurrency of distinctCurrencies){
        if(toCurrency === fromCurrency)
            continue;

        const [path, rate] = findBestRateWithLeastConversion(fromCurrency, toCurrency, currencyConversionHash, distinctCurrencies, {}, 100);
        const row = `${toCurrency},${distinctCountriesHash[toCurrency]},${!!path ? path: 'N/A'},${!!path ? rate.toFixed(5): 'N/A'}\n`;
        writeStream.write(row);
    }
    writeStream.end();
}


generateCurrencyConversionCsv();


/*fs.writeFile(`./csv/${(new Date()).toISOString()}.csv`, csvContent, 'utf8', function (err) {
        if (err) {
            console.log('Some error occured - file either not saved or corrupted file saved.');
        } else{
            console.log('It\'s saved!');
        }
    });*/
