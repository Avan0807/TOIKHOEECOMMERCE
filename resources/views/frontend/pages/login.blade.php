@extends('frontend.layouts.master')

@section('title','CODY || Trang Đăng Nhập')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Trang chủ<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Đăng Nhập</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc Breadcrumbs -->
            
    <!-- Đăng Nhập -->
    <section class="shop login section">
        <div class="container">
            <div class="row"> 
                <div class="col-lg-6 offset-lg-3 col-12">
                    <div class="login-form">
                        <h2>Đăng Nhập</h2>
                        <!-- Form -->
                        <form class="form" method="post" action="{{route('login.submit')}}">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Số điện thoại của bạn<span>*</span></label>
                                        <input type="text" name="phone" placeholder="Nhập số điện thoại"
                                            required="required"
                                            value="{{ old('phone') }}">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Mật khẩu của bạn<span>*</span></label>
                                        <input type="password" name="password" placeholder="Nhập mật khẩu" required="required" value="{{old('password')}}">
                                        @error('password')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                        

                                <div class="col-12">
                                    <div class="form-group login-btn">
                                        <button class="btn btn-facebook" type="submit">Đăng Nhập</button>
                                        <a href="{{route('register.form')}}" class="btn">Đăng Ký</a>
                                    </div>
                                    <div class="checkbox">
                                        <label class="checkbox-inline" for="2"><input name="news" id="2" type="checkbox">Ghi nhớ tài khoản</label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a class="lost-pass" href="{{ route('password.reset') }}">
                                            Quên mật khẩu?
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </form>
                        <!-- Kết thúc Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Kết thúc Đăng Nhập -->
@endsection

@push('styles')
<style>
    .shop.login .form .btn{
        margin-right:0;
    }
    .btn-facebook{
        background:#39579A;
    }
    .btn-facebook:hover{
        background:#073088 !important;
    }
    .btn-github{
        background:#444444;
        color:white;
    }
    .btn-github:hover{
        background:black !important;
    }
    .btn-google{
        background:#ea4335;
        color:white;
    }
    .btn-google:hover{
        background:rgb(243, 26, 26) !important;
    }
</style>
@endpush

@push('scripts')

@endpush
