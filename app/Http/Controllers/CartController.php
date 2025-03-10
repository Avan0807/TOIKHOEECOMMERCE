<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\AffiliateLink;
use Illuminate\Support\Str;
use Helper;
use Illuminate\Contracts\View\View as ViewContract;
use DB;

class CartController extends Controller
{
    protected $product = null;

    public function __construct(Product $product){
        $this->product = $product;
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(Request $request)
    {
        // Kiểm tra nếu sản phẩm hợp lệ
        if (empty($request->slug)) {
            request()->session()->flash('error','Sản phẩm không hợp lệ');
            return back();
        }
    
        $product = Product::where('slug', $request->slug)->first();
        if (empty($product)) {
            request()->session()->flash('error','Sản phẩm không hợp lệ');
            return back();
        }
    
        // Kiểm tra giỏ hàng đã có sản phẩm này chưa
        $already_cart = Cart::where('user_id', auth()->user()->id)
                            ->where('order_id', null)
                            ->where('product_id', $product->id)
                            ->first();
    
        // Lấy hash_ref từ query string trong URL (ví dụ: ?ref=xxxx)
        $hash_ref = $request->query('ref');  // Lấy giá trị 'ref' từ URL
        $doctor_id = null;
    
        if ($hash_ref) {
            // Tra cứu doctor_id từ bảng affiliate_links qua hash_ref
            $affiliate = DB::table('affiliate_links')->where('hash_ref', $hash_ref)->first();
            if ($affiliate) {
                $doctor_id = $affiliate->doctor_id;
            }
        }
    
        if ($already_cart) {
            // ✅ Cập nhật số lượng và tổng tiền
            $already_cart->quantity = $already_cart->quantity + 1;
            $already_cart->amount = $product->price + $already_cart->amount;
    
            // ✅ Kiểm tra tồn kho
            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
                return back()->with('error', 'Số lượng tồn kho không đủ!');
            }
    
            // ✅ Cập nhật doctor_id nếu có
            if ($doctor_id) {
                $already_cart->doctor_id = $doctor_id;
    
                // ✅ Tính hoa hồng từ commission_percentage
                $already_cart->commission = ($already_cart->amount) * ($product->commission_percentage / 100);
            }
    
            $already_cart->save();
        } else {
            // ✅ Tạo mới giỏ hàng
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price - ($product->price * $product->discount) / 100);
            $cart->quantity = 1;
            $cart->amount = $cart->price * $cart->quantity;
    
            // ✅ Kiểm tra tồn kho
            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) {
                return back()->with('error', 'Số lượng tồn kho không đủ!');
            }
    
            // ✅ Cập nhật doctor_id nếu có
            if ($doctor_id) {
                $cart->doctor_id = $doctor_id;
    
                // ✅ Tính hoa hồng từ commission_percentage
                $cart->commission = ($cart->amount) * ($product->commission_percentage / 100);
            }
    
            $cart->save();
    
            // ✅ Cập nhật wishlist (nếu có)
            Wishlist::where('user_id', auth()->user()->id)
                    ->where('cart_id', null)
                    ->update(['cart_id' => $cart->id]);
        }
    
        request()->session()->flash('success', 'Sản phẩm đã được thêm vào giỏ hàng');
        return back();
    }
    

    /**
     * Thêm sản phẩm vào giỏ với số lượng xác định
     */
    public function singleAddToCart(Request $request)
    {
        $request->validate([
            'slug' => 'required',
            'quant' => 'required',
        ]);
    
        $product = Product::where('slug', $request->slug)->first();
        if (!$product || $product->stock < $request->quant[1]) {
            return back()->with('error', 'Hết hàng, bạn có thể thêm sản phẩm khác.');
        }
    
        if ($request->quant[1] < 1) {
            request()->session()->flash('error', 'Sản phẩm không hợp lệ');
            return back();
        }
    
        // ✅ Lấy hash_ref từ query string hoặc session
        $hash_ref = $request->query('ref');
        $doctor_id = null;
    
        // ✅ Tra cứu doctor_id từ bảng affiliate_links nếu có hash_ref
        if ($hash_ref) {
            $affiliate = DB::table('affiliate_links')->where('hash_ref', $hash_ref)->first();
            if ($affiliate) {
                $doctor_id = $affiliate->doctor_id;
            }
        }
    
        // ✅ Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $already_cart = Cart::where('user_id', auth()->user()->id)
                            ->where('order_id', null)
                            ->where('product_id', $product->id)
                            ->first();
    
        if ($already_cart) {
            // ✅ Cập nhật số lượng và tổng tiền
            $already_cart->quantity += $request->quant[1];
            $already_cart->amount = ($already_cart->price * $already_cart->quantity);
    
            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
                return back()->with('error', 'Số lượng tồn kho không đủ!');
            }
    
            // ✅ Cập nhật doctor_id nếu có
            if ($doctor_id) {
                $already_cart->doctor_id = $doctor_id;
    
                // ✅ Tính hoa hồng dựa vào commission_percentage của sản phẩm
                $already_cart->commission = ($already_cart->amount) * ($product->commission_percentage / 100);
            }
    
            $already_cart->save();
        } else {
            // ✅ Tạo mới giỏ hàng
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price - ($product->price * $product->discount) / 100);
            $cart->quantity = $request->quant[1];
            $cart->amount = ($cart->price * $cart->quantity);
    
            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) {
                return back()->with('error', 'Số lượng tồn kho không đủ!');
            }
    
            // ✅ Gán doctor_id nếu có
            if ($doctor_id) {
                $cart->doctor_id = $doctor_id;
    
                // ✅ Tính hoa hồng dựa trên commission_percentage
                $cart->commission = ($cart->amount) * ($product->commission_percentage / 100);
            }
    
            $cart->save();
        }
    
        request()->session()->flash('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
        return back();
    }
    



    /**
     * Xóa một mục trong giỏ hàng
     */
    public function cartDelete(Request $request){
        $cart = Cart::find($request->id);
        if ($cart) {
            $cart->delete();
            request()->session()->flash('success','Đã xóa sản phẩm khỏi giỏ hàng');
            return back();
        }
        request()->session()->flash('error','Đã xảy ra lỗi, vui lòng thử lại');
        return back();
    }

    /**
     * Cập nhật giỏ hàng
     */
    public function cartUpdate(Request $request){
        if($request->quant){
            $error   = [];
            $success = '';

            foreach ($request->quant as $k => $quant) {
                $id   = $request->qty_id[$k];
                $cart = Cart::find($id);

                if($quant > 0 && $cart) {
                    // Kiểm tra tồn kho
                    if($cart->product->stock < $quant){
                        request()->session()->flash('error','Hết hàng');
                        return back();
                    }

                    // Cập nhật số lượng (không vượt quá tồn kho)
                    $cart->quantity = ($cart->product->stock > $quant) ? $quant : $cart->product->stock;
                    if ($cart->product->stock <= 0) continue;

                    // Tính lại giá tiền
                    $after_price = ($cart->product->price - ($cart->product->price*$cart->product->discount)/100);
                    $cart->amount = $after_price * $cart->quantity;
                    $cart->save();

                    $success = 'Cập nhật giỏ hàng thành công!';
                } else {
                    $error[] = 'Giỏ hàng không hợp lệ!';
                }
            }
            return back()->with($error)->with('success', $success);
        } else {
            return back()->with('Giỏ hàng không hợp lệ!');
        }
    }

    /**
     * Trang checkout
     */
    public function checkout(Request $request){
        return view('frontend.pages.checkout');
    }

    public function checkoutNow($product_id)
    {
        // Lấy thông tin sản phẩm từ DB
        $product = Product::findOrFail($product_id);
    
        // Kiểm tra nếu sản phẩm không tồn tại
        if (!$product) {
            request()->session()->flash('error', 'Sản phẩm không hợp lệ');
            return back();
        }
    
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cart = Cart::where('user_id', auth()->user()->id)
                    ->where('order_id', null)
                    ->where('product_id', $product->id)
                    ->first();
    
        if (!$cart) {
            // Nếu giỏ hàng không tồn tại, tạo mới
            $cart = new Cart();
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->quantity = 1;  // Mặc định 1 sản phẩm
            $cart->price = $product->price;
            $cart->amount = $product->price * $cart->quantity;
        } else {
            // Nếu giỏ hàng đã tồn tại, cập nhật thông tin
            $cart->quantity += 1;
            $cart->amount = $cart->price * $cart->quantity;
        }
    
        // Lấy giá trị ref từ URL và lưu vào session
        $ref = request()->query('ref');
        if ($ref) {
            session(['hash_ref' => $ref]);  // Lưu giá trị ref vào session với khóa 'hash_ref'
        }
    
        // Lấy giá trị hash_ref từ session
        $hash_ref = session('hash_ref');
    
        // Kiểm tra xem hash_ref có tồn tại và tìm affiliate link
        $affiliateLink = AffiliateLink::where('hash_ref', $hash_ref)->first();
    
        // Gán doctor_id nếu tìm thấy affiliate link
        if ($affiliateLink && $affiliateLink->doctor_id) {
            $cart->doctor_id = $affiliateLink->doctor_id;
    
            // ✅ Tính hoa hồng từ commission_percentage của sản phẩm
            $cart->commission = ($cart->amount) * ($product->commission_percentage / 100);
        } else {
            $cart->doctor_id = null;
            $cart->commission = 0; // Không có hoa hồng nếu không có bác sĩ giới thiệu
        }
    
        // Lưu giỏ hàng
        $cart->save();
    
        // Chuyển hướng đến trang thanh toán
        return redirect()->route('checkout');
    }
    


}
