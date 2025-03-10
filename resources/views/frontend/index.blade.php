@extends('frontend.layouts.master')
@section('title','CODY || TRANG CHỦ')
@section('main-content')

<!-- Popup hiển thị khuyến mãi -->
<div id="popup-container">
    <div class="popup-content">
        <span class="close-btn">&times;</span>
        <img src="{{asset('frontend/img/promo-banner.png')}}" alt="Khuyến mãi">
    </div>
</div>

<!-- Banner trái cố định -->
<div class="side-banner left-banner">
    <img src="{{asset('frontend/img/tesstbaner.png')}}" alt="Banner Trái">
</div>
<!-- Slider Area -->
    @if(count($banners)>0)
        <section id="Gslider" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                @foreach($banners as $key=>$banner)
            <li data-target="#Gslider" data-slide-to="{{$key}}" class="{{(($key==0)? 'active' : '')}}"></li>
                @endforeach

            </ol>
            <div class="carousel-inner" role="listbox">
                    @foreach($banners as $key=>$banner)
                    <div class="carousel-item {{(($key==0)? 'active' : '')}}">
                        <img class="first-slide" src="{{$banner->photo}}" alt="First slide">
                        <div class="carousel-caption d-none d-md-block text-left">
                            <h1 class="wow fadeInDown">{{$banner->title}}</h1>
                            <p>{!! html_entity_decode($banner->description) !!}</p>
                            <a class="btn btn-lg ws-btn wow fadeInUpBig" href="{{route('product-grids')}}" role="button">Mua ngay<i class="far fa-arrow-alt-circle-right"></i></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#Gslider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Trước</span>
            </a>
            <a class="carousel-control-next" href="#Gslider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Sau</span>
            </a>
        </section>
    @endif
<!--/ End Slider Area -->

<!-- Banner phải cố định -->
<div class="side-banner right-banner">
    <img src="{{asset('frontend/img/tesstbaner2.png')}}" alt="Banner Phải">
</div>

<!-- Start Small Banner -->
<section class="small-banner section">
    <div class="container">
        <div class="row">
            @if($category_lists->count())
                @foreach($category_lists as $cat)
                    <!-- Single Banner  -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-banner">
                            @if($cat->photo)
                                <img src="{{ $cat->photo }}" alt="{{ $cat->title }}">
                            @else
                                <img src="https://via.placeholder.com/600x370" alt="No image">
                            @endif
                            <div class="content">
                                <h3>{{ $cat->title }}</h3>
                                <a href="{{ route('product-cat', $cat->slug) }}">Khám phá ngay</a>
                            </div>
                        </div>
                    </div>
                    <!-- /End Single Banner  -->
                @endforeach
            @else
                <p class="text-center w-100">Chưa có danh mục nào được cập nhật.</p>
            @endif
        </div>
    </div>
</section>
<!-- End Small Banner -->


<!-- Start Product Area -->
<div class="product-area section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Sản phẩm mới</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-info">
                    <div class="nav-main">
    <!-- Tab Nav -->
    <ul class="nav nav-tabs filter-tope-group" id="myTab" role="tablist">
        <button class="btn" style="background:black" data-filter="*">
            Mới thêm gần đây
        </button>
        @foreach($categories as $key => $cat)
            <button class="btn" style="background:none;color:black;" data-filter=".{{$cat->id}}">
                {{$cat->title}}
            </button>
        @endforeach
    </ul>
    <!--/ End Tab Nav -->
</div>


<div class="tab-content isotope-grid" id="myTabContent">
    @foreach($recentlyAddedProducts as $key => $product)
        <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{$product->cat_id}}">
            <div class="single-product">
                <div class="product-img">
                    <a href="{{ route('product-detail', $product->slug) }}">
                        @php
                            $photos = explode(',', $product->photo);
                        @endphp
                        <img class="default-img" src="{{ $photos[0] }}" alt="{{ $photos[0] }}">
                        <img class="hover-img" src="{{ $photos[0] }}" alt="{{ $photos[0] }}">
                        @if($product->stock <= 0)
                            <span class="out-of-stock">Bán hết</span>
                        @elseif($product->condition == 'new')
                            <span class="new">Mới</span>
                        @elseif($product->condition == 'hot')
                            <span class="hot">Nổi Bật</span>
                        @else
                            <span class="price-dec">{{ $product->discount }}% Giảm giá</span>
                        @endif
                    </a>
                    <div class="button-head">
                        <div class="product-action">
                            <a data-toggle="modal" data-target="#{{$product->id}}" title="Xem nhanh" href="#"><i class="ti-eye"></i><span>Mua sắm nhanh</span></a>
                            <a title="Danh sách mong muốn" href="{{ route('add-to-wishlist', $product->slug) }}"><i class="ti-heart"></i><span>Thêm vào danh sách mong muốn</span></a>
                            <a title="Mua ngay" href="{{ route('checkout-now', ['product_id' => $product->id]) }}"><i class="ti-shopping-cart"></i><span>Mua ngay</span></a>
                        </div>
                        <div class="product-action-2">
                            <a title="Thêm vào giỏ hàng" href="{{ route('add-to-cart', $product->slug) }}">Thêm vào giỏ hàng</a>
                        </div>
                    </div>
                </div>
                <div class="product-content">
                    <h3><a href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a></h3>
                    @php
                        $after_discount = $product->price - ($product->price * $product->discount / 100);
                    @endphp
                    <div class="product-price">
                        <span>{{ number_format($after_discount, 0, ',', '.') }}đ</span>
                        <del style="padding-left: 4%;">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

                    </div>
                </div>
            </div>
        </div>
</div>

<!-- End Product Area -->


<div class="product-area most-popular section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>Sản phẩm nổi bật</h2>
                </div>
            </div>
        </div>
        <div class="row">
    <div class="col-12">
        <div class="owl-carousel popular-slider">
            @foreach($popular_products as $product)
                <!-- Start Single Product -->
                <div class="single-product">
                    <div class="product-img">
                        <a href="{{ route('product-detail', $product->slug) }}">
                            @php
                                $photos = explode(',', $product->photo);
                            @endphp
                            <img class="default-img" src="{{ $photos[0] ?? 'https://via.placeholder.com/600x370' }}" alt="{{ $product->title }}">
                            <img class="hover-img" src="{{ $photos[0] ?? 'https://via.placeholder.com/600x370' }}" alt="{{ $product->title }}">
                        </a>
                        <div class="button-head">
                            <div class="product-action">
                                <a data-toggle="modal" data-target="#{{ $product->id }}" title="Xem nhanh" href="#"><i class="ti-eye"></i><span>Mua sắm nhanh</span></a>
                                <a title="Danh sách mong muốn" href="{{ route('add-to-wishlist', $product->slug) }}"><i class="ti-heart"></i><span>Thêm vào danh sách mong muốn</span></a>
                                <a title="Mua ngay" href="{{ route('checkout-now', ['product_id' => $product->id]) }}"><i class="ti-shopping-cart"></i><span>Mua ngay</span></a>
                            </div>
                            <div class="product-action-2">
                                <a title="Thêm vào giỏ hàng" href="{{ route('add-to-cart', $product->slug) }}">Thêm vào giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                    <div class="product-content">
                        <h3><a href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a></h3>
                        @php
                            $after_discount = $product->price - ($product->price * $product->discount / 100);
                        @endphp
                        <div class="product-price">
                            <span class="old">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                            <span>{{ number_format($after_discount, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
                <!-- End Single Product -->
            @endforeach
        </div>
    </div>
</div>

    </div>
</div>
<!-- End Most Popular Area -->

<!-- Modal cho Quick View -->
@foreach($popular_products as $product)
<div class="modal fade" id="quick-view-{{ $product->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $product->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Hình ảnh sản phẩm -->
                    <div class="col-lg-6">
                        <img src="{{ explode(',', $product->photo)[0] }}" alt="{{ $product->title }}" class="img-fluid">
                    </div>
                    <!-- Thông tin sản phẩm -->
                    <div class="col-lg-6">
    <div class="quickview-ratting-review mb-3">
        <div class="quickview-ratting-wrap">
            <div class="quickview-ratting">
                @php
                    $rate = round($product->reviews_avg_rate ?? 0, 1);
                    $rate_count = $product->reviews_count ?? 0;
                @endphp
                @for($i = 1; $i <= 5; $i++)
                    @if($rate >= $i)
                        <i class="yellow fa fa-star"></i>
                    @else
                        <i class="fa fa-star"></i>
                    @endif
                @endfor
            </div>
            <a href="#">({{ $rate_count }} đánh giá của khách hàng)</a>
        </div>
    </div>
    <p>{!! strip_tags($product->summary, '<p><br>') !!}</p>
</div>

                </div>
            </div>
        </div>
    </div>
</div>
@endforeach



<!-- Start Shop Home List -->
<section class="shop-home-list section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="shop-section-title">
                            <h1>Các sản phẩm mới nhất</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
    @foreach($recent_products as $product)
        <div class="col-md-4">
            <!-- Start Single List -->
            <div class="single-list">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="list-image overlay">
                            @php
                                $photo = explode(',', $product->photo);
                            @endphp
                            <img src="{{ $photo[0] ?? 'https://via.placeholder.com/600x370' }}" alt="{{ $product->title }}">
                            <a href="{{ route('add-to-cart', $product->slug) }}" class="buy">
                                <i class="fa fa-shopping-bag"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 no-padding">
                        <div class="content">
                            <h4 class="title">
                                <a href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a>
                            </h4>
                            <p class="price with-discount">
                                Giảm {{ $product->discount }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Single List -->
        </div>
    @endforeach
</div>

            </div>
        </div>
    </div>
</section>
<!-- End Shop Home List -->


<!-- Start Shop Services Area -->
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

<!-- End Shop Services Area -->

@include('frontend.layouts.newsletter')

<!-- Modal -->
@if($product_lists)
    @foreach($product_lists as $key=>$product)
        <div class="modal fade" id="{{$product->id}}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row no-gutters">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Product Slider -->
                                        <div class="product-gallery">
                                            <div class="quickview-slider-active">
                                                @php
                                                    $photo=explode(',',$product->photo);
                                                // dd($photo);
                                                @endphp
                                                @foreach($photo as $data)
                                                    <div class="single-slider">
                                                        <img src="{{$data}}" alt="{{$data}}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    <!-- End Product slider -->
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
    <div class="quickview-content">
        <h2>{{ $product->title }}</h2>
        <div class="quickview-ratting-review">
            <div class="quickview-ratting-wrap">
                <div class="quickview-ratting">
                    @php
                        $rate = round($product->reviews_avg_rate ?? 0, 1);
                        $rate_count = $product->reviews_count ?? 0;
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                        @if($rate >= $i)
                            <i class="yellow fa fa-star"></i>
                        @else
                            <i class="fa fa-star"></i>
                        @endif
                    @endfor
                </div>
                <a href="#">({{ $rate_count }} đánh giá của khách hàng)</a>
            </div>
            <div class="quickview-stock">
                @if($product->stock > 0)
                    <span><i class="fa fa-check-circle-o"></i> {{ $product->stock }} trong kho</span>
                @else
                    <span><i class="fa fa-times-circle-o text-danger"></i> Hết hàng</span>
                @endif
            </div>
        </div>

        @php
            $after_discount = $product->price - ($product->price * $product->discount / 100);
        @endphp
        <h3>
            <small><del class="text-muted">{{ number_format($product->price, 0, ',', '.') }}đ</del></small>
            {{ number_format($after_discount, 0, ',', '.') }}đ
        </h3>

        <div class="quickview-peragraph">
            <p>{!! html_entity_decode($product->summary) !!}</p>
        </div>

        @if($product->size)
            <div class="size">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <h3 class="title">Loại</h3>
                        <select>
                            @foreach(explode(',', $product->size) as $size)
                                <option>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('single-add-to-cart') }}" method="POST" class="mt-4">
            @csrf
            <div class="quantity">
                <div class="input-group">
                    <div class="button minus">
                        <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                            <i class="ti-minus"></i>
                        </button>
                    </div>
                    <input type="hidden" name="slug" value="{{ $product->slug }}">
                    <input type="text" name="quant[1]" class="input-number" data-min="1" data-max="1000" value="1">
                    <div class="button plus">
                        <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
                            <i class="ti-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="add-to-cart">
                <button type="submit" class="btn">Thêm vào giỏ hàng</button>
                <a href="{{ route('checkout-now', ['product_id' => $product->id]) }}" class="btn btn-primary">Mua ngay</a>
                <a href="{{ route('add-to-wishlist', $product->slug) }}" class="btn min">
                    <i class="ti-heart"></i>
                </a>
            </div>
        </form>

        <div class="default-social"></div>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
        </div>
    @endforeach
@endif
<!-- Modal end -->
@endsection

@push('styles')
    <style>
        /* ========== CHỈNH BANNER SLIDER ========== */
/* Định dạng vùng chứa banner */
#Gslider {
    padding: 10px 0; /* Giảm khoảng cách trên dưới */
    max-width: 1200px; /* Giới hạn chiều rộng */
    margin: 0 auto; /* Căn giữa */
}

/* Điều chỉnh kích thước banner */
#Gslider .carousel-inner {
    background: #ffffff; /* Chỉnh nền thành trắng */
    color: black;
    height: 350px; /* Giảm chiều cao */
    border-radius: 8px; /* Bo góc nhẹ */
}

/* Hình ảnh bên trong banner */
#Gslider .carousel-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Giữ tỷ lệ ảnh mà không méo */
    border-radius: 8px;
}

/* Chỉnh tiêu đề trên banner */
#Gslider .carousel-inner .carousel-caption h1 {
    font-size: 28px; /* Nhỏ hơn chút để gọn gàng */
    font-weight: bold;
    color: #1e1e1e;
}

/* Chỉnh mô tả banner */
#Gslider .carousel-inner .carousel-caption p {
    font-size: 16px;
    color: black;
    margin: 10px 0;
}

/* Chỉnh vị trí nút điều hướng prev/next */
#Gslider .carousel-control-prev,
#Gslider .carousel-control-next {
    width: 40px;
    height: 40px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.8;
}

/* Định vị lại nút prev/next */
#Gslider .carousel-control-prev {
    left: 5px;
}

#Gslider .carousel-control-next {
    right: 5px;
}

/* Hiệu ứng hover khi di chuột vào */
#Gslider .carousel-control-prev:hover,
#Gslider .carousel-control-next:hover {
    opacity: 1;
    background: rgba(0, 0, 0, 0.8);
}

/* Điều chỉnh icon trong nút */
#Gslider .carousel-control-prev-icon,
#Gslider .carousel-control-next-icon {
    width: 20px;
    height: 20px;
}

/* ========== CHỈNH DANH MỤC ========== */
/* Căn chỉnh danh mục nhỏ */
.small-banner {
    max-width: 1200px;
    margin: 0 auto;
}

/* Điều chỉnh từng banner danh mục */
.small-banner .single-banner {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    height: 180px; /* Giảm chiều cao */
    max-width: 350px; /* Giới hạn chiều rộng */
    margin: 0 auto;
}

/* Hình ảnh bên trong danh mục */
.small-banner .single-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

/* Chữ trên banner danh mục */
.small-banner .single-banner .content {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: rgba(0, 0, 0, 0.6);
    padding: 8px 12px;
    border-radius: 5px;
}

/* Tiêu đề danh mục */
.small-banner .single-banner .content h3 {
    font-size: 14px;
    color: white;
    font-weight: bold;
}

/* Link danh mục */
.small-banner .single-banner .content a {
    color: #fff;
    font-size: 12px;
    text-decoration: none;
    font-weight: bold;
}
/* Định dạng banner quảng cáo bên trái và phải */
.side-banner {
    position: fixed;
    top: 100px; /* Điều chỉnh vị trí theo chiều dọc */
    width: 160px; /* Điều chỉnh kích thước banner */
    height: 500px; /* Độ cao banner */
    overflow: hidden;
    z-index: 999; /* Luôn hiển thị trên cùng */
}

/* Banner bên trái */
.left-banner {
    left: 10px; /* Cách lề trái */
}

/* Banner bên phải */
.right-banner {
    right: 10px; /* Cách lề phải */
}

/* Định dạng ảnh trong banner */
.side-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

/* Để banner không che mất nội dung */
@media (max-width: 1400px) {
    .side-banner {
        display: none; /* Ẩn banner khi màn hình nhỏ */
    }
}

    </style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>

        /*==================================================================
        [ Isotope ]*/
        var $topeContainer = $('.isotope-grid');
        var $filter = $('.filter-tope-group');

        // filter items on button click
        $filter.each(function () {
            $filter.on('click', 'button', function () {
                var filterValue = $(this).attr('data-filter');
                $topeContainer.isotope({filter: filterValue});
            });

        });

        // init Isotope
        $(window).on('load', function () {
            var $grid = $topeContainer.each(function () {
                $(this).isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: 'fitRows',
                    percentPosition: true,
                    animationEngine : 'best-available',
                    masonry: {
                        columnWidth: '.isotope-item'
                    }
                });
            });
        });

        var isotopeButton = $('.filter-tope-group button');

        $(isotopeButton).each(function(){
            $(this).on('click', function(){
                for(var i=0; i<isotopeButton.length; i++) {
                    $(isotopeButton[i]).removeClass('how-active1');
                }

                $(this).addClass('how-active1');
            });
        });
    </script>
    <script>
         function cancelFullScreen(el) {
            var requestMethod = el.cancelFullScreen||el.webkitCancelFullScreen||el.mozCancelFullScreen||el.exitFullscreen;
            if (requestMethod) { // cancel full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function requestFullScreen(el) {
            // Supports most browsers and their versions.
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;

            if (requestMethod) { // Native full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            return false
        }
    </script>

    <script>
        $(document).ready(function(){
            // Xử lý sự kiện click cho nút "Mua ngay!"
            $('.cart').click(function(){
                var quantity = 1;
                var pro_id = $(this).data('id');
                var slug = $(this).data('slug');  // Lấy giá trị slug từ data attribute

                $.ajax({
                    url: "{{ url('add-to-cart') }}/" + slug,  // Gửi slug vào URL
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",  // Token CSRF
                        quantity: quantity,
                        pro_id: pro_id
                    },
                    success: function(response) {
                        console.log(response);
                        if (typeof(response) != 'object') {
                            response = $.parseJSON(response);
                        }
                        if (response.status) {
                            // Hiển thị thông báo thành công
                            swal('Success', response.msg, 'success').then(function() {
                                document.location.href = document.location.href; // Reload trang
                            });
                        } else {
                            // Hiển thị thông báo lỗi
                            swal('Error', response.msg, 'error').then(function() {
                                // Có thể thêm hành động nào đó nếu cần
                            });
                        }
                    }
                })
            });

            /*----------------------------------------------------*/
            /*  Jquery Ui slider js
            /*----------------------------------------------------*/
            if ($("#slider-range").length > 0) {
                const max_value = parseInt($("#slider-range").data('max')) || 500;
                const min_value = parseInt($("#slider-range").data('min')) || 0;
                const currency = $("#slider-range").data('currency') || '';
                let price_range = min_value + '-' + max_value;
                if ($("#price_range").length > 0 && $("#price_range").val()) {
                    price_range = $("#price_range").val().trim();
                }

                let price = price_range.split('-');
                $("#slider-range").slider({
                    range: true,
                    min: min_value,
                    max: max_value,
                    values: price,
                    slide: function(event, ui) {
                        $("#amount").val(currency + ui.values[0] + " - " + currency + ui.values[1]);
                        $("#price_range").val(ui.values[0] + "-" + ui.values[1]);
                    }
                });
            }

            if ($("#amount").length > 0) {
                const m_currency = $("#slider-range").data('currency') || '';
                $("#amount").val(m_currency + $("#slider-range").slider("values", 0) +
                    " - " + m_currency + $("#slider-range").slider("values", 1));
            }
        });
    </script>

    <script>
        // Reset trạng thái button-head khi modal được đóng
        $(document).on('hidden.bs.modal', function (e) {
            $('.button-head').css('display', 'none'); // Đảm bảo button-head bị ẩn
        });

        // Đảm bảo button-head chỉ hiển thị khi hover vào .product-img
        $(document).ready(function () {
            $('.product-img').hover(function () {
                $(this).find('.button-head').css('display', 'block');
            }, function () {
                $(this).find('.button-head').css('display', 'none');
            });
        });
    </script>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const popup = document.getElementById("popup-container");
    const closeBtn = document.querySelector(".close-btn");

    // Đóng popup khi nhấn nút
    closeBtn.addEventListener("click", function () {
        popup.style.display = "none";
    });

    // Đóng popup khi click ra ngoài
    popup.addEventListener("click", function (e) {
        if (e.target === popup) {
            popup.style.display = "none";
        }
    });
});

    </script>
@endpush
