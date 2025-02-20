@extends('frontend.layouts.master')
@section('title','CODY || TRANG CHỦ')
@section('main-content')
<!-- Slider Area         -->
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

<!-- Start Small Banner  -->
@if(isset($category_lists) && $category_lists->isNotEmpty())
<section class="small-banner section">
    <div class="container-fluid">
        <div class="row">
            @foreach($category_lists as $cat)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-banner">
                        <img src="{{ $cat->photo ?? 'https://via.placeholder.com/600x370' }}" alt="{{ $cat->title }}">
                        <div class="content">
                            <h3>{{ $cat->title }}</h3>
                            <a href="{{ route('product-cat', $cat->slug) }}">Khám phá ngay</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<!-- End Small Banner -->


<!-- Start Product Area -->
@if(isset($product_lists) && $product_lists->isNotEmpty())
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
                            @isset($parent_categories )
                                @foreach($parent_categories  as $cat)
                                    <button class="btn" style="background:none;color:black;" data-filter=".{{$cat->id}}">
                                        {{$cat->title}}
                                    </button>
                                @endforeach
                            @endisset
                        </ul>
                        <!--/ End Tab Nav -->
                    </div>

                    <div class="tab-content isotope-grid" id="myTabContent">
                        @foreach($product_lists as $product)
                            @php
                                $photos = $product->photo ? explode(',', $product->photo) : ['https://via.placeholder.com/600x370'];
                                $first_photo = $photos[0];
                                $after_discount = $product->price - ($product->price * $product->discount / 100);
                            @endphp
                            <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{$product->cat_id}}">
                                <div class="single-product">
                                    <div class="product-img">
                                        <a href="{{route('product-detail', $product->slug)}}">
                                            <img class="default-img" src="{{$first_photo}}" alt="{{$product->title}}">
                                            <img class="hover-img" src="{{$first_photo}}" alt="{{$product->title}}">
                                            @if($product->stock <= 0)
                                                <span class="out-of-stock">Bán hết</span>
                                            @elseif($product->condition == 'new')
                                                <span class="new">Mới</span>
                                            @elseif($product->condition == 'hot')
                                                <span class="hot">Nổi Bật</span>
                                            @else
                                                <span class="price-dec">Giảm giá {{$product->discount}}%</span>
                                            @endif
                                        </a>
                                        <div class="button-head">
                                            <div class="product-action">
                                                <a data-toggle="modal" data-target="#{{$product->id}}" title="Xem nhanh" href="#">
                                                    <i class="ti-eye"></i><span>Mua sắm nhanh</span>
                                                </a>
                                                <a title="Danh sách mong muốn" href="{{route('add-to-wishlist', $product->slug)}}">
                                                    <i class="ti-heart"></i><span>Thêm vào danh sách mong muốn</span>
                                                </a>
                                                <a title="Mua ngay" href="{{route('checkout-now', ['product_id' => $product->id])}}">
                                                    <i class="ti-shopping-cart"></i><span>Mua ngay</span>
                                                </a>
                                            </div>
                                            <div class="product-action-2">
                                                <a title="Thêm vào giỏ hàng" href="{{route('add-to-cart', $product->slug)}}">Thêm vào giỏ hàng</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3><a href="{{route('product-detail', $product->slug)}}">{{$product->title}}</a></h3>
                                        <div class="product-price">
                                            <span>{{number_format($after_discount, 0, ',', '.')}}đ</span>
                                            <del style="padding-left: 4%;">{{number_format($product->price, 0, ',', '.')}}đ</del>
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
@endif
<!-- End Product Area -->


<!-- Start Most Popular Product Area -->
@if(isset($popular_products) && $popular_products->isNotEmpty())
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
                        @php
                            $photo = $product->photo ? explode(',', $product->photo) : ['https://via.placeholder.com/600x370'];
                            $first_photo = $photo[0];
                            $after_discount = $product->price - ($product->price * $product->discount / 100);
                        @endphp
                        <div class="single-product">
                            <div class="product-img">
                                <a href="{{ route('product-detail', $product->slug) }}">
                                    <img class="default-img" src="{{ $first_photo }}" alt="{{ $product->title }}">
                                    <img class="hover-img" src="{{ $first_photo }}" alt="{{ $product->title }}">
                                </a>
                                <div class="button-head">
                                    <div class="product-action">
                                        <a data-toggle="modal" data-target="#{{$product->id}}" title="Xem nhanh" href="#">
                                            <i class="ti-eye"></i><span>Mua sắm nhanh</span>
                                        </a>
                                        <a title="Danh sách mong muốn" href="{{ route('add-to-wishlist', $product->slug) }}">
                                            <i class="ti-heart"></i><span>Thêm vào danh sách mong muốn</span>
                                        </a>
                                        <a title="Mua ngay" href="{{ route('checkout-now', ['product_id' => $product->id]) }}">
                                            <i class="ti-shopping-cart"></i><span>Mua ngay</span>
                                        </a>
                                    </div>
                                    <div class="product-action-2">
                                        <a title="Thêm vào giỏ hàng" href="{{ route('add-to-cart', $product->slug) }}">Thêm vào giỏ hàng</a>
                                    </div>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3><a href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a></h3>
                                <div class="product-price">
                                    <span class="old">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                    <span>{{ number_format($after_discount, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- End Most Popular Product Area -->

<!-- Modal cho Quick View -->
@foreach($popular_products as $product)
@php
    $photos = $product->photo ? explode(',', $product->photo) : ['https://via.placeholder.com/600x370'];
    $first_photo = $photos[0];
@endphp
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
                        <img src="{{ $first_photo }}" alt="{{ $product->title }}" class="img-fluid">
                    </div>
                    <!-- Thông tin sản phẩm -->
                    <div class="col-lg-6">
                        <div class="quickview-ratting-review mb-3">
                            <div class="quickview-ratting-wrap">
                                <div class="quickview-ratting">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $product->rate >= $i ? 'yellow fa fa-star' : 'fa fa-star' }}"></i>
                                    @endfor
                                </div>
                                <a href="#">({{ $product->rate_count }} đánh giá của khách hàng)</a>
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
@if($product_lists->isNotEmpty())

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
                    @foreach($product_lists as $product)
                        @php
                            $photos = $product->photo ? explode(',', $product->photo) : ['https://via.placeholder.com/600x370'];
                            $first_photo = $photos[0];
                        @endphp
                        <div class="col-md-4">
                            <!-- Start Single List -->
                            <div class="single-list">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="list-image overlay">
                                            <img src="{{ $first_photo }}" alt="{{ $product->title }}">
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
                                            <p class="price with-discount">Giảm {{ $product->discount }}%</p>
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
@endif

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
@if(isset($product_lists) && $product_lists->isNotEmpty())
    @foreach($product_lists as $product)
        @php
            $photos = $product->photo ? explode(',', $product->photo) : ['https://via.placeholder.com/600x370'];
            $after_discount = $product->price - ($product->price * $product->discount / 100);
            $sizes = $product->size ? explode(',', $product->size) : [];
        @endphp
        <div class="modal fade" id="{{$product->id}}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="ti-close" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row no-gutters">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <!-- Product Slider -->
                                <div class="product-gallery">
                                    <div class="quickview-slider-active">
                                        @foreach($photos as $photo)
                                            <div class="single-slider">
                                                <img src="{{ $photo }}" alt="{{ $product->title }}">
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
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="{{ $product->rate >= $i ? 'yellow fa fa-star' : 'fa fa-star' }}"></i>
                                                @endfor
                                            </div>
                                            <a href="#">({{ $product->rate_count }} đánh giá của khách hàng)</a>
                                        </div>
                                        <div class="quickview-stock">
                                            <span class="{{ $product->stock > 0 ? '' : 'text-danger' }}">
                                                <i class="fa {{ $product->stock > 0 ? 'fa-check-circle-o' : 'fa-times-circle-o' }}"></i>
                                                {{ $product->stock > 0 ? $product->stock . ' trong kho' : 'Hết hàng' }}
                                            </span>
                                        </div>
                                    </div>
                                    <h3>
                                        <small><del class="text-muted">{{ number_format($product->price, 0, ',', '.') }}đ</del></small>
                                        {{ number_format($after_discount, 0, ',', '.') }}đ
                                    </h3>
                                    <div class="quickview-peragraph">
                                        <p>{!! strip_tags($product->summary, '<p><br>') !!}</p>
                                    </div>
                                    @if(!empty($sizes))
                                        <div class="size">
                                            <h3 class="title">Loại</h3>
                                            <select>
                                                @foreach($sizes as $size)
                                                    <option>{{ $size }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <form action="{{ route('single-add-to-cart') }}" method="POST" class="mt-4">
                                        @csrf
                                        <div class="quantity">
                                            <!-- Input Order -->
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
                                            <!--/ End Input Order -->
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


@if(isset($settings))
    <title>{{ $settings->site_title }}</title>
    <meta name="description" content="{{ $settings->meta_description }}">
@endif


@endsection

@push('styles')
    <style>
        /* Banner Sliding */
        #Gslider .carousel-inner {
        background: #000000;
        color:black;
        }

        #Gslider .carousel-inner{
        height: 550px;
        }
        #Gslider .carousel-inner img{
            width: 100% !important;
            opacity: .8;
        }

        #Gslider .carousel-inner .carousel-caption {
        bottom: 60%;
        }

        #Gslider .carousel-inner .carousel-caption h1 {
        font-size: 50px;
        font-weight: bold;
        line-height: 100%;
        /* color: #F7941D; */
        color: #1e1e1e;
        }

        #Gslider .carousel-inner .carousel-caption p {
        font-size: 18px;
        color: black;
        margin: 28px 0 28px 0;
        }

        #Gslider .carousel-indicators {
        bottom: 70px;
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

@endpush
