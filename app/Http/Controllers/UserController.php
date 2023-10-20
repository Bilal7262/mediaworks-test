<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('name', function(User $user){
                return $user->name;
            })
            ->editColumn('email', function(User $user){
                return $user->email;
            })
            // ->editColumn('action', function(User $user){
            //     return 'crud';
            //     // return view('admin.user.action',compact('user'))->render();
            // })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function manage_google_callback(Request $request){
        if(auth()->user()->update(['google_access_token'=>$request->code])){
            return redirect()->route('meetings.create');
        }
    }
}
