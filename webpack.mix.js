// webpack.mix.js

let mix = require('laravel-mix');

var argv = require('minimist')(process.argv.slice(2));


require('laravel-mix-purgecss');
require('laravel-mix-clean-css');

mix.webpackConfig({
    externals: {
        "jquery": "jQuery"
    }
});

mix.options({ processCssUrls: false });

mix.js('src/index.js', 'build');

mix.js('src/index-gutenberg.js', '/build/index-gutenberg.js').react();

mix.sass('sass/style.scss', '/style.min.css')
    .sourceMaps(false, 'source-map')
    .purgeCss({
     //enabled: true,
       content:  ['template-parts/*.php', 'template-parts/blocks/*.php', 'inc/*.php', 'src/js/*.js', '*.php'],
    safelist: [
    'rtl',
    'home',
    'blog',
    'archive',
    'date',
    'error404',
    'logged-in',
    'admin-bar',
    'no-customize-support',
    'custom-background',
    'wp-custom-logo',
    'alignnone',
    'alignright',
    'alignleft',
    'wp-caption',
    'wp-caption-text',
    'screen-reader-text',
    'comment-list',
    'slick-list',
    'slick',
    'partnerships-page',
    'with_frm_style',
    'frm_form_field',
    'form-field',
    'ez-toc-widget-container', 
    'wp-block-table', 
    'is-style-hso-table', 
    'privacy-policy',
    'menu-item-new',
    'tiles-1',
    'menu-item-resource',
    'faq-page',
    'left-border',
    /^search(-.*)?$/,
    /^(.*)-template(-.*)?$/,
    /^(.*)?-?single(-.*)?$/,
    /^postid-(.*)?$/,
    /^attachmentid-(.*)?$/,
    /^attachment(-.*)?$/,
    /^page(-.*)?$/,
    /^(post-type-)?archive(-.*)?$/,
    /^author(-.*)?$/,
    /^category(-.*)?$/,
    /^tag(-.*)?$/,
    /^tax-(.*)?$/,
    /^term-(.*)?$/,
    /^(.*)?-?paged(-.*)?$/,
    /^slick-(.*)?$/,    
    /^contact-(.*)?$/,
    /^content-block-(.*)?$/,
    /^frm_(.*)?$/,
    /^wp-block-(.*)?$/,
    /^ez-toc-(.*)?$/,
    /^plans-(.*)?$/,
    /^(.*)?-?cookie(-.*)?$/,
    /^wt-cli-(.*)?$/,
    /^modal-(.*)?$/,
    /^desktop-(.*)?$/,
    /^mobile-(.*)?$/,
    /^p-(.*)?$/,
    /^pr-(.*)?$/,
    /^pl-(.*)?$/,
    /^pt-(.*)?$/,
    /^pb-(.*)?$/,
    /^m-(.*)?$/,
    /^mr-(.*)?$/,
    /^ml-(.*)?$/,
    /^mt-(.*)?$/,
    /^mb-(.*)?$/,
    /^mx-(.*)?$/,
    /^d-(.*)?$/,
    /^w-(.*)?$/,
    /^flex-(.*)?$/,
    /^justify-(.*)?$/,
    /^align(.*)?$/,
    /^border-(.*)?$/,
    /^data-(.*)?$/
    ]
   })
  .cleanCss({
    level: 2
  })

mix.sass('src/sass/admin-styles.scss', 'src/css/admin-styles.css')
    .sourceMaps({generateForProduction: false});



if (argv.user === 'brad') {
    //add in your browserSync settings that you need
    mix.browserSync({
        proxy: 'highspeedoptions.test',
        browser: 'firefox'
    });
 } else if (argv.user === 'edward') {
    //add in your browserSync settings that you need
    mix.browserSync({
        proxy: 'hso.test'
    });
 } else if (argv.user === 'jessi') {
    //add in your browserSync settings that you need
    mix.browserSync({
        proxy: 'localhost:8888/hso',
        browser: 'chrome'
    });
 } else if (argv.user === 'ryan') {
    //add in your browserSync settings that you need
    mix.browserSync({
        proxy: 'localhost:8888/highspeedoptions'
    });
 } else if (argv.user === 'syed') {
     //add in your browserSync settings that you need
    mix.browserSync({
        proxy: 'highspeedoptions.test'
    });
 }   




