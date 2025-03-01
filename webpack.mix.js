const mix = require('laravel-mix');
require('laravel-mix-purgecss'); // Import PurgeCSS

// Kết hợp, nén và loại bỏ CSS không dùng với PurgeCSS
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
.minify('public/css/all.css')
.purgeCss({
    content: [
        'resources/views/**/*.blade.php', // Xóa CSS không dùng từ file Blade
        'resources/js/**/*.vue', // Nếu dùng VueJS
        'resources/js/**/*.js' // Nếu có file JS frontend
    ],
    safelist: ['active', 'show'], // Giữ lại các class cần thiết để tránh lỗi
});

// Kết hợp, nén và tối ưu hóa JS
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

// Sao chép font vào thư mục public/fonts
mix.copy('public/frontend/fonts', 'public/fonts');

// Thêm versioning để cache tốt hơn
mix.version();
