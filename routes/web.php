<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use CodeToad\Strava\Strava;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});
Route::get('/stravatoken', 'StravaController@getToken');
//Route::get('/loading', 'StravaController@loadingResults');
Route::get('/download', 'StravaController@fetchActivities');
Route::get('/newactivities', 'StravaController@fetchNEWActivities');
Route::get('/authenticate', 'StravaController@stravaAuth');
//Route::get('/activities', 'UserController@activities');
//Route::get('/authenticate', 'UserController@stravaAuth');
//Route::get('/activities', 'UserController@activities');
//Route::get('/dashboard', function () {
//  return view('welcome')->with('activities', $activities);
//});


Auth::routes();

Route::get('/activity/{id}', 'StravaController@getActivity');
Route::get('/home', 'HomeController@index')->name('home');
/*Route::get('/home', 'DataController@avgSpeed')->name('avgSpeed');
Route::get('/home', 'DataController@distance')->name('distance');
Route::get('/home', 'DataController@elapsedTime')->name('elapsedTime');
Route::get('/home', 'DataController@recent')->name('recent');
Route::get('/home', 'DataController@elevationGain')->name('elevationGain');
*/
