@extends('backend.layouts.master')

@section('title', 'Chỉnh sửa đánh giá bác sĩ')

@section('main-content')
<div class="card">
  <h5 class="card-header">Chỉnh sửa đánh giá</h5>
  <div class="card-body">
    <form action="{{route('doctor-review.update', $review->id)}}" method="POST">
      @csrf
      @method('PATCH')
      <div class="form-group">
        <label for="name">Đánh giá bởi:</label>
        <input type="text" disabled class="form-control" value="{{$review->user->name}}">
      </div>
      <div class="form-group">
        <label for="doctor">Bác sĩ:</label>
        <input type="text" disabled class="form-control" value="{{$review->doctor->name}}">
      </div>
      <div class="form-group">
        <label for="rate">Số sao:</label>
        <input type="number" disabled class="form-control" value="{{$review->rate}}">
      </div>
      <div class="form-group">
        <label for="review">Nội dung đánh giá:</label>
        <textarea name="review" cols="30" rows="5" class="form-control" required>{{$review->review}}</textarea>
      </div>
      <div class="form-group">
        <label for="status">Trạng thái:</label>
        <select name="status" class="form-control" required>
          <option value="active" {{($review->status == 'active') ? 'selected' : ''}}>Hoạt động</option>
          <option value="inactive" {{($review->status == 'inactive') ? 'selected' : ''}}>Không hoạt động</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
  </div>
</div>
@endsection

@push('styles')
<style>
  .form-group label {
    font-weight: bold;
  }
</style>
@endpush
