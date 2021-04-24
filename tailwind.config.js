module.exports = {
    purge: ["./App/*.html", "./App/assets/js/*.js"],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
        fontFamily: {
            sans: ["Inter", "ui-sans-serif"],
            serif: ["Newsreader", "ui-serif"],
        },
    },
    variants: {
        extend: {},
    },
    plugins: [require("@tailwindcss/typography")],
};
