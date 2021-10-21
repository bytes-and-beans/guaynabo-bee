let mix = require('laravel-mix');

mix.js('./source/js/app.js', 'js')
    .postCss("./source/css/app.pcss", "css", [
        require("tailwindcss"),
    ])
    .setPublicPath('./web/dist')
    .options({
        processCssUrls: false
    })
    .version()
    .sourceMaps();

//Enable this if you are distributing your own fonts
// mix.copy('./source/fonts', './web/dist/fonts');


mix.browserSync({
    proxy: "bytesandbeans-wolf.test",
    files: [
        "./templates/**/*.{twig,html}",
        "./source/css/**/*.{pcss,css}",
        "./source/js/**/*.js"
    ],
    open: false,
    notify: false
});