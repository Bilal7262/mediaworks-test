@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
@endpush
@push('js-start')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<script>
    var tablet;
    $(document).ready(function(){
        tablet = $('#meeting-table').DataTable({
            ajax:{
                    url: "{{ route('meetings.index') }}"
            },
            "columns": [
                { "name": "start", "data": "start" },
                { "name": "end",  "data": "end" },
                { "name": "summary",  "data": "summary" },
                { "name": "attendee",  "data": "attendee" },
                { "name": "action", "data": "action" },
            ],
            "initComplete": function(settings, json) {
                $('.dataTables_wrapper>.row:eq(1)').addClass('table-responsive p-0 m-0');
                $('.table-responsive>.col-sm-12').addClass('p-0');
            },
            "drawCallback": function( settings ) {
            },
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]

        });

        
    });
    function deleteMeeting(id) {
        //there can be a sweet alert due to shortage of time i skip few things
    if (confirm('Are you sure you want to delete this meeting?')) {
        $.ajax({
            url: '/meetings/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log('Meeting deleted successfully.');
                window.location.reload();
                // You can add additional logic here, such as removing the deleted meeting from the UI
            },
            error: function() {
                console.error('An error occurred while deleting the meeting.');
                // Handle the error, e.g., displaying an error message
            }
        });
    }
}

</script>
@endpush


<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('meetings.create') }}" type="button" class="btn btn-primary" style="background-color:#007bff!important">
                    create meeting
                </a>
            </div>
        </div>
       
    </x-slot>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="table-responsive p-3">
                    <table class="table nowrap scroll-horizontal-vertical" id="meeting-table" width="100%";>
                        <thead>
                            <tr>
                                <th>Start</th>
                                <th>end</th>
                                <th>summry</th>
                                <th>attendee</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>      
            </div>
        </div>
    </div>



</x-app-layout>


