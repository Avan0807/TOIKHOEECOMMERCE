@extends('frontend.layouts.master')

@section('title','CODY || Giới thiệu về chúng tôi')

@section('main-content')

	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="index1.html">Trang chủ<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="blog-single.html">Giới thiệu về chúng tôi</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->

	<!-- About Us -->
	<section class="about-us section">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-12">
						<div class="about-content">
							@php
								$settings=DB::table('settings')->get();
							@endphp
							<h3>Chào mừng đến với <span>CODY</span></h3>
							<p>@foreach($settings as $data) {{$data->description}} @endforeach</p>
							<div class="button">
								<a href="{{route('blog')}}" class="btn">Bài viết của chúng tôi</a>
								<a href="{{route('contact')}}" class="btn primary">Liên hệ với chúng tôi</a>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-12">
						<div class="about-img overlay">
							<div class="button">
								<a href="https://www.youtube.com/watch?v=7edcgCdiHVU" class="video video-popup mfp-iframe"><i class="fa fa-play"></i></a>
							</div>
							<img src="@foreach($settings as $data) {{$data->photo}} @endforeach" alt="@foreach($settings as $data) {{$data->photo}} @endforeach">
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- End About Us -->


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
@endsection
