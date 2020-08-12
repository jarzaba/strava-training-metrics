<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activities;
use App\User;
use App\Token;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Strava;



class UserController extends Controller
{

    public function stravaAuth()
    {

        /* $redirect_uri = env('CT_STRAVA_REDIRECT_URI');
        $client_id = env('CT_STRAVA_CLIENT_ID');
          return redirect('https://www.strava.com/oauth/authorize?client_id='. $client_id .'&response_type=code&redirect_uri='. $redirect_uri . '&scope=read_all,profile:read_all,activity:read_all&state=strava');

        */
        return Strava::authenticate();
    }
    public function getToken(Request $request)
    {

        $token = Strava::token($request->code);
        //$token = $request->code;

        $access = $token->access_token;
        $refresh = $token->refresh_token;
        //DB::table('token')->insert(['access_token' => $access, 'refresh_token' => $refresh]);
        $tokenToDb = new Token;
        $tokenToDb->users_id = Auth::user()->id;
        $tokenToDb->access_token = $access;
        $tokenToDb->refresh_token = $refresh;
        $tokenToDb->save();

        $activities = Strava::activities($access, 1, 200);

        $i = 2;
        while (count(Strava::activities($access, $i, 200)) != 0) {
            $activities = array_merge($activities, Strava::activities($access, $i, 200));
            $i++;
        }
        foreach ($activities as $activity) {
            $activitiesToDb = new Activities;
            $activitiesToDb->activities_user_id = Auth::user()->id;
            $activitiesToDb->type = $activity->type;
            $activitiesToDb->start_date = $activity->start_date_local;
            $activitiesToDb->activity_id = $activity->id;
            $activitiesToDb->distance = $activity->distance;
            $activitiesToDb->elapsed_time = $activity->elapsed_time;
            $activitiesToDb->moving_time = $activity->moving_time;
            $activitiesToDb->elevation_gain = $activity->total_elevation_gain;
            $activitiesToDb->average_speed = $activity->average_speed;
            $activitiesToDb->save();
        }

        //$activities3 = Strava::activities($access, 3);
        //$activities4 = Strava::activities($access, 4);
        //$activities5 = Strava::activities($access, 5);
        //$activities6 = Strava::activities($access, 6);
        //$activities7 = Strava::activities($access, 7);

        //$activities = array_merge($activities1, $activities2, $activities3, $activities4, $activities5, $activities6, $activities7);

        //$activitiesForJS = json_encode($activities, JSON_HEX_QUOT | JSON_HEX_APOS);
        //$activitiesForJS = str_replace('&quot;', '"', $activitiesForJS);
        //$activitiesForJS = @json($activities);
        //echo Strava::activities($access, 8)[0];


        return view('welcome')->with('activities', $activities)->with('access', $access)->with('refresh', $refresh);

        // Store $token->access_token & $token->refresh_token in database
    }

    public function activities($token, $page = 1, $perPage = 100, $after = null, $before = null)
    {
        $res = array();

        while ($page < 10) {

            $url = 'https://www.strava.com/api/v3/athlete/activities?page=' . $page . '&per_page=' . $perPage;

            if ($after !== null) {
                $url .= '&after=' . $after;
            }

            if ($before !== null) {
                $url .= '&before=' . $before;
            }

            $config = $this->bearer($token);
            $res = $res . array_push($this->get($url, $config));
            $page += 1;
        }
        return view('activities')->with('res', $res);
    }
}
