module.exports = {
  root: true,
  parserOptions: {
    parser: "babel-eslint",
    sourceType: "module",
    ecmaVersion: 6,
  },
  env: {
    browser: true,
    node: true,
    es6: true,
  },
  extends: [
    "plugin:vue/base",
    "plugin:prettier/recommended",
    "prettier",
    "eslint-config-prettier",
    "plugin:vue/recommended",
    "plugin:vue/strongly-recommended",
  ],
  rules: {
    // Disabled rules
    "no-undef": "off",
    "no-unused-vars": "off",
    "no-redeclare": "off",
    "vue/max-attributes-per-line": "off",
    "vue/order-in-components": "off",
    "vue/html-end-tags": "off",
    "no-mixed-spaces-and-tabs": 0, // disable rule
    "vue/attribute-hyphenation": "off",
    "prettier/prettier": 0,
    camelcase: "off",
    "vue/no-v-html": "off",

    // Warn level rules
    indent: ["warn", 2],
    "no-empty": "warn",

    // Error level rules
    semi: ["error", "always"],
    quotes: ["error", "double"],
    "no-cond-assign": ["error", "always"],
    "vue/comment-directive": "error",
    "no-console": process.env.NODE_ENV === "production" ? "error" : "off",
    "no-debugger": process.env.NODE_ENV === "production" ? "error" : "off",
  },
  // required to lint *.vue files
  plugins: ["vue"],
};
