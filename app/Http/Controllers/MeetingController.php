<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Google\Service\Calendar as GoogleCalendar;
use Laravel\Socialite\Facades\Socialite;
use Google\Client as GoogleClient;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetings = Meeting::with('attendees')->get();
        return DataTables::of($meetings)
            ->addIndexColumn()
            ->editColumn('summary', function(Meeting $meeting){
                return ucfirst($meeting->summary);
            })
            ->editColumn('attendee', function(Meeting $meeting){
                foreach($meeting->attendees as $attendee){
                    if($attendee->id != auth()->id()){
                        return '<p>'.ucfirst($attendee->name).'</p><p>'.ucfirst($attendee->email).'</p>';
                    }
                }
            })
            ->editColumn('action', function(Meeting $meeting) {
                return '<a href="' . route('meetings.edit', ['meeting' => $meeting->id]) . '" class="btn btn-secondary">Edit</a>'
                     . '<a onClick="deleteMeeting(' . $meeting->id . ')" class="btn btn-danger">Delete</a>';
            })
            ->rawColumns(['action','attendee'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all()->except([auth()->id()]);
        return view('meeting.add',compact('users'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        
        $request->validate([
            'summary' => 'required|max:191',
            'start' => 'required|date|after:now',
            'end' => 'required|date|after:start',
            'attendee' => 'required|exists:users,id'
        ]);
        if (!auth()->user()->google_access_token) {
            return Socialite::driver('google')
                ->scopes(['https://www.googleapis.com/auth/calendar'])
                ->redirect();
        }
        
        $attendee = User::where('id', $request->attendee)->first();
        
        $google_client = new GoogleClient();
        $google_client->setAuthConfig(base_path('App/Http/Controllers/client_secret.json')); 
        $google_client->setScopes(GoogleCalendar::CALENDAR_EVENTS);
        $google_client->setAccessType('offline'); 
        $google_client->setPrompt('select_account consent');
        $google_client->setAccessToken(auth()->user()->google_access_token);
        $google_client->setHttpClient(new \GuzzleHttp\Client([
            'verify' => base_path('App/Http/Controllers/cacert.pem'), 
        ]));
        
        $google_calendar = new GoogleCalendar($google_client);
        
        $event = new GoogleCalendar\Event([
            'summary' => $request->title,
            'description' => 'Meeting description',
            'start' => [
                'dateTime' => $request->start,
            ],
            'end' => [
                'dateTime' => $request->end,
            ],
            'attendees' => [
                [
                    'email' => auth()->user()->email,
                    'displayName' => auth()->user()->name
                ],
                [
                    'email' => $attendee->email,
                    'displayName' => $attendee->name
                ],
            ],
        ]);
        
        /*************************************************************************************************** */ 
        // here i get authentication error so i comment this code
        /*************************************************************************************************** */ 


        // $calendar_id = 'primary'; // Use 'primary' for the user's primary calendar
        // $event = $google_calendar->events->insert($calendar_id , $event);
        
        if ($event) {
            $meeting = Meeting::create([
                'summary'=> $request->summary,
                'start' => $request->start,
                'end'=>$request->end,
                // 'event_id' =>$event->id
            ]);
            $meeting->attendees()->attach([auth()->id(), $request->attendee]);
            return redirect()->route('attendees');

        } else{
            return redirect()->route('meetings.add');
        }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting)
    {
        $users = User::all()->except([auth()->id()]);
        return view('meeting.add',compact(['users','meeting']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting)
    {
        $request->validate([
            'summary' => 'sometimes|max:191',
            'start' => 'sometimes|date|after:now',
            'end' => 'sometimes|date|after:start',
            'attendee' => 'sometimes|exists:users,id'
        ]);
        
        $attendee = User::find($request->attendee);
        
        //*************************************************************************************************** */ 
        // because i donot add event so i donot get event_id so i coud not able to update event for now
        // ********************************************************************************************************
        

        // $google_client = new GoogleClient();
        // $google_client->setAuthConfig(base_path('App/Http/Controllers/client_secret.json'));
        // $google_client->setScopes(GoogleCalendar::CALENDAR_EVENTS);
        // $google_client->setAccessType('offline');
        // $google_client->setPrompt('select_account consent');
        // $google_client->setAccessToken(auth()->user()->google_access_token);
        // $google_client->setHttpClient(new \GuzzleHttp\Client(['verify' => base_path('App/Http/Controllers/cacert.pem')]));
        
        // $google_calendar = new GoogleCalendar($google_client);
        
        // $existing_event = $google_calendar->events->get('primary', $meeting->event_id);
        
        // $existing_event->setSummary($request->summary ? $request->summary : $meeting->summary);

        // $start = $request->start ? $request->start : $meeting->start;
        // $end = $request->end ? $request->end : $meeting->end;

        // $existing_event->setStart($start);
        // $existing_event->setEnd($end);
        
        // $attendees = $existing_event->getAttendees();
        
        // $attendees[0]->setEmail(auth()->user()->email);
        // $attendees[0]->setDisplayName(auth()->user()->name);

        // if ($attendee) {
        //     $attendees[1]->setEmail($attendee->email);
        //     $attendees[1]->setDisplayName($attendee->name);
        // }
        
        // $updatedEvent = $google_calendar->events->update('primary', $meeting->event_id, $existing_event);
        
        // if ($updatedEvent) {
        //     if(isset($request->summary)){
        //         $meeting->update(['summary'=>$request->summary]);
        //     }
        //     if(isset($request->start)){
        //         $meeting->update(['start'=>$request->start]);
        //     }
        //     if(isset($request->end)){
        //         $meeting->update(['end'=>$request->end]);
        //     }
        //     if(isset($request->event_id)){
        //         $meeting->update(['event_id'=>$updatedEvent->id]);
        //     }
        //     $meeting->attendees()->detach();
        //     $meeting->attendees()->attach([auth()->id(), $request->attendee]);
        // } 


        // so i update it locally
        if(isset($request->summary)){
            $meeting->update(['summary'=>$request->summary]);
        }
        if(isset($request->start)){
            $meeting->update(['start'=>$request->start]);
        }
        if(isset($request->end)){
            $meeting->update(['end'=>$request->end]);
        }
        $meeting->attendees()->detach();
        $meeting->attendees()->attach([auth()->id(), $request->attendee]);
        return redirect()->route('attendees');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        /**************************** now i am not deleting event from google because i donot have event_id so i am simply deleting from my system********************************/
        $meeting->delete();
        return response()->json(['message'=> 'success']);

    }
}
