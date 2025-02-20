<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\Models\Settings;
use App\Models\Category;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $menu = Category::where('status', 'active')->get();

        // ✅ Chỉ chia sẻ nếu $menu có dữ liệu
        if ($menu) {
            View::share('menu', $menu);
        }

        $settings = Settings::first(); // ✅ Chỉ chạy 1 lần
        View::share('settings', $settings); // ✅ Chia sẻ biến settings cho toàn bộ view
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();
    }
}
