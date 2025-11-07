module.exports = {
  purge: {
    enabled: process.env.NODE_ENV === "production",
    content: ["./src/**/*.vue", "./src/**/*.js"],
  },
  plugins: {
    tailwindcss: {
      config: "./tailwind.config.js",
    },
  },
};
