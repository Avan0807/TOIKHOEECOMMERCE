@extends('backend.layouts.master')

@section('title', 'Danh sách bác sĩ')

@section('main-content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách bác sĩ</h6>
            <a href="{{ route('doctor.create') }}" class="btn btn-primary btn-sm float-right">Thêm bác sĩ</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if($doctors->count() > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Họ tên</th>
                                <th>Chuyên khoa</th>
                                <th>Đánh giá</th>
                                <th>Điện thoại</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $doctor)
                                <tr>
                                    <td>{{ $doctor->doctorID }}</td>
                                    <td>{{ $doctor->name }}</td>
                                    <td>{{ $doctor->specialization }}</td>
                                    <td>{{ $doctor->rating }}</td>
                                    <td>{{ $doctor->phone }}</td>
                                    <td>
                                        <a href="{{ route('doctor.edit', $doctor->doctorID) }}" class="btn btn-primary btn-sm">Sửa</a>
                                        <form action="{{ route('doctor.destroy', $doctor->doctorID) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $doctors->links() }}
                @else
                    <p>Không có bác sĩ nào!</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate {
          display: none;
      }
      .zoom {
        transition: transform .2s;
      }

      .zoom:hover {
        transform: scale(3.2);
      }
  </style>
@endpush

@push('scripts')

  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>
      $('#doctor-dataTable').DataTable({
          "columnDefs": [
              {
                  "orderable": false,
                  "targets": [4, 5]
              }
          ]
      });

      $(document).ready(function () {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $('.dltBtn').click(function (e) {
              var form = $(this).closest('form');
              var dataID = $(this).data('id');
              e.preventDefault();
              swal({
                  title: "Bạn có chắc không?",
                  text: "Sau khi xóa, bạn sẽ không thể khôi phục dữ liệu này!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
                  .then((willDelete) => {
                      if (willDelete) {
                          form.submit();
                      } else {
                          swal("Dữ liệu của bạn an toàn!");
                      }
                  });
          });
      });
  </script>
@endpush
