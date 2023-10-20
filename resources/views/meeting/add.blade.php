@push('css')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
@endpush
@push('js-start')
<script>
    
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{isset($meeting)?'Edit':'Create'}} Meeting</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <form action="{{ isset($meeting) ? route('meetings.update', ['meeting' => $meeting->id]) : route('meetings.store') }}" method="POST" enctype="multipart/form-data">

                                        @csrf
                                        @if(isset($meeting))
                                            @method('put')
                                        @endif
                                        
                                            <div class="row">
                                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                                    <fieldset class="form-group">
                                                        <label class="text-bold-600 font-medium-2" for="summary">summary</label>
                                                        <textarea class="form-control font-medium-2"  id="summary" name="summary" placeholder="Enter summry">{{ isset($meeting)?$meeting->summary:''}}</textarea>
                                                        @error('summary')
                                                            <span style="color:red;">{{ $message }}</span>
                                                        @enderror
                                                    </fieldset>
                                                </div>
                                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                                    <fieldset class="form-group">
                                                        <label class="text-bold-600 font-medium-2" for="start">Start date</label>
                                                        <input type="datetime-local" class="form-control font-medium-2" id="start" name="start" value="{{ isset($meeting) ? $meeting->start : '' }}" placeholder="Enter start date">

                                                        @error('start')
                                                            <span style="color:red;">{{ $message }}</span>
                                                        @enderror
                                                    </fieldset>
                                                </div>
                                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                                    <fieldset class="form-group">
                                                        <label class="text-bold-600 font-medium-2" for="end">End date</label>
                                                        <input type="datetime-local" class="form-control font-medium-2" id="end" name="end" value="{{ isset($meeting) ? $meeting->end : '' }}" placeholder="Enter end">
                                                        @error('end')
                                                            <span style="color:red;">{{ $message }}</span>
                                                        @enderror
                                                    </fieldset>
                                                </div>
                                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                                    <fieldset class="form-group">
                                                        <label class="text-bold-600 font-medium-2" for="attendee">Select attendee</label>
                                                        <select name="attendee" id="attendee">
                                                            @foreach($users as $key => $user)
                                                                <option value="{{ $user->id }}" >{{  $user->email }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('attendee')
                                                            <span style="color:red;">{{ $message }}</span>
                                                        @enderror
                                                    </fieldset>
                                                </div>
                                               
                                                <div class="col-xl-12 col-sm-12 col-12">
                                                    <button style="background-color:#007bff!important" class="btn btn-primary" type="submit" style="float:right;">{{isset($meeting)?'Update':'Save'}}</button>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



</x-app-layout>


