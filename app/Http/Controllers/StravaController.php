<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activities;
use App\User;
use App\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Strava;



class StravaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function stravaAuth()
    {

        // Kutsutaan yksinkertaisesti Strava-paketista autentikointi-funktiota
        return Strava::authenticate();
    }

    public function getToken(Request $request)
    {
        // Haetaan tokenit Strava APIin kytkeytymistä varten

        $token = Strava::token($request->code);
        $access = $token->access_token;
        $refresh = $token->refresh_token;
        $expires_at = $token->expires_at;

        // Tallennetaan tokenit tokens-tietokantaan

        $tokenToDb = new Token;
        $tokenToDb->users_id = Auth::user()->id;
        $tokenToDb->access_token = $access;
        $tokenToDb->refresh_token = $refresh;
        $tokenToDb->expires_at = $expires_at;
        $tokenToDb->save();

        return view('home')->with('tokenExists', true)->with('activities_fetched', false);
    }

    public function fetchActivities()
    {

        // Tsekataan onko access-token vanhentunut (validi 6 tuntia), jos ei, niin haetaan
        // uusi refresh-tokenin avulla.

        $user = Auth::user()->id;
        $expires = Token::select('expires_at')->where('users_id', $user)->orderby('expires_at', 'desc')->get();
        $expires = $expires[0]->expires_at;
        $expires =  date($expires);
        if (Carbon::now() > $expires) {

            // Token has expired, generate new tokens using the currently stored user refresh token
            $refresh = Token::select('refresh_token')->where('users_id', $user)->orderby('expires_at', 'desc')->get();
            $refresh = $refresh[0]->refresh_token;
            $refresh = Strava::refreshToken($refresh);

            // Update the users tokens
            Token::where('users_id', $user)->update([
                'access_token' => $refresh->access_token,
                'refresh_token' => $refresh->refresh_token
            ]);
        }

        // Haetaan kaikki aktiviteetit Stravan APIsta.
        // APIn Rajoituksena on 200 aktiviteettia per haku,
        // joten silmukan avulla liitetään yhteen hakuja kunnes tulee tyhjä haku.

        $access = Token::select('access_token')->where('users_id', $user)->latest()->get();
        $access = $access[0]->access_token;
        $activities = Strava::activities($access, 1, 200);
        $i = 2;
        while (count(Strava::activities($access, $i, 200)) != 0) {
            $activities = array_merge($activities, Strava::activities($access, $i, 200));
            $i++;
        }

        // Tallennetaan kaikki aktiviteetit activities-tietokantaan

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

        $user = Auth::user()->id;
        $activities = Activities::where('activities_user_id', $user)->get();
        //return view('strava_login')->with('access', $access);

        return view('home')->with('tokenExists', true)->with('activities', $activities)->with('activities_fetched', true);
    }


    public function fetchNEWActivities()
    {
        // Tsekataan onko access-token vanhentunut (validi 6 tuntia), jos ei, niin haetaan
        // uusi refresh-tokenin avulla.

        $user = Auth::user()->id;
        $expires = Token::select('expires_at')->where('users_id', $user)->orderby('expires_at', 'desc')->get();
        $expires = $expires[0]->expires_at;
        $expires =  date($expires);
        if (Carbon::now() > $expires) {

            // Token has expired, generate new tokens using the currently stored user refresh token
            $refresh = Token::select('refresh_token')->where('users_id', $user)->orderby('expires_at', 'desc')->get();
            $refresh = $refresh[0]->refresh_token;
            $refresh = Strava::refreshToken($refresh);

            // Update the users tokens
            Token::where('users_id', $user)->update([
                'access_token' => $refresh->access_token,
                'refresh_token' => $refresh->refresh_token
            ]);
        }

        // Haetaan tietokannasta viimeisin aktiviteetti

        $latestDownload = Activities::select('start_date')->where('activities_user_id', $user)->orderby('start_date', 'desc')->get();
        $latestDownload = $latestDownload[0]->start_date;

        // Haetaan tietokannan viimeisintä aktiviteettia uudemmat Stravan APIn kautta
        // APIn Rajoituksena on 200 aktiviteettia per haku,
        // joten silmukan avulla liitetään yhteen hakuja kunnes tulee tyhjä haku.

        $access = Token::select('access_token')->where('users_id', $user)->latest()->get();
        $access = $access[0]->access_token;
        $activities = Strava::activities($access, 1, 200, $latestDownload);
        $i = 2;
        while (count(Strava::activities($access, $i, 200, $latestDownload)) != 0) {
            $activities = array_merge($activities, Strava::activities($access, $i, 200));
            $i++;
        }

        // Tallennetaan haetut aktiviteetit activities-tietokantaan

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


        $activities = Activities::where('activities_user_id', Auth::user()->id)->get();
        //return view('strava_login')->with('access', $access);

        return view('home')->with('tokenExists', true)->with('activities', $activities)->with('activities_fetched', true);
    }

    public function getActivity($id)
    {

        $user = Auth::user()->id;

        $expires = Token::select('expires_at')->where('users_id', $user)->orderby('expires_at', 'desc')->get();
        $expires = $expires[0]->expires_at;
        $expires =  date($expires);
        if (Carbon::now() > $expires) {

            // Token has expired, generate new tokens using the currently stored user refresh token
            $refresh = Token::select('refresh_token')->where('users_id', $user)->orderby('expires_at', 'desc')->get();
            $refresh = $refresh[0]->refresh_token;
            $refresh = Strava::refreshToken($refresh);

            // Update the users tokens
            Token::where('users_id', $user)->update([
                'access_token' => $refresh->access_token,
                'refresh_token' => $refresh->refresh_token
            ]);
        }

        // Haetaan valittu aktiviteetti Id:n avulla

        $access = Token::select('access_token')->where('users_id', $user)->latest()->get();
        $access = $access[0]->access_token;
        $activity = Strava::activity($access, $id);

        /* Tsekataan toimiiko tokenin toimivuuden testaus

        $current_time = Carbon::now();
        $msg = "not known";
        if ($current_time > $expires) {
            $msg = "Token needed";
        } else if ($current_time < $expires) {
            $msg = "Token okay";
        }
        return view('strava_login')->with('expires', $expires)->with('current_time', $current_time)->with('msg', $msg);
        */

        return view('activitymap')->with('activity', $activity)->with('tokenExists', true);
    }
}
