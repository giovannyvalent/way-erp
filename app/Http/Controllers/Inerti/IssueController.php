<?php

namespace App\Http\Controllers\Inerti;

use App\Http\Controllers\Controller;
use App\Models\Inert\Instances;
use App\Models\Inert\Issues;
use App\Models\Inert\Release;
use App\Models\User;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index()
    {
        $title = "Issues";
        $issues = Issues::all();

        return view('inerti.issues',compact(
            'title','issues'
        ));
    }

    /**
     * Display a create page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Add Issue";

        $releases = Release::get();
        $instances = Instances::get();
        $users = User::get();

        return view('inerti.add-issues',compact(
            'title','releases','users','instances'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Issues::create([
            'title'=>$request->title,
            'release_id'=>$request->release_id,
            'gravity'=>$request->gravity,
            'urgency'=>$request->urgency,
            'trend'=>$request->trend,
            'user_id'=>null,
            'type'=>$request->type,
            'expiry_date'=>isset($request->expiry_date) ? $request->expiry_date : null,
            'instances_released'=>json_encode($request->instances_released),
            'status_all'=>$request->status_all,
            'description'=>$request->description
        ]);
        $notifications = array(
            'message'=>"Nova issue!",
            'alert-type'=>'success',
        );

        return redirect()->route('issues')->with($notifications);
    }

    public function show(Request $request, $id)
    {
        $title = "Edit Issue";
        $issue = Issues::find($id);
        $releases = Release::get();
        $instances = Instances::get();
        $users = User::get();
        return view('inerti.edit-issue',compact(
            'title','issue','releases','instances', 'users'
        ));
    }
}
