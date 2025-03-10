@extends('frontend.layouts.master')
@section('title', "Thông tin bác sĩ - $doctor->name")

@section('main-content')
<div class="container mt-5">
    <!-- Thông tin bác sĩ -->
    <div class="row">
        <div class="col-md-4 text-center">
            <img
                src="{{ $doctor->photo ?? 'https://via.placeholder.com/250' }}"
                alt="Hình ảnh bác sĩ"
                class="rounded-circle img-fluid shadow"
                style="height: 250px; width: 250px;">
            <h3 class="mt-3">{{ $doctor->name }}</h3>
            <p class="text-muted">{{ $doctor->specialization }}</p>
            <div>
                <span class="text-warning">
                    @if ($doctor->reviews && $doctor->reviews->count() > 0)
                        <h4>{{ round($doctor->reviews->avg('rate'), 1) }} / 5 ({{ $doctor->reviews->count() }} đánh giá)</h4>
                    @else
                        <h4>Chưa có đánh giá</h4>
                    @endif
                </span>
            </div>
        </div>
    <div class="col-md-8">
            <h4>Thông tin liên hệ</h4>
            <p><strong>Địa chỉ:</strong> {{ $doctor->location ?? 'Không rõ' }}</p>
            <p><strong>Email:</strong> {{ $doctor->email ?? 'Không rõ' }}</p>
            <p><strong>Điện thoại:</strong> {{ $doctor->phone ?? 'Không rõ' }}</p>
            <p><strong>Giờ làm việc:</strong> {{ $doctor->working_hours ?? 'Không rõ' }}</p>
            <p><strong>Kinh nghiệm:</strong> {{ $doctor->experience }} năm</p>
        </div>
    </div>

    <!-- Phần giữ lại: Nút chức năng -->
    <div class="row mt-3">
        <div class="col-md-12 text-center">
            <button class="btn btn-primary me-2">Liên Hệ</button>
            <button class="btn btn-success me-2" id="openBookingModal" data-bs-toggle="modal" data-bs-target="#bookingModal">
                Đặt Lịch Khám
            </button>
            <button class="btn btn-warning">Chat</button>
        </div>
    </div>

    <!-- Đánh giá -->
    <div class="row mt-5">
        <div class="col-12">
            <h4>Đánh giá</h4>
            <!-- Form đánh giá -->
            @auth
            <form method="POST" action="{{ route('doctor.review.store', $doctor->doctorID) }}">
                    @csrf
                    <input type="hidden" name="doctorID" value="{{ $doctor->doctorID }}">

                    <label for="rate">Đánh giá của bạn:</label>
                    <div class="star-rating">
                    @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" id="star-{{ $i }}" name="rate" value="{{ $i }}">
                            <label for="star-{{ $i }}">&#9733;</label>
                        @endfor
                    </div>

                    <label for="review">Nội dung đánh giá:</label>
                    <textarea name="review" id="review" required></textarea>

                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                </form>

            @else
            <p>Bạn cần <a href="{{ route('login.form') }}">đăng nhập</a> để đánh giá.</p>
            @endauth
        </div>
    </div>

    <!-- Danh sách đánh giá -->
    <div class="row mt-4">
        <div class="col-12">
            <h5>Danh sách đánh giá</h5>
            @forelse($doctor->reviews as $review)
            <div class="review-card">
    <strong>{{$review->user_info['name'] ?? 'N/A'}}</strong>
    <div>
        <span class="text-warning">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $review->rate)
                    &#9733;
                @else
                    &#9734;
                @endif
            @endfor
        </span>
    </div>
    <p>{{ $review->review }}</p>
    <small class="text-muted">Ngày đánh giá: {{ $review->created_at->format('d-m-Y') }}</small>
</div>

            @empty
                <p>Chưa có đánh giá nào.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

        <!-- Modal Đặt Lịch Khám -->
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content p-5">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Đặt lịch hẹn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="bookingForm">
                            <!-- Đảm bảo doctorID được lấy ngay khi tải trang -->
                            <input type="hidden" id="doctorID" name="doctorID" value="{{ $doctor->doctorID }}">
                            <input type="hidden" id="userID" value="{{ auth()->id() }}">

                            <div class="mb-3">
                                <label for="doctorName" class="form-label">Bác sĩ</label>
                                <input type="text" class="form-control" id="doctorName" value="{{ $doctor->name }}"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Ngày khám</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>

                            <div class="mb-3">
                                <label for="time" class="form-label">Giờ khám</label>
                                <input type="time" class="form-control" id="time" name="time" required>
                            </div>

                            <div class="mb-3">
                                <label for="consultation_type" class="form-label">Hình thức khám</label>
                                <select class="form-control" id="consultation_type" name="consultation_type" required>
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                    <option value="Home">Home</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Ghi chú</label>
                                <textarea class="form-control" id="note" name="note" rows="3">Lịch khám định kỳ</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Xác nhận đặt lịch</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


@push('styles')
    <style>
        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            font-size: 20px;
            cursor: pointer;
        }

        .star-rating input:checked ~ label {
            color: #FFD700; /* Star color when selected */
        }

        .star-rating input:hover ~ label {
            color: #FFBF00; /* Hover color */
        }

        .review-card {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .review-card p {
            font-size: 14px;
            color: #555;
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

    </style>
@endpush


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Khi nhấn nút "Đặt lịch hẹn"
        document.getElementById("openBookingModal").addEventListener("click", function() {
            let modal = new bootstrap.Modal(document.getElementById("bookingModal"));
            modal.show();
        });

        // Xử lý sự kiện khi form được submit
        document.getElementById("bookingForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let doctorID = document.getElementById("doctorID").value;
            let userID = document.getElementById("userID").value;
            let date = document.getElementById("date").value;
            let time = document.getElementById("time").value;
            let consultationType = document.getElementById("consultation_type").value.trim();
            let note = document.getElementById("note").value;

            let formData = {
                doctorID: doctorID,
                date: date,
                time: time,
                consultation_type: consultationType,
                note: note
            };

            console.log("DEBUG: SENDING DATA:", formData);

            fetch(`/api/appointments/${userID}/create`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    console.log("RESPONSE:", data);
                    if (data.success) {
                        alert("Đặt lịch thành công!");
                        window.location.reload();
                    } else {
                        alert("Lỗi: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("LỖI:", error);
                    alert("Không thể đặt lịch, vui lòng thử lại.");
                });
        });
    });
</script>
@endpush
