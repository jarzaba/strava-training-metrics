<template>
  <div class="container">
    <table class="table table-striped table-hover table-sm">
      <thead>
        <tr>
          <th>Date</th>
          <th>Activity type</th>
          <th>Distance</th>
          <th>Average speed</th>
          <th>Time</th>
          <th>Elevation gain</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in activities" :key="item.id">
          <td>{{ moment(item.start_date).format("DD/MM/YYYY HH:mm") }}</td>
          <td>{{ item.type }}</td>
          <td>{{ distanceInKm(item.distance) }} km</td>
          <td>{{ avgSpeed(item.average_speed) }} min/km</td>
          <td>{{ sec2time(item.elapsed_time) }}</td>
          <td>{{ item.elevation_gain }} m</td>
          <td>
            <a
              class="btn btn-primary btn-sm"
              role="button"
              :href="'/strava/public/activity/' + item.activity_id"
            >map</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import moment from "moment";

export default {
  props: {
    activities: {
      type: Array,
    },
  },
  data() {
    return {
      //activities: this.activities,
      //activitylist: this.activities.slice(0, 10),
      //n: 10,
      //starting_item: 0,
    };
  },
  computed: {},
  methods: {
    sec2time: function (timeInSeconds) {
      var pad = function (num, size) {
          return ("000" + num).slice(size * -1);
        },
        time = parseFloat(timeInSeconds).toFixed(3),
        hours = Math.floor(time / 60 / 60),
        minutes = Math.floor(time / 60) % 60,
        seconds = Math.floor(time - minutes * 60),
        milliseconds = time.slice(-3);

      return pad(hours, 2) + ":" + pad(minutes, 2) + ":" + pad(seconds, 2);
    },
    distanceInKm: function (distanceInMeters) {
      return (distanceInMeters / 1000).toFixed(2);
    },
    avgSpeed: function (metersPerSecond) {
      // Tämä muunnos ei ole vielä täsmällinen, vaatii työstämistä

      let res = 60 / (metersPerSecond * 3.6);
      console.log(res);
      let secs = (res % 1) / 60;
      let mins = Math.floor(res);
      res = parseFloat(mins) + parseFloat(secs.toFixed(2));

      return res;
    },
  },
  mounted() {
    console.log("Activity-list component mounted.");
  },
};
</script>
