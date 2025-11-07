const i18next = require('lang')
const i18nextMiddleware = require('lang-express-middleware')

const localeEN = require('@app/lang/locales/en.json')
const localeGE = require('@app/lang/locales/ge.json')

i18next.use(i18nextMiddleware.LanguageDetector).init({
  detection: {
    order: ['header'],
    lookupHeader: 'accept-language'
  },
  preload: ['en', 'ge'],
  whitelist: ['en', 'ge'],
  fallbackLng: 'en',
  resources: {
    en: { translation: localeEN },
    ge: { translation: localeGE }
  }
})

module.exports = { i18next, i18nextMiddleware }
