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
                                        <div class="password-wrapper">
                                            <input type="password" name="password" id="password" placeholder="Nhập mật khẩu" required="required">
                                            <span toggle="#password" class="fa fa-fw fa-eye toggle-password"></span>
                                        </div>
                                        @error('password')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                        
                                <div class="col-12">
                                    <div class="form-group login-btn">
                                        <button class="btn btn-primary" type="submit">Đăng Nhập</button>
                                        <a href="{{route('register.form')}}" class="btn btn-secondary">Đăng Ký</a>
                                    </div>
                                    <div class="checkbox">
                                        <label class="checkbox-inline" for="remember"><input name="remember" id="remember" type="checkbox">Ghi nhớ tài khoản</label>
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
    .shop.login .form .btn {
        margin-right: 0;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
    }
    .btn-primary:hover {
        background-color: #0056b3 !important;
    }
    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }
    .btn-secondary:hover {
        background-color: #545b62 !important;
    }
    .password-wrapper {
        position: relative;
    }
    .password-wrapper .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #777;
    }
    .password-wrapper .toggle-password:hover {
        color: #333;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const togglePassword = document.querySelector(".toggle-password");
        const passwordField = document.querySelector("#password");

        togglePassword.addEventListener("click", function() {
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
            this.classList.toggle("fa-eye-slash");
        });
    });
</script>
@endpush
