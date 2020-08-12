<script>
// Kaavio toimii, mutta siinä on vielä työstettävää,
// jotta se havainnollistaisi paremmin suorituksia

import { Bar, Line } from "vue-chartjs";

import Chart from "chart.js";
import moment from "moment";

export default {
  extends: Bar,
  props: {
    year: 2019,
    data: {
      type: Array,
    },
    options: {
      type: Object,
    },
    width: "",
    height: "",
  },
  mounted() {
    this.renderActivityChart();
  },
  computed: {
    chartData: function () {
      let sortedActivities = _.find(this.data, function (year) {
        return this.data.start_date == this.year;
      });

      return sortedActivities;
    },
  },
  methods: {
    renderActivityChart: function () {
      let activities = this.data.slice(0, 20);
      let item;
      const distance = [];
      const elevation_gain = [];
      const elapsed_time = [];
      const average_speed = [];
      const timeOfActivity = [];
      const bg_color = [];
      const bg_color2 = [];
      const bg_color3 = [];
      const border_color = [];
      const border_color2 = [];
      const border_color3 = [];
      for (item of activities) {
        distance.push(item.distance / 1000);
        timeOfActivity.push(moment(item.start_date).format("D/M/YY"));
        elapsed_time.push(item.elapsed_time);
        average_speed.push(item.average_speed);
        elevation_gain.push(item.elevation_gain);
        bg_color.push("rgba(255, 99, 132, 0.7)");
        bg_color2.push("rgba(25, 99, 182, 0.7)");
        bg_color3.push("rgba(25, 255, 55, 0.7)");
        border_color.push("rgba(255,99,132,1)");
        border_color2.push("rgba(25, 99, 182,1)");
        border_color3.push("rgba(25, 255, 55,1)");
      }

      this.renderChart({
        labels: timeOfActivity,
        datasets: [
          {
            label: "Distance (km)",
            //yAxisID: "distance",
            data: distance,
            backgroundColor: bg_color,
            borderColor: border_color,
            borderWidth: 1,
            barThickness: 20,
          },
          /*{
            label: "Time",
            data: elapsed_time,
            backgroundColor: bg_color2,
            borderColor: border_color2,
            borderWidth: 1,
            barThickness: 10,
          },*/
          {
            label: "Average speed (m/s)",
            type: "line",
            //yAxisID: "average",
            data: average_speed,
            //backgroundColor: bg_color3,
            //borderColor: border_color3,
            //borderWidth: 1,
            //barThickness: 10,
          },
          {
            label: "Elevation gain",
            type: "line",
            data: elevation_gain,
            //backgroundColor: bg_color3,
            //borderColor: border_color3,
            //borderWidth: 1,
            //barThickness: 10,
          },
        ],
        options: {
          /*scales: {
            yAxes: [
              {
                id: "distance",
                type: "linear",
                position: "left",
              },
              {
                id: "average",
                type: "linear",
                position: "right",
              },
            ],
          },*/
          legend: {
            display: true,
            labels: {
              boxWidth: 0,
            },
          },
          responsive: false,
          maintainAspectRatio: true,
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                  min: 0,
                },
              },
            ],
          },
        },
      });
    },
  },
  watch: {
    data: function () {
      //this._chart.destroy();
      //this.renderChart(this.data, this.options);
      this.renderActivityChart();
    },
  },
};
</script>
