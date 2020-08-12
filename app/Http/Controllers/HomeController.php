<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Token;
use App\Activities;
use phpDocumentor\Reflection\Types\True_;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $tokenExists = Token::find(Auth::user()->id);
        if ($tokenExists) {
            //$activities = Activities::paginate(15);
            $activities = Activities::where('activities_user_id', Auth::user()->id)->limit(1000)->get();
            return view('home')->with('tokenExists', true)->with('activities', $activities)->with('activities_fetched', true);
        } else {
            return view('home')->with('tokenExists', false)->with('activities_fetched', false);
        }
    }
}
