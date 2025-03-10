@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Chỉnh sửa thông tin người dùng</h5>
    <div class="card-body">
        <form method="post" action="{{ route('users.update', $user->id) }}">
            @csrf 
            @method('PATCH')

            <!-- Tên người dùng -->
            <div class="form-group">
                <label for="inputName" class="col-form-label">Tên</label>
                <input id="inputName" type="text" name="name" placeholder="Nhập tên" 
                    value="{{ old('name', $user->name) }}" class="form-control">
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="inputEmail" class="col-form-label">Email</label>
                <input id="inputEmail" type="email" name="email" placeholder="Nhập email" 
                    value="{{ old('email', $user->email) }}" class="form-control">
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Ảnh đại diện -->
            <div class="form-group">
                <label for="inputPhoto" class="col-form-label">Ảnh đại diện</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                            <i class="fa fa-picture-o"></i> Chọn ảnh
                        </a>
                    </span>
                    <input id="thumbnail" class="form-control" type="text" name="photo" 
                        value="{{ old('photo', $user->photo) }}">
                </div>
                <img id="holder" style="margin-top:15px;max-height:100px;">
                @error('photo')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Vai trò -->
            <div class="form-group">
                <label for="role" class="col-form-label">Vai trò</label>
                <select name="role" class="form-control">
                    <option value="">-- Chọn vai trò --</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                    <option value="doctor" {{ $user->role == 'doctor' ? 'selected' : '' }}>Bác sĩ</option>
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Người dùng</option>
                </select>
                @error('role')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Trạng thái -->
            <div class="form-group">
                <label for="status" class="col-form-label">Trạng Thái</label>
                <select name="status" class="form-control">
                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
                @error('status')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Chuyên khoa (Chỉ hiển thị nếu là bác sĩ) -->
            @if($user->role == 'doctor')
            <div class="form-group">
                <label for="specialization" class="col-form-label">Chuyên khoa</label>
                <input id="specialization" type="text" name="specialization" placeholder="Nhập chuyên khoa" 
                    value="{{ old('specialization', $user->specialization ?? '') }}" class="form-control">
                @error('specialization')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            @endif

            <!-- Số điện thoại -->
            <div class="form-group">
                <label for="phone" class="col-form-label">Số điện thoại</label>
                <input id="phone" type="text" name="phone" placeholder="Nhập số điện thoại" 
                    value="{{ old('phone', $user->phone ?? '') }}" class="form-control">
                @error('phone')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Địa chỉ -->
            <div class="form-group">
                <label for="location" class="col-form-label">Địa chỉ</label>
                <input id="location" type="text" name="location" placeholder="Nhập địa chỉ" 
                    value="{{ old('location', $user->location ?? '') }}" class="form-control">
                @error('location')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Giờ làm việc (Chỉ hiển thị nếu là bác sĩ) -->
            @if($user->role == 'doctor')
            <div class="form-group">
                <label for="working_hours" class="col-form-label">Giờ làm việc</label>
                <input id="working_hours" type="text" name="working_hours" placeholder="Nhập giờ làm việc" 
                    value="{{ old('working_hours', $user->working_hours ?? '') }}" class="form-control">
                @error('working_hours')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            @endif

            <!-- Nút Cập nhật -->
            <div class="form-group mb-3">
                <button class="btn btn-success" type="submit">Cập nhật</button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    $('#lfm').filemanager('image');
</script>
@endpush