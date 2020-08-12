@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="width: 1024px;">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($tokenExists && $activities_fetched)

                    <div id="vueapp">

                        <main-app :activities='@json($activities)'></main-app>
                        </div>

                    @elseif ($tokenExists && !$activities_fetched)
                    <p>Authentication with Strava succeeded!</p>
                    <a href="{{ url('/download/') }}">Fetch your activities from Strava</a>
                    <p>Downloading might take a minute, please hold on!</p>
                    <div id="loadimage"></div>


                    @else
                    <p><a href="{{ url('/authenticate') }}">You need to give permissions to link your Strava account!</a></p>
                   <p>To use this app, you need to log into your Strava account and give permissions to access your activities. </p>


                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
