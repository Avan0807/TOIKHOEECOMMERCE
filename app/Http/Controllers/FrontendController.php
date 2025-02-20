<?php

namespace App\Http\Controllers;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\Settings;
use DB;
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

    public function home() {
        
        // ✅ Truy vấn banner
        $banners = Banner::where('status', 'active')
            ->orderBy('id', 'DESC')
            ->limit(3)
            ->get();
        // Truy vấn chỉ lấy 5 danh mục
        $categories = Category::where('status', 'active')->take(3)->get();

        // ✅ Lọc danh mục cha ngay trong PHP để tránh truy vấn SQL lặp
        $parent_categories = $categories->where('is_parent', 1)->sortBy('title');

        
        // ✅ Truy vấn sản phẩm nổi bật (hot)
        $popular_products = Product::where('status', 'active')
            ->where('condition', 'hot')
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->get();
    
        // ✅ Truy vấn sản phẩm nổi bật (featured)
        $featured = Product::where('status', 'active')
            ->where('is_featured', 1)
            ->orderBy('price', 'DESC')
            ->limit(2)
            ->get();
    
        // ✅ Truy vấn sản phẩm mới nhất
        $product_lists = Product::where('status', 'active')
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->get();
    
        // ✅ Truy vấn tất cả rating cùng lúc
        $product_ids = $product_lists->pluck('id');
        $reviews = DB::table('product_reviews')
            ->whereIn('product_id', $product_ids)
            ->selectRaw('product_id, AVG(rate) as avg_rate, COUNT(rate) as rate_count')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');
    
        // ✅ Gán dữ liệu rating vào danh sách sản phẩm
        $product_lists->transform(function ($product) use ($reviews) {
            $product->rate = $reviews[$product->id]->avg_rate ?? 0;
            $product->rate_count = $reviews[$product->id]->rate_count ?? 0;
            return $product;
        });

        $settings = Settings::first(); 
    
        return view('frontend.index', [
            'settings' => $settings,
            'featured' => $featured,
            'banners' => $banners,
            'product_lists' => $product_lists,
            'category_lists' => $categories, // ✅ Chỉ truyền một biến duy nhất
            'parent_categories' => $parent_categories, // ✅ Lọc danh mục cha từ PHP
            'popular_products' => $popular_products
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
        $request = request(); 
        $productsQuery = Product::query();
    
        // Lọc theo danh mục (category)
        if ($request->has('category')) {
            $slug = explode(',', $request->category);
            $cat_ids = Category::whereIn('slug', $slug)->pluck('id')->toArray();
            if (!empty($cat_ids)) {
                $productsQuery->whereIn('cat_id', $cat_ids);
            }
        }
    
        // Lọc theo thương hiệu (brand)
        if ($request->has('brand')) {
            $slugs = explode(',', $request->brand);
            $brand_ids = Brand::whereIn('slug', $slugs)->pluck('id')->toArray();
            if (!empty($brand_ids)) {
                $productsQuery->whereIn('brand_id', $brand_ids);
            }
        }
    
        // Sắp xếp sản phẩm
        if ($request->has('sortBy')) {
            $productsQuery->orderBy($request->sortBy, 'ASC');
        }
    
        // Lọc theo giá
        if ($request->has('price')) {
            $price = explode('-', $request->price);
            if (count($price) == 2) {
                $productsQuery->whereBetween('price', [$price[0], $price[1]]);
            }
        }
    
        // Eager load các quan hệ cần thiết (danh mục, thương hiệu và bình luận)
        $recent_products = Product::active()
            ->with(['cat_info', 'brand', 'getReview']) // Eager load reviews
            ->latest()->limit(3)->get();
    
        // Tối ưu phân trang với dữ liệu chỉ cần thiết
        $products = $productsQuery->with(['cat_info', 'brand', 'getReview']) // Eager load reviews
            ->active()
            ->paginate($request->input('show', 9));
    
        // Lấy giá trị max cho slider giá một lần
        $max_price = DB::table('products')->max('price');
    
        // Truy vấn danh mục cha và con một lần
        $menu = Category::getAllParentWithChild();
    
        return view('frontend.pages.product-grids', compact('products', 'recent_products', 'max_price', 'menu'));
    }
    
    public function productLists()
    {
        $request = request(); 
        $products = Product::query();
    
        // Eager load các danh mục và thương hiệu
        $categories = Category::with('child_cat')->get();
        $brands = Brand::orderBy('title', 'ASC')->where('status', 'active')->get();
    
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
    
        // Lấy sản phẩm mới nhất
        $recent_products = Cache::remember('recent_products', 30, function () {
            return Product::active()->latest()->limit(3)->get();
        });
    
        // Phân trang sản phẩm
        $products = $products->active()->paginate($request->input('show', 6));
    
        return view('frontend.pages.product-lists', compact('products', 'recent_products', 'categories', 'brands'));
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
    
        // ✅ Truy vấn sản phẩm mới nhất (không cần cache)
        $recent_products = Product::active()->latest()->limit(3)->get();
    
        // ✅ Nếu không có từ khóa tìm kiếm, trả về danh sách trống
        if (empty($searchTerm)) {
            return view('frontend.pages.product-grids', [
                'products' => [],
                'recent_products' => $recent_products
            ]);
        }
    
        // ✅ Tối ưu tìm kiếm: Thêm `select()` để chỉ lấy cột cần thiết, giảm tải query
        $products = Product::active()
            ->select('id', 'title', 'photo','slug', 'description', 'summary', 'price')
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('slug', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhere('summary', 'like', "%{$searchTerm}%")
                      ->orWhereRaw("CAST(price AS CHAR) LIKE ?", ["%{$searchTerm}%"]); // Tối ưu tìm kiếm số
            })
            ->orderByDesc('id')
            ->paginate(9);
    
        return view('frontend.pages.product-grids', compact('products', 'recent_products'));
    }
    

    public function productBrand(Request $request)
    {
        // ✅ Lấy sản phẩm theo thương hiệu
        $products = Brand::getProductByBrand($request->slug);
    
        // ✅ Lấy sản phẩm mới nhất (không cần cache)
        $recent_products = Product::active()->latest()->limit(3)->get();
    
        // ✅ Kiểm tra nếu thương hiệu không có sản phẩm để tránh lỗi
        $productList = $products ? $products->products : collect([]);
    
        // ✅ Xác định view cần điều hướng
        $view = request()->routeIs('product-grids') ? 'frontend.pages.product-grids' : 'frontend.pages.product-lists';
    
        return view($view, compact('productList', 'recent_products'));
    }
    

    public function productCat(Request $request)
    {
        // ✅ Lấy danh mục sản phẩm theo slug
        $category = Category::getProductByCat($request->slug);
    
        // ✅ Kiểm tra nếu danh mục tồn tại
        $products = $category ? collect($category->products) : collect([]);
        $categories = Category::all(); // Lấy danh sách danh mục sản phẩm
    
        // ✅ Lấy sản phẩm mới nhất
        $recent_products = Product::active()->latest()->limit(3)->get();
    
        // ✅ Xác định view cần điều hướng
        $view = request()->routeIs('product-grids') ? 'frontend.pages.product-grids' : 'frontend.pages.product-lists';
    
        return view($view, compact('products', 'recent_products', 'categories'));
    }
    
    


    public function productSubCat($slug, $sub_slug)
    {
        // ✅ Tìm danh mục cha
        $category = Category::where('slug', $slug)->first();
        
        // ✅ Nếu danh mục cha không tồn tại, trả về lỗi 404
        if (!$category) {
            abort(404, 'Danh mục không tồn tại');
        }
    
        // ✅ Tìm danh mục con
        $subCategory = Category::where('slug', $sub_slug)->where('parent_id', $category->id)->first();
    
        // ✅ Nếu danh mục con không tồn tại, trả về lỗi 404
        if (!$subCategory) {
            abort(404, 'Danh mục con không tồn tại');
        }
    
        // ✅ Lấy sản phẩm theo danh mục con
        $products = Product::where('category_id', $subCategory->id)->get();
    
        return view('frontend.pages.product-sub-cat', compact('products', 'subCategory'));
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
