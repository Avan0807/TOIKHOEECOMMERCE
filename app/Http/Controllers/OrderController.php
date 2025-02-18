<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\AffiliateOrder;
use App\User;
use PDF;
use DB;
use Notification;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;
use Illuminate\Http\RedirectResponse;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng (phía admin).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::orderBy('id','DESC')->paginate(10);
        return view('backend.order.index')->with('orders',$orders);
    }

    /**
     * Tạo đơn hàng mới (trang create nếu có).
     */
    public function create()
    {
        //
    }

    /**
     * Lưu đơn hàng mới.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


     public function store(Request $request): RedirectResponse
     {
         // ✅ Validate dữ liệu đầu vào
         $this->validate($request, [
             'first_name' => 'string|required',
             'last_name'  => 'string|required',
             'address1'   => 'string|required',
             'address2'   => 'string|nullable',
             'coupon'     => 'nullable|numeric',
             'phone'      => 'numeric|required',
             'post_code'  => 'string|nullable',
             'email'      => 'string|required',
             'doctor_id'  => 'nullable|exists:doctors,id' // ✅ Đảm bảo doctor_id hợp lệ nếu có
         ]);

         // ✅ Kiểm tra giỏ hàng
         if (Cart::where('user_id', auth()->id())->whereNull('order_id')->doesntExist()) {
             request()->session()->flash('error', 'Giỏ hàng đang trống!');
             return redirect()->back();
         }

         // ✅ Lấy toàn bộ dữ liệu từ request
         $order_data = $request->all();

         // ✅ Nếu không có doctor_id, loại bỏ nó khỏi dữ liệu insert
         if (!$request->filled('doctor_id')) {
             unset($order_data['doctor_id']);
         }

         // ✅ Tạo order
         $order_data['order_number'] = 'ORD-' . strtoupper(Str::random(10));
         $order_data['user_id'] = auth()->id();
         $order_data['shipping_id'] = $request->shipping;
         $order_data['sub_total'] = Helper::totalCartPrice();
         $order_data['quantity'] = Helper::cartCount();

         // ✅ Tính toán total_amount (bao gồm phí vận chuyển nếu có)
         if ($request->shipping) {
             $shipping = Shipping::find($request->shipping);
             $order_data['total_amount'] = Helper::totalCartPrice() + ($shipping ? $shipping->price : 0);
         } else {
             $order_data['total_amount'] = Helper::totalCartPrice();
         }

         // ✅ Áp dụng coupon nếu có
         if (session('coupon')) {
             $order_data['coupon'] = session('coupon')['value'];
             $order_data['total_amount'] -= session('coupon')['value'];
         }

         // ✅ Xử lý thanh toán
         $order_data['payment_method'] = $request->payment_method ?? 'cod';
         $order_data['payment_status'] = in_array($request->payment_method, ['paypal', 'cardpay']) ? 'paid' : 'Unpaid';


         // ✅ Kiểm tra Query Log trước khi insert
         DB::enableQueryLog();
         $order = Order::create($order_data);

         // ✅ Kiểm tra lỗi insert
         if (!$order) {
             Log::error('Lỗi khi insert đơn hàng!', $order_data);
             request()->session()->flash('error', 'Có lỗi xảy ra khi tạo đơn hàng.');
             return redirect()->back();
         }

         // ✅ Lấy doctor_id từ session (nếu có)
         $doctor_id = session('doctor_id');

         // ✅ Nếu có doctor_id, lưu nó vào đơn hàng và tính hoa hồng
         if ($doctor_id) {
             $order->doctor_id = $doctor_id;
             $order->commission = $order->sub_total * 0.10; // 10% hoa hồng cho bác sĩ
             $order->save();

             // ✅ Lưu thông tin vào affiliate_orders
             AffiliateOrder::create([
                 'order_id' => $order->id,
                 'doctor_id' => $doctor_id,
                 'commission' => $order->commission,
                 'status' => 'pending',  // hoặc trạng thái bạn muốn
                 'created_at' => now(),
                 'updated_at' => now(),
             ]);
         } else {
             $order->commission = 0;
             $order->save();
         }

         // ✅ Gửi thông báo khi tạo đơn hàng thành công
         Notification::send(User::where('role', 'admin')->first(), new StatusNotification([
             'title' => 'Đơn hàng mới',
             'actionURL' => route('order.show', $order->id),
             'fas' => 'fa-file-alt'
         ]));

         // ✅ Xóa session giỏ hàng
         session()->forget(['cart', 'coupon']);

         // ✅ Cập nhật giỏ hàng với order_id
         Cart::where('user_id', auth()->id())->whereNull('order_id')->update(['order_id' => $order->id]);

         // ✅ Thông báo thành công và chuyển hướng
         request()->session()->flash('success', 'Đơn hàng của bạn đã được tạo. Cảm ơn bạn đã mua sắm!');
         return redirect()->route('home');
     }



    /**
     * Hiển thị chi tiết đơn hàng (phía admin).
     */
    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            // Ở đây có thể xử lý lỗi hoặc redirect nếu không tìm thấy đơn hàng
        }
        return view('backend.order.show')->with('order', $order);
    }

    /**
     * Form chỉnh sửa đơn hàng (phía admin).
     */
    public function edit($id)
    {
        $order = Order::find($id);
        return view('backend.order.edit')->with('order',$order);
    }

    /**
     * Cập nhật đơn hàng (phía admin).
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $this->validate($request,[
            'status' => 'required|in:new,process,delivered,cancel'
        ]);
        $data = $request->all();

        // Nếu đơn hàng được chuyển sang trạng thái 'delivered'
        // => Trừ stock của các sản phẩm liên quan
        if($request->status=='delivered'){
            foreach($order->cart as $cart){
                $product = $cart->product;
                $product->stock -= $cart->quantity;
                $product->save();
            }
        }

        $status = $order->fill($data)->save();
        if($status){
            request()->session()->flash('success','Cập nhật đơn hàng thành công');
        }
        else{
            request()->session()->flash('error','Không thể cập nhật đơn hàng');
        }
        return redirect()->route('order.index');
    }

    /**
     * Xóa đơn hàng (phía admin).
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if($order){
            $status = $order->delete();
            if($status){
                request()->session()->flash('success','Đã xóa đơn hàng thành công');
            }
            else{
                request()->session()->flash('error','Không thể xóa đơn hàng');
            }
            return redirect()->route('order.index');
        }
        else{
            request()->session()->flash('error','Không tìm thấy đơn hàng');
            return redirect()->back();
        }
    }

    /**
     * Trang theo dõi đơn hàng (phía frontend).
     */
    public function orderTrack(){
        return view('frontend.pages.order-track');
    }

    /**
     * Kiểm tra tình trạng đơn hàng (phía frontend).
     */
    public function productTrackOrder(Request $request){
        $order = Order::where('user_id', auth()->user()->id)
                      ->where('order_number', $request->order_number)
                      ->first();

        if($order){
            if($order->status == "new"){
                request()->session()->flash('success','Đơn hàng của bạn đã được đặt.');
                return redirect()->route('home');
            }
            elseif($order->status == "process"){
                request()->session()->flash('success','Đơn hàng của bạn đang được xử lý.');
                return redirect()->route('home');
            }
            elseif($order->status == "delivered"){
                request()->session()->flash('success','Đơn hàng của bạn đã được giao. Cảm ơn bạn đã mua sắm!');
                return redirect()->route('home');
            }
            else{
                request()->session()->flash('error','Rất tiếc, đơn hàng của bạn đã bị hủy.');
                return redirect()->route('home');
            }
        }
        else{
            request()->session()->flash('error','Mã đơn hàng không hợp lệ. Vui lòng thử lại!');
            return back();
        }
    }

    /**
     * Xuất hóa đơn PDF (phía admin).
     */
    public function pdf(Request $request, $id)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Bật isRemoteEnabled
        // Lấy dữ liệu đơn hàng
        $order = Order::findOrFail($id);

        // Tạo tên file
        $file_name = $order->order_number . '-' . $order->first_name . '.pdf';

        // Tải view và xuất PDF
        $pdf = PDF::loadView('backend.order.pdf', compact('order'));

        // Xuất file PDF
        return $pdf->download($file_name);
    }

    /**
     * Lấy dữ liệu thống kê thu nhập theo tháng (phía admin).
     */
    public function incomeChart(Request $request){
        $year = \Carbon\Carbon::now()->year;
        $items = Order::with(['cart_info'])
                      ->whereYear('created_at', $year)
                      ->where('status','delivered')
                      ->get()
                      ->groupBy(function($d){
                          return \Carbon\Carbon::parse($d->created_at)->format('m');
                      });

        $result = [];
        foreach($items as $month => $item_collections){
            foreach($item_collections as $item){
                $amount = $item->cart_info->sum('amount');
                $m = intval($month);
                isset($result[$m]) ? $result[$m] += $amount : $result[$m] = $amount;
            }
        }

        $data = [];
        for($i = 1; $i <= 12; $i++){
            $monthName = date('F', mktime(0, 0, 0, $i, 1));
            $data[$monthName] = (!empty($result[$i]))
                ? number_format((float)($result[$i]), 2, '.', '')
                : 0.0;
        }

        return $data;
    }
}
