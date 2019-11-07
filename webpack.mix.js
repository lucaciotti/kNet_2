const { mix } = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js("resources/assets/js/app.js", "public/js")
  .js("resources/assets/js/app-landing.js", "public/js/app-landing.js")
  .sourceMaps()
  .combine(
    [
      "resources/assets/css/bootstrap.min.css",
      "resources/assets/css/font-awesome.min.css",
      "resources/assets/css/ionicons.min.css",
      "node_modules/admin-lte/dist/css/AdminLTE.min.css",
      "node_modules/admin-lte/dist/css/skins/_all-skins.css"
    ],
    "public/css/app.css"
  )
  .combine(
    [
      "resources/assets/css/bootstrap.min.css",
      "resources/assets/css/pratt_landing.min.css"
    ],
    "public/css/app-landing.css"
  )
  // PACKAGE (ADMINLTE-LARAVEL) RESOURCES
  .copy("resources/assets/img/*.*", "public/img/")
  //VENDOR RESOURCES
  .copy("node_modules/font-awesome/fonts/*.*", "public/fonts/")
  .copy("node_modules/ionicons/dist/fonts/*.*", "public/fonts/")
  .copy("node_modules/bootstrap/fonts/*.*", "public/fonts/")
  //  .copy('node_modules/admin-lte/dist/css/skins/*.*','public/css/skins')
  //  .copy('node_modules/admin-lte/dist/img','public/img')
  .copy("node_modules/icheck/skins/flat/aero.png", "public/css")
  .copy("node_modules/icheck/skins/square/aero@2x.png", "public/css")
  .copy("node_modules/admin-lte/plugins", "public/plugins")
  .copy("node_modules/datatables.net-bs/js", "public/plugins/datatables")
  .copy("node_modules/datatables.net-bs/css", "public/plugins/datatables")
  .copy("node_modules/datatables.net/js", "public/plugins/datatables")
  .copy("node_modules/moment/min", "public/plugins/moment")
  .copy("node_modules/morris.js/morris.*", "public/plugins/morris/")
  .copy("node_modules/raphael/raphael.*", "public/plugins/raphael")
  .copy("node_modules/jquery-knob/dist", "public/plugins/jquery-knob")
  .copy("node_modules/select2/dist", "public/plugins/select2")
  .copy(
    "node_modules/bootstrap-daterangepicker/daterangepicker.*",
    "public/plugins/daterangepicker"
  )
  .copy("node_modules/bootstrap-datepicker/dist", "public/plugins/datepicker")
  .copy(
    "node_modules/pretty-checkbox/dist/pretty-checkbox.min.css",
    "public/plugins/pretty-checkbox/css"
  )
  .copy(
    "node_modules/vue-multiselect/dist/vue-multiselect.min.css",
    "public/plugins/vue-multiselect/css"
  );

if (mix.config.inProduction) {
  mix.version();
  mix.minify();
}
