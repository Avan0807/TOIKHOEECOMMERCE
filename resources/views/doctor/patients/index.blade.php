@extends('doctor.layouts.master')

@section('main-content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Danh sách lịch hẹn của bệnh nhân</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="appointment-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bệnh nhân</th>
                        <th>Bác sĩ</th>
                        <th>Ngày</th>
                        <th>Giờ</th>
                        <th>Loại tư vấn</th>
                        <th>Phê duyệt</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->appointmentID }}</td>
                        <td>{{ $appointment->user->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->date }}</td>
                        <td>{{ $appointment->time }}</td>
                        <td>{{ $appointment->consultation_type }}</td>
                        <td>
                            @if($appointment->approval_status == 'Chấp nhận')
                                <span class="badge badge-success">Chấp nhận</span>
                            @elseif($appointment->approval_status == 'Từ chối')
                                <span class="badge badge-danger">Từ chối</span>
                            @else
                                <span class="badge badge-warning">Chờ duyệt</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('appointments.updateStatus', $appointment->appointmentID) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="approval_status" value="Chấp nhận">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> 
                                    </button>
                                </form>

                                <form action="{{ route('appointments.updateStatus', $appointment->appointmentID) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="approval_status" value="Từ chối">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i> 
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="float-right">{{ $appointments->links() }}</div>
        </div>
    </div>
</div>
@endsection


@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
      .sidebar {
        background-color: #0924ec !important;
        background-image: linear-gradient(113deg, #314aff 10%, #60616f 100%) !important;
        background-size: cover !important;
      }
    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px; /* Khoảng cách giữa 2 nút */
    }

  </style>
@endpush



@push('scripts')
<!-- DataTables -->
<script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

<!-- SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        // Khởi tạo DataTable
        var table = $('#appointment-dataTable').DataTable({
            "columnDefs": [
                { "orderable": false, "targets": [7,8] } // Không sắp xếp cột hành động
            ],
            "language": {
                "search": "Tìm kiếm:",
                "lengthMenu": "Hiển thị _MENU_ lịch hẹn",
                "info": "Hiển thị _START_ đến _END_ của _TOTAL_ lịch hẹn",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Tiếp",
                    "previous": "Trước"
                }
            }
        });

        // Lọc lịch hẹn theo ngày
        $('#filter-date').on('change', function() {
            table.columns(3).search(this.value).draw(); // Cột thứ 3 là "Ngày hẹn"
        });

        // Xác nhận xoá bằng SweetAlert
        $('.dltBtn').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Bạn có chắc không?",
                text: "Sau khi xóa, bạn sẽ không thể khôi phục dữ liệu này!",
                icon: "warning",
                buttons: ["Hủy", "Xác nhận"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

