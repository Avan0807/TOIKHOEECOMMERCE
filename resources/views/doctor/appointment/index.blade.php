@extends('doctor.layouts.master')

@section('main-content')
<div class="container-fluid">
    @include('doctor.layouts.notification')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lịch làm việc của bác sĩ</h1>
    </div>

    <!-- Calendar -->
    <div id="calendar"></div>
</div>
@endsection

@push('styles')
<style>
    .sidebar {
        background-color: #0924ec !important;
        background-image: linear-gradient(113deg, #314aff 10%, #60616f 100%) !important;
        background-size: cover !important;
    }
    #calendar {
        max-width: 100%; 
        margin: 20px auto;
        overflow-x: auto; 
        white-space: nowrap; 
    }
    .fc {
        width: 100%;
    }
    .fc-scroller {
        overflow-y: hidden;
    }
</style>
@endpush

@push('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script> 

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'Asia/Ho_Chi_Minh', 
                locale: 'vi', 
                initialView: 'dayGridMonth', 
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto',
                contentHeight: 'auto', 
                scrollTime: '07:00:00', 
                slotMinWidth: 100, 
                editable: true,
                events: [], 
            });
            calendar.render();
        } else {
            console.error('Không tìm thấy phần tử #calendar');
        }
    });
</script>
@endpush
