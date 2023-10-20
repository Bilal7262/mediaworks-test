@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
@endpush
@push('js-start')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<script>
    var tablet;
    $(document).ready(function(){
        tablet = $('#user-table').DataTable({
            ajax:{
                    url: "{{ route('users.index') }}"
            },
            "columns": [
                { "name": "name", "data": "name" },
                { "name": "email",  "data": "email" },
                // { "name": "action", "data": "action" },
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
</script>
@endpush


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="table-responsive p-3">
                    <table class="table nowrap scroll-horizontal-vertical" id="user-table" width="100%";>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>      
            </div>
        </div>
    </div>



</x-app-layout>


