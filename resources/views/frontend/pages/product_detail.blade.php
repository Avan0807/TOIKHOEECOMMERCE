@extends('frontend.layouts.master')
@section('meta')
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name='copyright' content=''>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="cửa hàng trực tuyến, mua sắm, giỏ hàng, trang thương mại điện tử, mua sắm online tốt nhất">
	<meta name="description" content="{{$product_detail->summary}}">
	<meta property="og:url" content="{{route('product-detail',$product_detail->slug)}}">
	<meta property="og:type" content="bài viết">
	<meta property="og:title" content="{{$product_detail->title}}">
	<meta property="og:image" content="{{$product_detail->photo}}">
	<meta property="og:description" content="{{$product_detail->description}}">
@endsection
@section('title','Ecommerce Laravel || CHI TIẾT SẢN PHẨM')
@section('main-content')

		<!-- Breadcrumbs -->
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="bread-inner">
							<ul class="bread-list">
								<li><a href="{{route('home')}}">Trang Chủ<i class="ti-arrow-right"></i></a></li>
								<li class="active"><a href="">Chi Tiết Sản Phẩm</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->

		<!-- Shop Single -->
		<section class="shop single section">
					<div class="container">
						<div class="row">
							<div class="col-12">
								<div class="row">
									<div class="col-lg-6 col-12">
										<!-- Product Slider -->
										<div class="product-gallery">
											<!-- Images slider -->
											<div class="flexslider-thumbnails">
												<ul class="slides">
												@php
													$photos = !empty($product_detail->photo) ? explode(',', $product_detail->photo) : [];
												@endphp
												@if(!empty($photos))
													@foreach($photos as $photo)
														<li data-thumb="{{ $photo }}" rel="adjustX:10, adjustY:">
															<img src="{{ $photo }}" alt="Hình ảnh sản phẩm">
														</li>
													@endforeach
												@endif
												</ul>
											</div>
											<!-- End Images slider -->
										</div>
										<!-- End Product slider -->
									</div>
									<div class="col-lg-6 col-12">
										<div class="product-des">
											<!-- Description -->
											<div class="short">
												<h4>{{$product_detail->title}}</h4>
												<div class="rating-main">
    <ul class="rating">
        @php
            $rate = $product_detail->reviews->count() > 0 ? ceil($product_detail->reviews->avg('rate')) : 0;
        @endphp
        @for($i=1; $i<=5; $i++)
            @if($rate >= $i)
                <li><i class="fa fa-star"></i></li>
            @else
                <li><i class="fa fa-star-o"></i></li>
            @endif
        @endfor
    </ul>
    <a href="#" class="total-review">({{ $product_detail->reviews->count() }}) Đánh giá</a>
</div>

                                                @php
                                                    $after_discount=($product_detail->price-(($product_detail->price*$product_detail->discount)/100));
                                                @endphp
												<p class="price"><span class="discount">{{number_format($after_discount,0,',','.')}}đ</span><s>{{number_format($product_detail->price,0,',','.')}}đ</s> </p>
												<p class="description">{!!($product_detail->summary)!!}</p>
											</div>
											<!--/ End Description -->
											<!-- Size -->
											@if($product_detail->size)
												<div class="size mt-4">
													<h4>Loại</h4>
													<ul>
														@php
															$sizes=explode(',',$product_detail->size);
														@endphp
														@foreach($sizes as $size)
														<li><a href="#" class="one">{{$size}}</a></li>
														@endforeach
													</ul>
												</div>
											@endif
											<!--/ End Size -->
											<!-- Product Buy -->
											<div class="product-buy">
												<form action="{{route('single-add-to-cart')}}" method="POST">
													@csrf
													<div class="quantity">
    <h6>Số lượng :</h6>
    <div class="input-group">
        <div class="button minus">
            <button type="button" class="btn btn-primary btn-number" data-type="minus">
                <i class="ti-minus"></i>
            </button>
        </div>

        <input type="hidden" name="slug" value="{{ $product_detail->slug }}">
        <input type="text" name="quantity" class="input-number text-center" data-min="1" data-max="1000" value="1" id="quantity">

        <div class="button plus">
            <button type="button" class="btn btn-primary btn-number" data-type="plus">
                <i class="ti-plus"></i>
            </button>
        </div>
    </div>
</div>

													<div class="add-to-cart mt-4">
														<button type="submit" class="btn">Thêm vào giỏ hàng</button>
														<a href="{{ route('checkout-now', ['product_id' => $product_detail->id]) }}" class="btn btn-primary">Mua Ngay</a>
														<a href="{{route('add-to-wishlist', $product_detail->slug)}}" class="btn min"><i class="ti-heart"></i></a>
													</div>
												</form>

												<p class="cat">Danh mục: <a href="{{route('product-cat',$product_detail->cat_info['slug'])}}">{{$product_detail->cat_info['title']}}</a></p>
													@if($product_detail->sub_cat_info)
														<p class="cat mt-1">Danh mục con: <a href="{{route('product-sub-cat',[$product_detail->cat_info['slug'],$product_detail->sub_cat_info['slug']])}}">{{$product_detail->sub_cat_info['title']}}</a></p>
													@endif
													<p class="availability">Tình trạng:
														@if($product_detail->stock > 0)
															@if($product_detail->stock < 5)
																<span class="badge badge-warning">Hàng gần hết</span>
															@else
																<span class="badge badge-success">Còn hàng</span>
															@endif
														@else
															<span class="badge badge-danger">Hết hàng</span>
														@endif
													</p>
												</p>
											</div>
											<!--/ End Product Buy -->

										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-12">
										<div class="product-info">
											<div class="nav-main">
												<!-- Tab Nav -->
												<ul class="nav nav-tabs" id="myTab" role="tablist">
													<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#description" role="tab">Mô tả</a></li>
													<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Đánh giá</a></li>
												</ul>
												<!--/ End Tab Nav -->
											</div>
											<div class="tab-content" id="myTabContent">
												<!-- Description Tab -->
												<div class="tab-pane fade show active" id="description" role="tabpanel">
													<div class="tab-single">
														<div class="row">
															<div class="col-12">
																<div class="single-des">
																	<p>{!! ($product_detail->description) !!}</p>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!--/ End Description Tab -->
												<!-- Reviews Tab -->
												<div class="tab-pane fade" id="reviews" role="tabpanel">
													<div class="tab-single review-panel">
														<div class="row">
															<div class="col-12">
																<!-- Review -->
																<div class="comment-review">
																	<div class="add-review">
																		<h5>Thêm đánh giá</h5>
																			<p>Địa chỉ email của bạn sẽ không được công khai. Các trường bắt buộc được đánh dấu</p>
																		<h4>Đánh giá của bạn <span class="text-danger">*</span></h4>
																	<div class="review-inner">
																			<!-- Form -->
																@auth
																<form class="form" method="post" action="{{route('review.store',$product_detail->slug)}}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-12">
                                                                            <div class="rating_box">
                                                                                  <div class="star-rating">
                                                                                    <div class="star-rating__wrap">
                                                                                      <input class="star-rating__input" id="star-rating-5" type="radio" name="rate" value="5">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-5" title="5 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-4" type="radio" name="rate" value="4">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-4" title="4 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-3" type="radio" name="rate" value="3">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-3" title="3 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-2" type="radio" name="rate" value="2">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-2" title="2 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-1" type="radio" name="rate" value="1">
																					  <label class="star-rating__ico fa fa-star-o" for="star-rating-1" title="1 out of 5 stars"></label>
																					  @error('rate')
																						<span class="text-danger">{{$message}}</span>
																					  @enderror
                                                                                    </div>
                                                                                  </div>
                                                                            </div>
                                                                        </div>
																		<div class="col-lg-12 col-12">
																			<div class="form-group">
																				<label>Viết đánh giá</label>
																				<textarea name="review" rows="6" placeholder="" ></textarea>
																			</div>
																		</div>
																		<div class="col-lg-12 col-12">
																			<div class="form-group button5">
																				<button type="submit" class="btn">Đăng</button>
																			</div>
																		</div>
																	</div>
																</form>
																@else
																<p class="text-center p-5">
																	Bạn cần phải <a href="{{route('login.form')}}" style="color:rgb(54, 54, 204)">Đăng nhập</a> Hoặc <a style="color:blue" href="{{route('register.form')}}">Đăng ký</a>

																</p>
																<!--/ End Form -->
																@endauth
																	</div>
																</div>

																<div class="ratting-main">
																<div class="avg-ratting">
																	<h4>{{ ceil($product_detail->reviews->avg('rate')) }} <span>(Trung bình)</span></h4>
																	<span>Dựa trên {{ $product_detail->reviews->count() }} bình luận</span>
																</div>
																@foreach($product_detail->reviews as $data)
<!-- Single Rating -->
<div class="single-rating">
    <div class="rating-author">
        @if($data->user_info && $data->user_info->photo)
            <img src="{{ $data->user_info->photo }}" alt="Ảnh người dùng">
        @else
            <img src="{{ asset('backend/img/avatar.png') }}" alt="Avatar mặc định">
        @endif
    </div>
    <div class="rating-des">
        <h6>{{ $data->user_info->name ?? 'Ẩn danh' }}</h6>
        <ul class="rating">
            @for($i=1; $i<=5; $i++)
                @if($data->rate >= $i)
                    <li><i class="fa fa-star"></i></li>
                @else
                    <li><i class="fa fa-star-o"></i></li>
                @endif
            @endfor
        <div class="rate-count">(<span>{{ $data->rate }}</span>)</div>
        <p>{{ $data->review }}</p>
    </div>
</div>
@endforeach
																</div>
																<!--/ End Review -->
															</div>
														</div>
													</div>
												</div>
												<!--/ End Reviews Tab -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
		</section>
		<!--/ End Shop Single -->

		<!-- Start Most Popular -->
	<div class="product-area most-popular related-product section">
        <div class="container">
            <div class="row">
				<div class="col-12">
					<div class="section-title">
						<h2>Sản phẩm liên quan</h2>
					</div>
				</div>
            </div>
            <div class="row">
                {{-- {{$product_detail->rel_prods}} --}}
                <div class="col-12">
                    <div class="owl-carousel popular-slider">
                        @foreach($product_detail->rel_prods as $data)
                            @if($data->id !==$product_detail->id)
                                <!-- Start Single Product -->
                                <div class="single-product">
                                    <div class="product-img">
										<a href="{{route('product-detail',$data->slug)}}">
											@php
												$photo=explode(',',$data->photo);
											@endphp
                                            <img class="default-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                            <img class="hover-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                            <span class="price-dec">Giảm {{$data->discount}}%</span>
                                                                    {{-- <span class="out-of-stock">Hot</span> --}}
                                        </a>
                                        <div class="button-head">
                                            <div class="product-action">
												<a data-toggle="modal" data-target="#quickViewModal" title="Xem nhanh" href="#"><i class="ti-eye"></i><span>Mua sắm nhanh</span></a>
                                                <a title="Danh sách mong muốn" href="{{route('add-to-wishlist', $product_detail->slug)}}"><i class="ti-heart"></i><span>Thêm vào danh sách mong muốn</span></a>
                                                <a title="Mua ngay" href="{{route('checkout-now', ['product_id' => $product_detail->id])}}"><i class="ti-shopping-cart"></i><span>Mua ngay</span></a>
                                            </div>
                                            <div class="product-action-2">
                                                <a title="Add to cart" href="#">Thêm vào giỏ hàng</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3><a href="{{route('product-detail',$data->slug)}}">{{$data->title}}</a></h3>
                                        <div class="product-price">
                                            @php
                                                $after_discount=($data->price-(($data->discount*$data->price)/100));
                                            @endphp
                                            <span class="old">{{number_format($data->price,0,',','.')}}đ</span>
                                            <span>{{number_format($after_discount,0,',','.')}}đ</span>
                                        </div>

                                    </div>
                                </div>
                                <!-- End Single Product -->
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
	<!-- End Most Popular Area -->

	<!-- Modal -->
	<div class="modal fade" id="quickViewModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Thông tin sản phẩm</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
						<span>&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<!-- Hình ảnh sản phẩm -->
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="product-gallery">
								<div class="quickview-slider-active">
									@php
										$photos = explode(',', $product_detail->photo);
									@endphp
									@foreach($photos as $photo)
										<div class="single-slider">
											<img src="{{$data}}" alt="{{$data}}">
										</div>
									@endforeach
								</div>
							</div>
						</div>

						<!-- Thông tin sản phẩm -->
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="quickview-content">
								<h2>{{ $product_detail->title }}</h2>
								<div class="quickview-ratting-review">
    <div class="quickview-ratting">
        @php
            $rate = $product_detail->reviews->count() > 0 ? ceil($product_detail->reviews->avg('rate')) : 0;
        @endphp
        @for($i = 1; $i <= 5; $i++)
            @if($rate >= $i)
                <i class="yellow fa fa-star"></i>
            @else
                <i class="fa fa-star"></i>
            @endif
        @endfor
    </div>
    <span>({{ $product_detail->reviews->count() }} đánh giá)</span>
</div>


								@php
									$after_discount = $product_detail->price - (($product_detail->price * $product_detail->discount) / 100);
								@endphp
								<h3>
									@if($product_detail->discount > 0)
										<small><del class="text-muted">{{ number_format($product_detail->price, 0, ',', '.') }} đ</del></small>
									@endif
									{{ number_format($after_discount, 0, ',', '.') }} đ
								</h3>

								<p>{!! html_entity_decode($product_detail->summary) !!}</p>

								<form action="{{ route('single-add-to-cart') }}" method="POST">
									@csrf
									<div class="quantity">
										<div class="input-group">
											<div class="button minus">
												<button type="button" class="btn btn-primary btn-number" data-type="minus" data-field="quantity">
													<i class="ti-minus"></i>
												</button>
											</div>
											<input type="text" name="quantity" class="input-number" data-min="1" data-max="1000" value="1">
											<div class="button plus">
												<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quantity">
													<i class="ti-plus"></i>
												</button>
											</div>
										</div>
									</div>

									<div class="add-to-cart mt-3">
										<button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
										<a href="{{ route('checkout-now', ['product_id' => $product_detail->id]) }}" class="btn btn-success">Mua Ngay</a>
										<a href="{{route('add-to-wishlist', $product_detail->slug)}}" class="btn btn-outline-dark">
											<i class="ti-heart"></i>
										</a>
									</div>
								</form>
							</div>
						</div>
					</div> <!-- End row -->
				</div> <!-- End modal-body -->
			</div>
		</div>
	</div>
	<!-- Kết thúc Modal -->



@endsection

@push('styles')
	<style>
		.rating_box {
			display: inline-flex;
		}

		.star-rating {
			font-size: 0;
			padding-left: 10px;
			padding-right: 10px;
		}

		.star-rating__wrap {
			display: inline-block;
			font-size: 1rem;
		}

		.star-rating__ico {
			float: right;
			padding-left: 2px;
			cursor: pointer;
			color: #F7941D;
			font-size: 16px;
			margin-top: 5px;
		}

		.star-rating__input {
			display: none;
		}

		.star-rating__ico:hover:before,
		.star-rating__ico:hover ~ .star-rating__ico:before,
		.star-rating__input:checked ~ .star-rating__ico:before {
			content: "\F005";
		}
		.product-gallery img {
			max-width: 100%;
			height: auto;
			object-fit: contain;
			display: block;
			margin: 0 auto;
		}
		.quickview-slider-active .single-slider {
			display: flex;
			justify-content: center;
			align-items: center;
			background: #f9f9f9;
		}

		.input-group {
			display: flex;
			align-items: center;
		}

		.size ul {
    padding: 0;
    margin: 0;
    list-style: none;
    display: block; /* mỗi mục xuống 1 dòng mới */
}

.size ul li {
    margin-bottom: 8px;
}

.size ul li a {
    display: inline-block;
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    color: #6c757d;
    text-decoration: none;
    white-space: nowrap; /* Hiển thị trên cùng 1 dòng */
    max-width: 100%; /* không vượt quá giới hạn */
    overflow: hidden;
    text-overflow: ellipsis;
}

.size ul li a:hover,
.size ul li a.active {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}
	</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
	$(document).on('click', '.btn-number', function(e) {
    e.preventDefault();

    let type = $(this).data('type');
    let input = $('#quantity');
    let currentVal = parseInt(input.val());
    let minValue = parseInt(input.data('min'));
    let maxValue = parseInt(input.data('max'));

    if (!isNaN(currentVal)) {
        if (type === 'minus') {
            if (currentVal > minValue) {
                input.val(currentVal - 1);
            }
        } else if (type === 'plus') {
            if (currentVal < maxValue) {
                input.val(currentVal + 1);
            }
        }
    } else {
        input.val(minValue);
    }
});


	$(document).ready(function() {
		$('.btn-quick-view').on('click', function(e) {
			e.preventDefault();
			$('#quickViewModal').modal('show');
		});
	});

</script>

@endpush
