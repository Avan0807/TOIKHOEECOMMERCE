<?php

namespace App\Http\Controllers;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function index(Request $request){
        return redirect()->route($request->user()->role);
    }


    public function home(){
        // Truy vấn danh mục
        $category = Category::where('status', 'active')
            ->where('is_parent', 1)
            ->orderBy('title', 'ASC')
            ->get();

        // Truy vấn sản phẩm nổi bật
        $featured = Product::with('cat_info', 'brand') // Sửa 'category' thành 'cat_info'
            ->where('status', 'active')
            ->where('is_featured', 1)
            ->orderBy('price', 'DESC')
            ->limit(2)
            ->get();

        // Truy vấn sản phẩm mới nhất
        $products = Product::with('cat_info', 'brand', 'getReview') // Sửa 'category' thành 'cat_info'
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->get();

        // Truy vấn banner
        $banners = Banner::where('status', 'active')
            ->orderBy('id', 'DESC')
            ->limit(3)
            ->get();

        return view('frontend.index', [
            'featured' => $featured,
            'banners' => $banners,
            'product_lists' => $products,
            'category_lists' => $category
        ]);
    }




    public function aboutUs(){
        return view('frontend.pages.about-us');
    }

    public function contact(){
        return view('frontend.pages.contact');
    }

    public function productDetail($slug, Request $request){
        if ($request->has('ref')) {
            session(['doctor_id' => $request->ref]);
        }
        $product_detail = Product::getProductBySlug($slug);
        return view('frontend.pages.product_detail')->with('product_detail', $product_detail);
    }

    public function productGrids()
    {
        $request = request(); // Sử dụng request() để dễ đọc hơn
        $products = Product::query();

        // Lọc theo danh mục (category)
        if ($request->has('category')) {
            $slug = explode(',', $request->category);
            $cat_ids = Category::whereIn('slug', $slug)->pluck('id');
            if ($cat_ids->isNotEmpty()) {
                $products->whereIn('cat_id', $cat_ids);
            }
        }

        // Lọc theo thương hiệu (brand)
        if ($request->has('brand')) {
            $slugs = explode(',', $request->brand);
            $brand_ids = Brand::whereIn('slug', $slugs)->pluck('id');
            if ($brand_ids->isNotEmpty()) {
                $products->whereIn('brand_id', $brand_ids);
            }
        }

        // Sắp xếp sản phẩm
        if ($request->has('sortBy')) {
            $products->orderBy($request->sortBy, 'ASC');
        }

        // Lọc theo giá
        if ($request->has('price')) {
            $price = explode('-', $request->price);
            if (count($price) == 2) {
                $products->whereBetween('price', [$price[0], $price[1]]);
            }
        }

        // Lấy sản phẩm mới nhất (cache để giảm tải)
        $recent_products = Cache::remember('recent_products', 30, function () {
            return Product::active()->latest()->limit(3)->get();
        });

        // Phân trang sản phẩm
        $products = $products->active()->paginate($request->input('show', 9));

        return view('frontend.pages.product-grids', compact('products', 'recent_products'));
    }

    public function productLists()
    {
        $request = request(); // Sử dụng request() thay vì $_GET
        $products = Product::query();

        // Lọc theo danh mục (category)
        if ($request->has('category')) {
            $slug = explode(',', $request->category);
            $cat_ids = Category::whereIn('slug', $slug)->pluck('id');
            if ($cat_ids->isNotEmpty()) {
                $products->whereIn('cat_id', $cat_ids);
            }
        }

        // Lọc theo thương hiệu (brand)
        if ($request->has('brand')) {
            $slugs = explode(',', $request->brand);
            $brand_ids = Brand::whereIn('slug', $slugs)->pluck('id');
            if ($brand_ids->isNotEmpty()) {
                $products->whereIn('brand_id', $brand_ids);
            }
        }

        // Sắp xếp sản phẩm
        if ($request->has('sortBy')) {
            $products->orderBy($request->sortBy, 'ASC');
        }

        // Lọc theo giá
        if ($request->has('price')) {
            $price = explode('-', $request->price);
            if (count($price) == 2) {
                $products->whereBetween('price', [$price[0], $price[1]]);
            }
        }

        // Lấy sản phẩm mới nhất (cache để giảm tải)
        $recent_products = Cache::remember('recent_products', 30, function () {
            return Product::active()->latest()->limit(3)->get();
        });

        // Phân trang sản phẩm
        $products = $products->active()->paginate($request->input('show', 6));

        return view('frontend.pages.product-lists', compact('products', 'recent_products'));
    }

    public function productFilter(Request $request)
    {
        $queryParams = [];

        if ($request->has('show')) {
            $queryParams['show'] = $request->input('show');
        }

        if ($request->has('sortBy')) {
            $queryParams['sortBy'] = $request->input('sortBy');
        }

        if ($request->has('category')) {
            $queryParams['category'] = implode(',', $request->input('category'));
        }

        if ($request->has('brand')) {
            $queryParams['brand'] = implode(',', $request->input('brand'));
        }

        if ($request->has('price_range')) {
            $queryParams['price'] = $request->input('price_range');
        }

        // Xây dựng query string tự động
        $queryString = http_build_query($queryParams);

        // Điều hướng đến trang tương ứng
        return request()->is('e-shop.loc/product-grids')
            ? redirect()->route('product-grids', $queryString)
            : redirect()->route('product-lists', $queryString);
    }

    public function productSearch(Request $request)
    {
        $searchTerm = trim($request->input('search'));

        // Lấy sản phẩm mới nhất (cache để giảm tải)
        $recent_products = Cache::remember('recent_products', 30, function () {
            return Product::active()->latest()->limit(3)->get();
        });

        // Kiểm tra xem có từ khóa tìm kiếm không, nếu không thì trả về danh sách trống
        if (empty($searchTerm)) {
            return view('frontend.pages.product-grids', compact('recent_products'))
                   ->with('products', collect([]));
        }

        // Tìm kiếm sản phẩm theo nhiều tiêu chí
        $products = Product::active()
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('slug', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhere('summary', 'like', "%{$searchTerm}%")
                      ->orWhereRaw("CAST(price AS CHAR) LIKE ?", ["%{$searchTerm}%"]); // Tối ưu tìm kiếm số
            })
            ->orderBy('id', 'DESC')
            ->paginate(9);

        return view('frontend.pages.product-grids', compact('products', 'recent_products'));
    }


    public function productBrand(Request $request)
    {
        $products = Brand::getProductByBrand($request->slug);
        $recent_products = Cache::remember('recent_products', 30, function () {
            return Product::active()->latest()->limit(3)->get();
        });

        // Kiểm tra nếu không có sản phẩm từ thương hiệu để tránh lỗi
        $productList = $products ? $products->products : collect([]);

        // Xác định trang cần điều hướng
        $view = request()->routeIs('product-grids') ? 'frontend.pages.product-grids' : 'frontend.pages.product-lists';

        return view($view, compact('productList', 'recent_products'));
    }

    public function productCat(Request $request)
    {
        $category = Category::getProductByCat($request->slug);

        // Đảm bảo biến $products luôn là collection để tránh lỗi count()
        $products = $category ? collect($category->products) : collect([]);

        $recent_products = Cache::remember('recent_products', 30, function () {
            return Product::active()->latest()->limit(3)->get();
        });

        // Xác định trang cần điều hướng
        $view = request()->routeIs('product-grids') ? 'frontend.pages.product-grids' : 'frontend.pages.product-lists';

        return view($view, compact('products', 'recent_products'));
    }


    public function productSubCat(Request $request)
    {
        $products = Category::getProductBySubCat($request->sub_slug);
        $recent_products = Cache::remember('recent_products', 30, function () {
            return Product::active()->latest()->limit(3)->get();
        });

        // Kiểm tra nếu không có sản phẩm từ danh mục con để tránh lỗi
        $productList = $products ? $products->sub_products : collect([]);

        // Xác định trang cần điều hướng
        $view = request()->routeIs('product-grids') ? 'frontend.pages.product-grids' : 'frontend.pages.product-lists';

        return view($view, compact('productList', 'recent_products'));
    }


    // Login

    public function login(){
        return view('frontend.pages.login');
    }

    public function loginSubmit(Request $request){
        $data = $request->all();
        if(Auth::attempt(['phone' => $data['phone'], 'password' => $data['password'], 'status' => 'active'])){
            Session::put('user', $data['phone']);
            request()->session()->flash('success', 'Đã đăng nhập thành công!');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Số điện thoại và mật khẩu không hợp lệ, vui lòng thử lại!');
            return redirect()->back();
        }
    }

    public function logout(){
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success', 'Đã đăng xuất thành công');
        return back();
    }

    public function register(){
        return view('frontend.pages.register');
    }

    public function registerSubmit(Request $request){
        $this->validate($request, [
            'name' => 'string|required|min:2',
            'phone' => 'required|numeric|digits_between:10,15|unique:users,phone',
            'password' => 'required|min:6|confirmed',
        ]);
        $data = $request->all();
        $check = $this->create($data);
        Session::put('user', $data['phone']);
        if($check){
            request()->session()->flash('success', 'Đã đăng ký thành công');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Vui lòng thử lại!');
            return back();
        }
    }
    // Reset password
    public function showResetForm(){
        return view('auth.passwords.old-reset');
    }
    public function create(array $data){
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'status' => 'active'
        ]);
    }


}
