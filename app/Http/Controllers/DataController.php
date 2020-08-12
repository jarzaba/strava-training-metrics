<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activities;
use App\User;
use App\Token;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Strava;

class DataController extends Controller
{
    /*
    public function avgSpeed()
    {
        $activities = Activities::orderBy('average_speed', 'desc')->where('activities_user_id', Auth::user()->id)->limit(15)->get();
        return view('home')->with('tokenExists', true)->with('activities', $activities);
    }
    public function distance()
    {
        $activities = Activities::orderBy('distance', 'desc')->where('activities_user_id', Auth::user()->id)->limit(15)->get();
        return view('home')->with('tokenExists', true)->with('activities', $activities);
    }
    public function elapsedTime()
    {
        $activities = Activities::orderBy('elapsed_time', 'desc')->where('activities_user_id', Auth::user()->id)->limit(15)->get();
        return view('home')->with('tokenExists', true)->with('activities', $activities);
    }
    public function elevationGain()
    {
        $activities = Activities::orderBy('elevation_gain', 'desc')->where('activities_user_id', Auth::user()->id)->limit(15)->get();
        return view('home')->with('tokenExists', true)->with('activities', $activities);
    }
    public function recent()
    {
        $activities = Activities::where('activities_user_id', Auth::user()->id)->limit(15)->get();
        return view('home')->with('tokenExists', true)->with('activities', $activities);
    }
*/
}
