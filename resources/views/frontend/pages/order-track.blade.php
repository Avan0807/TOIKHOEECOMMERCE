@extends('frontend.layouts.master')

@section('title','CODY || Theo Dõi Đơn Hàng')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Trang chủ<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Theo Dõi Đơn Hàng</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc Breadcrumbs -->

<section class="tracking_box_area section_gap py-5">
    <div class="container">
        <div class="tracking_box_inner">
            <p>Để theo dõi đơn hàng của bạn, vui lòng nhập Mã Đơn Hàng vào ô bên dưới và nhấn nút "Theo Dõi". Mã này được cung cấp trên biên nhận và email xác nhận bạn đã nhận được, hoặc bạn có thể lấy mã đơn hàng từ bảng điều khiển người dùng.</p>
            <form class="row tracking_form my-4" action="{{route('product.track.order')}}" method="post" novalidate="novalidate">
              @csrf
                <div class="col-md-8 form-group">
                    <input type="text" class="form-control p-2"  name="order_number" placeholder="Nhập mã đơn hàng của bạn">
                </div>
                <div class="col-md-8 form-group">
                    <button type="submit" value="submit" class="btn submit_btn">Theo Dõi Đơn Hàng</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
