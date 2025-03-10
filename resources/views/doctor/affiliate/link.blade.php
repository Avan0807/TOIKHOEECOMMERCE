@extends('doctor.layouts.master')

@section('main-content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Link Affiliate cho {{ $product->name }}</h6>
    </div>
    <div class="card-body">
        <p>Link Affiliate của bạn: <a href="{{ $affiliateLink }}">{{ $affiliateLink }}</a></p>
    </div>
</div>
@endsection
