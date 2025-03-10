@extends('frontend.layouts.master')
@section('title','TRANG GIỎ HÀNG')
@section('main-content')
	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="{{('home')}}">Trang chủ<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="">Giỏ hàng</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->

	<!-- Shopping Cart -->
	<div class="shopping-cart section">
		<div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Tóm Tắt Giỏ Hàng -->
                    <table class="table shopping-summery">
                        <thead>
                            <tr class="main-hading">
                                <th class="text-center">SẢN PHẨM</th>
                                <th class="text-center">TÊN</th>
                                <th class="text-center">GIÁ ĐƠN VỊ</th>
                                <th class="text-center">SỐ LƯỢNG</th>
                                <th class="text-center">TỔNG</th>
                                <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody id="cart_item_list">
                            <form action="{{route('cart.update')}}" method="POST">
                                @csrf
                                @if(Helper::getAllProductFromCart())
                                    @foreach(Helper::getAllProductFromCart() as $key=>$cart)
                                        <tr>
                                            @php
                                            $photo=explode(',',$cart->product['photo']);
                                            @endphp
                                            <td class="image" data-title="Số"><img src="{{$photo[0]}}" alt="{{$photo[0]}}"></td>
                                            <td class="product-des" data-title="Mô tả">
                                                <p class="product-name"><a href="{{route('product-detail',$cart->product['slug'])}}" target="_blank">{{$cart->product['title']}}</a></p>
                                                <p class="product-des">{!!($cart['summary']) !!}</p>
                                            </td>
                                            <td class="total-amount cart_single_price" data-title="Tổng"><span class="money">{{number_format($cart['price'],0,',','.')}}đ</span></td>

                                            <td class="qty" data-title="Số lượng"><!-- Input Đặt Hàng -->
                                                <div class="input-group">
                                                    <div class="button minus">
                                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[{{$key}}]">
                                                            <i class="ti-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="quant[{{$key}}]" class="input-number" data-min="1" data-max="100" value="{{$cart->quantity}}">
                                                    <input type="hidden" name="qty_id[]" value="{{$cart->id}}">
                                                    <div class="button plus">
                                                        <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[{{$key}}]">
                                                            <i class="ti-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!--/ Kết Thúc Input Đặt Hàng -->
                                            </td>
                                            <td class="price" data-title="Giá"><span>{{number_format($cart['amount'],0,',','.')}}đ</span></td>
                                            <td class="action" data-title="Xóa"><a href="{{route('cart-delete',$cart->id)}}"><i class="ti-trash remove-icon"></i></a></td>
                                        </tr>
                                    @endforeach
                                    <track>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="float-right">
                                            <button class="btn float-right" type="submit">Cập nhật</button>
                                        </td>
                                    </track>
                                @else
                                    <tr>
                                        <td class="text-center">
                                            Hiện không có sản phẩm nào trong giỏ hàng. <a href="{{route('product-grids')}}" style="color:blue;">Tiếp tục mua sắm</a>
                                        </td>
                                    </tr>
                                @endif
                            </form>
                        </tbody>
                    </table>
                    <!--/ Kết Thúc Tóm Tắt Giỏ Hàng -->
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <!-- Tổng số tiền -->
                    <div class="total-amount">
                        <div class="row">
                            <div class="col-lg-8 col-md-5 col-12">
                                <div class="left">
                                    <div class="coupon">
                                        <form action="{{route('coupon-store')}}" method="POST">
                                            @csrf
                                            <input name="code" placeholder="Nhập mã giảm giá hợp lệ">
                                            <button class="btn">Áp dụng mã</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7 col-12">
                                <div class="right">
                                    <ul>
                                        @php
                                            $total_amount = Helper::totalCartPrice();
                                            $discount = 0;

                                            // Kiểm tra mã giảm giá từ session
                                            if (session()->has('coupon')) {
                                                $discount = session('coupon')['value'];
                                                
                                                // Nếu giá trị giảm >= tổng giá trị giỏ hàng
                                                if ($discount >= $total_amount) {
                                                    if ($total_amount > 1000) {
                                                        // Chỉ giảm để còn lại 1.000đ
                                                        $discount = $total_amount - 1000;
                                                    } else {
                                                        // Không áp dụng mã giảm giá
                                                        $discount = 0;
                                                        session()->forget('coupon');
                                                    }
                                                }

                                                $total_amount -= $discount;
                                            }
                                        @endphp

                                        <!-- Hiển thị tổng tiền giỏ hàng -->
                                        <li class="order_subtotal" data-price="{{ Helper::totalCartPrice() }}">
                                            Tổng tiền giỏ hàng<span>{{ number_format(Helper::totalCartPrice(), 0, ',', '.') }}đ</span>
                                        </li>

                                        <!-- Hiển thị giá trị giảm giá (nếu có) -->
                                        @if($discount > 0)
                                            <li class="coupon_price" data-price="{{ $discount }}">
                                                Bạn tiết kiệm<span>{{ number_format($discount, 0, ',', '.') }}đ</span>
                                            </li>
                                        @endif

                                        <!-- Hiển thị tổng tiền sau khi áp dụng giảm giá -->
                                        <li class="last" id="order_total_price">
                                            Số tiền bạn trả<span>{{ number_format($total_amount, 0, ',', '.') }}đ</span>
                                        </li>
                                    </ul>

                                    <div class="button5">
                                        <a href="{{ route('checkout') }}" class="btn">Thanh toán</a>
                                        <a href="{{ route('product-grids') }}" class="btn">Tiếp tục mua sắm</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--/ Kết thúc Tổng số tiền -->
                </div>
            </div>
                                            
		</div>
	</div>
	<!--/ End Shopping Cart -->

    <section class="shop-services section home">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-package"></i>
                        <h4>SP CHÍNH HÃNG</h4>
                        <p>Đa dạng và chuyên sâu</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-reload"></i>
                        <h4>ĐỔI TRẢ TRONG 30 NGÀY</h4>
                        <p>Kể từ ngày mua hàng</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-shield"></i>
                        <h4>CAM KẾT 100%</h4>
                        <p>Chất lượng sản phẩm</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-truck"></i>
                        <h4>MIỄN PHÍ VẬN CHUYỂN</h4>
                        <p>Theo chính sách giao hàng</p>
                    </div>
                    <!-- End Single Service -->
                </div>
            </div>
        </div>
    </section>
    <!-- End Shop Services -->

	<!-- Start Shop Newsletter  -->
	@include('frontend.layouts.newsletter')
	<!-- End Shop Newsletter -->

@endsection
@push('styles')
	<style>
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')
	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>
	<script>
		$(document).ready(function(){
			$('.shipping select[name=shipping]').change(function(){
				let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') );
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
				// alert(coupon);
				$('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
			});

		});

	</script>
@endpush