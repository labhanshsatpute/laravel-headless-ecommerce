let mix = require('laravel-mix');

mix.js("resources/js/admin/app.js", "public/admin/js")
    .postCss("resources/css/admin/app.css", "public/admin/css", [
    require("tailwindcss"),
]);