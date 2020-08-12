<template>
    <div class="container">
        <canvas id="activity_chart" width="800" height="300"></canvas>
    </div>
</template>

<script>
import Chart from "chart.js";
import moment from "moment";

export default {
    props: {
        activities: {
            type: Array
        }
    },
    /*data() {
    return {
      activities: activities,
    };
  },*/
    computed: {},
    mounted() {
        let activities = this.activities;
        let item;
        let toHHMMSS = secs => {
            let sec_num = parseInt(secs, 10);
            let hours = Math.floor(sec_num / 3600);
            let minutes = Math.floor(sec_num / 60) % 60;
            let seconds = sec_num % 60;
            return [hours, minutes, seconds]
                .map(v => (v < 10 ? "0" + v : v))
                .filter((v, i) => v !== "00" || i > 0)
                .join(":");
        };
        const distance = [];
        const timeOfRun = [];
        const bg_color = [];
        const border_color = [];
        for (item of activities) {
            distance.push(item.distance / 1000);
            timeOfRun.push(moment(item.start_date).format("D/M/YY"));
            bg_color.push("rgba(255, 99, 132, 0.7)");
            border_color.push("rgba(255,99,132,1)");

            // console.log(toHHMMSS(item.elapsed_time));
            // console.log(item.elapsed_time);
        }
        //distance.sort(function(a, b){return b-a});
        console.log(distance);
        console.log(timeOfRun);
        console.log(activities);
        console.log(bg_color);
        console.log(activities[0].distance);

        function drawChart(dist, activity_time, bg_color, border_color) {
            var ctx = document.getElementById("activity_chart");
            var chart = new Chart(ctx, {
                // The type of chart we want to create
                //    type: 'horizontalBar',
                type: "bar",

                // The data for our dataset
                data: {
                    labels: activity_time,
                    datasets: [
                        {
                            label: "Most recent activities",
                            data: dist,
                            backgroundColor: bg_color,
                            borderColor: border_color,
                            borderWidth: 1,
                            barThickness: 20
                        }
                    ]
                },

                // Configuration options go here
                options: {
                    legend: {
                        display: true,
                        labels: {
                            boxWidth: 0
                        }
                    },
                    responsive: false,
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true
                                }
                            }
                        ],
                        xAxes: [{}]
                    }
                }
            });
        }
        drawChart(distance, timeOfRun, bg_color, border_color);
        console.log("Chart Component mounted.");
    }
};
</script>
