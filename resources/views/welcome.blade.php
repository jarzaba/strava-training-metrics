<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    {{count($activities)}}
                    <!-- @foreach ($activities as $activity)
                   <p> {{$activity->distance/1000}} km</p>
                    @endforeach

                    -->
                    <canvas id="myChart" width="1000" height="400"></canvas>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.0-alpha/Chart.min.js"></script>
                    <div id="vueapp">
                    <example-component></example-component>
                    <chart></chart>
                    <map></map>
                    </div>



                </div>
            </div>
        </div>

        <script>

            let activities = @json($activities);
            let item;



            let toHHMMSS = (secs) => {
    let sec_num = parseInt(secs, 10)
    let hours   = Math.floor(sec_num / 3600)
    let minutes = Math.floor(sec_num / 60) % 60
    let seconds = sec_num % 60

    return [hours,minutes,seconds]
        .map(v => v < 10 ? "0" + v : v)
        .filter((v,i) => v !== "00" || i > 0)
        .join(":")
};
const distance = [];
const timeOfRun = [];
const bg_color = [];
const border_color = [];
            for (item of activities) {
                distance.push(item.distance/1000);
                timeOfRun.push(item.start_date_local);
                bg_color.push('rgba(255, 99, 132, 0.7)');
                border_color.push('rgba(255,99,132,1)');
                console.log(item.distance);
                console.log(toHHMMSS(item.elapsed_time));
                console.log(item.elapsed_time);
            }
            //distance.sort(function(a, b){return b-a});
            console.log(distance);
            console.log(timeOfRun);
            console.log(activities);
            console.log(activities[0].distance);
            console.log(activities[0].start_latlng);



            function drawchart(dist, activity_time, bg_color, border_color) {
var ctx = document.getElementById("myChart").getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
//    type: 'horizontalBar',
    type: 'bar',

    // The data for our dataset
    data: {
        labels: activity_time,
        datasets: [{
            label: "Most recent 100 activities",
            data: dist,
            backgroundColor: bg_color,
            borderColor: border_color,
            borderWidth: 1
        }]
    },

    // Configuration options go here
    options: {
        legend: {
            display: true,
            labels: {
            boxWidth: 0,
            }
        },
	responsive: false,
        scales: {
            yAxes: [{
                barThickness: 53,
                ticks: {
                    beginAtZero:true
                }
            }],
            xAxes: [{
                barThickness: 13
            }]
        }
   }
});
}
drawchart(distance, timeOfRun, bg_color, border_color);



        </script>
        <script type="text/javascript" src="js/app.js"></script>
    </body>
</html>
