const mix = require('laravel-mix');

// Kết hợp và nén các tệp CSS
mix.styles([
    'public/frontend/css/bootstrap.css',
    'public/frontend/css/magnific-popup.min.css',
    'public/frontend/css/font-awesome.css',
    'public/frontend/css/jquery.fancybox.min.css',
    'public/frontend/css/themify-icons.css',
    'public/frontend/css/niceselect.css',
    'public/frontend/css/animate.css',
    'public/frontend/css/flex-slider.min.css',
    'public/frontend/css/owl-carousel.css',
    'public/frontend/css/slicknav.min.css',
    'public/frontend/css/jquery-ui.css',
    'public/frontend/css/reset.css',
    'public/frontend/css/style.css',
    'public/frontend/css/responsive.css',
], 'public/css/all.css')
.minify('public/css/all.css');

// Kết hợp và nén các tệp JavaScript
mix.scripts([
    'public/frontend/js/jquery.min.js',
    'public/frontend/js/jquery-migrate-3.0.0.js',
    'public/frontend/js/jquery-ui.min.js',
    'public/frontend/js/popper.min.js',
    'public/frontend/js/bootstrap.min.js',
    'public/frontend/js/slicknav.min.js',
    'public/frontend/js/owl-carousel.js',
    'public/frontend/js/magnific-popup.js',
    'public/frontend/js/waypoints.min.js',
    'public/frontend/js/finalcountdown.min.js',
    'public/frontend/js/nicesellect.js',
    'public/frontend/js/flex-slider.js',
    'public/frontend/js/scrollup.js',
    'public/frontend/js/onepage-nav.min.js',
    'public/frontend/js/isotope/isotope.pkgd.min.js',
    'public/frontend/js/easing.js',
    'public/frontend/js/active.js',
], 'public/js/all.js')
.minify('public/js/all.js');

// Sao chép tệp font vào thư mục public/fonts
mix.copy('public/frontend/fonts', 'public/fonts');
