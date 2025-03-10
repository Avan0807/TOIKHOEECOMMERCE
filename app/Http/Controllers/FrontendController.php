<?php

namespace App\Http\Controllers;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Brand;
use App\Models\Booking;
use App\Models\Doctor;
use App\User;
use Auth;
use Session;
use Newsletter;
use DB;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{

    public function index(Request $request){
        return redirect()->route($request->user()->role);
    }
    
    public function home() {
        // Bật query log
    
        // Lấy banner từ cache với FORCE INDEX
        $banners = Cache::remember('banners', 60 * 60, function () {
            return collect(DB::select("
                SELECT `id`, `title`, `photo`, `description`
                FROM banners
                WHERE `status` = ?
                ORDER BY `id` DESC
                LIMIT 3
            ", ['active']));
        });
        
        
        // Lấy sản phẩm mới nhất (cuối trang)
        $recent_products = Cache::remember('recent_products', 60 * 60, function () {
            return Product::where('status', 'active')
                ->orderBy('id', 'DESC')
                ->limit(6)
                ->get();
        });
    
        // Lấy sản phẩm mới nhất (lọc sản phẩm)
        $recentlyAddedProducts = Cache::remember('recently_added_products', 60 * 60, function () {
            return Product::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        });
    
        // Lấy sản phẩm nổi bật
        $popular_products = Cache::remember('popular_products', 60 * 60, function () {
            return Product::where('status', 'active')
                ->where('condition', 'hot')
                ->orderBy('id', 'DESC')
                ->limit(8)
                ->get();
        });
    
        // Lấy sản phẩm với thông tin review
        $products = Product::where('status', 'active')
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->withCount('reviews')
            ->withAvg('reviews', 'rate')
            ->get();
    
        // Lấy 3 danh mục có is_parent = 1
        $category_lists = Cache::remember('category_lists', 60 * 60, function () {
            return Category::where('status', 'active')
                ->where('is_parent', 1)
                ->limit(3)
                ->get();
        });
    
        // Lấy danh mục cho phần lọc
        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where('status', 'active')
                ->where('is_parent', 1)
                ->get();
        });
    
    
        return view('frontend.index')
            ->with('banners', $banners)
            ->with('product_lists', $products)
            ->with('category_lists', $category_lists)
            ->with('categories', $categories)
            ->with('recentlyAddedProducts', $recentlyAddedProducts)
            ->with('popular_products', $popular_products)
            ->with('recent_products', $recent_products);
    }
    

    public function aboutUs(){
        return view('frontend.pages.about-us');
    }

    public function contact(){
        return view('frontend.pages.contact');
    }

    public function productDetail($slug){
        $product_detail= Product::getProductBySlug($slug);
        return view('frontend.pages.product_detail')->with('product_detail',$product_detail);
    }

    public function productGrids(){
        $products=Product::query();

        if(!empty($_GET['category'])){
            $slug=explode(',',$_GET['category']);
            $cat_ids=Category::select('id')->whereIn('slug',$slug)->pluck('id')->toArray();
            $products->whereIn('cat_id',$cat_ids);
        }
        if (!empty($_GET['brand'])) {
            $slugs = explode(',', $_GET['brand']);
            $brand_ids = Brand::select('id')->whereIn('slug', $slugs)->pluck('id')->toArray();
            $products->whereIn('brand_id', $brand_ids);
        }
        if(!empty($_GET['sortBy'])){
            if($_GET['sortBy']=='title'){
                $products=$products->where('status','active')->orderBy('title','ASC');
            }
            if($_GET['sortBy']=='price'){
                $products=$products->orderBy('price','ASC');
            }
        }

        if(!empty($_GET['price'])){
            $price=explode('-',$_GET['price']);
            $products->whereBetween('price',$price);
        }

        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        // Sort by number
        if(!empty($_GET['show'])){
            $products=$products->where('status','active')->paginate($_GET['show']);
        }
        else{
            $products=$products->where('status','active')->paginate(9);
        }


        return view('frontend.pages.product-grids')->with('products',$products)->with('recent_products',$recent_products);
    }
    public function productLists(){
        $products=Product::query();

        if(!empty($_GET['category'])){
            $slug=explode(',',$_GET['category']);
            $cat_ids=Category::select('id')->whereIn('slug',$slug)->pluck('id')->toArray();
            $products->whereIn('cat_id',$cat_ids)->paginate;
        }
        if(!empty($_GET['brand'])){
            $slugs=explode(',',$_GET['brand']);
            $brand_ids=Brand::select('id')->whereIn('slug',$slugs)->pluck('id')->toArray();
            $products->whereIn('brand_id',$brand_ids);
        }
        if(!empty($_GET['sortBy'])){
            if($_GET['sortBy']=='title'){
                $products=$products->where('status','active')->orderBy('title','ASC');
            }
            if($_GET['sortBy']=='price'){
                $products=$products->orderBy('price','ASC');
            }
        }

        if(!empty($_GET['price'])){
            $price=explode('-',$_GET['price']);
            $products->whereBetween('price',$price);
        }

        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        // Sort by number
        if(!empty($_GET['show'])){
            $products=$products->where('status','active')->paginate($_GET['show']);
        }
        else{
            $products=$products->where('status','active')->paginate(6);
        }
        // Sort by name , price, category


        return view('frontend.pages.product-lists')->with('products',$products)->with('recent_products',$recent_products);
    }

    public function productFilter(Request $request){
            $data= $request->all();
            // return $data;
            $showURL="";
            if(!empty($data['show'])){
                $showURL .='&show='.$data['show'];
            }

            $sortByURL='';
            if(!empty($data['sortBy'])){
                $sortByURL .='&sortBy='.$data['sortBy'];
            }

            $catURL="";
            if(!empty($data['category'])){
                foreach($data['category'] as $category){
                    if(empty($catURL)){
                        $catURL .='&category='.$category;
                    }
                    else{
                        $catURL .=','.$category;
                    }
                }
            }

            $brandURL="";
            if(!empty($data['brand'])){
                foreach($data['brand'] as $brand){
                    if(empty($brandURL)){
                        $brandURL .='&brand='.$brand;
                    }
                    else{
                        $brandURL .=','.$brand;
                    }
                }
            }

            $priceRangeURL="";
            if(!empty($data['price_range'])){
                $priceRangeURL .='&price='.$data['price_range'];
            }
            if(request()->is('e-shop.loc/product-grids')){
                return redirect()->route('product-grids',$catURL.$brandURL.$priceRangeURL.$showURL.$sortByURL);
            }
            else{
                return redirect()->route('product-lists',$catURL.$brandURL.$priceRangeURL.$showURL.$sortByURL);
            }
    }
    public function productSearch(Request $request){
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        $products=Product::orwhere('title','like','%'.$request->search.'%')
                    ->orwhere('slug','like','%'.$request->search.'%')
                    ->orwhere('description','like','%'.$request->search.'%')
                    ->orwhere('summary','like','%'.$request->search.'%')
                    ->orwhere('price','like','%'.$request->search.'%')
                    ->orderBy('id','DESC')
                    ->paginate('9');
        return view('frontend.pages.product-grids')->with('products',$products)->with('recent_products',$recent_products);
    }

    public function productBrand(Request $request){
        $products=Brand::getProductByBrand($request->slug);
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        if(request()->is('e-shop.loc/product-grids')){
            return view('frontend.pages.product-grids')->with('products',$products->products)->with('recent_products',$recent_products);
        }
        else{
            return view('frontend.pages.product-lists')->with('products',$products->products)->with('recent_products',$recent_products);
        }

    }
    public function productCat(Request $request){
        $products=Category::getProductByCat($request->slug);
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();

        if(request()->is('e-shop.loc/product-grids')){
            return view('frontend.pages.product-grids')->with('products',$products->products)->with('recent_products',$recent_products);
        }
        else{
            return view('frontend.pages.product-lists')->with('products',$products->products)->with('recent_products',$recent_products);
        }

    }
    public function productSubCat(Request $request){
        $products=Category::getProductBySubCat($request->sub_slug);
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();

        if(request()->is('e-shop.loc/product-grids')){
            return view('frontend.pages.product-grids')->with('products',$products->sub_products)->with('recent_products',$recent_products);
        }
        else{
            return view('frontend.pages.product-lists')->with('products',$products->sub_products)->with('recent_products',$recent_products);
        }

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

    public function create(array $data){
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'status' => 'active'
        ]);
    }

    // Reset password
    public function showResetForm(){
        return view('auth.passwords.old-reset');
    }

    public function subscribe(Request $request){
        if(! Newsletter::isSubscribed($request->email)){
                Newsletter::subscribePending($request->email);
                if(Newsletter::lastActionSucceeded()){
                    request()->session()->flash('success','Đã đăng ký! Vui lòng kiểm tra email của bạn');
                    return redirect()->route('home');
                }
                else{
                    Newsletter::getLastError();
                    return back()->with('error','Có gì đó không ổn! Vui lòng thử lại');
                }
            }
            else{
                request()->session()->flash('error','Đã đăng ký');
                return back();
            }
    }


}
