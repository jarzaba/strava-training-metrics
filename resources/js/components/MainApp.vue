<template>
  <div class="container" style="margin: auto;">
    <div id="content">
      <button
        type="button"
        class="btn btn-outline-primary btn-sm"
        v-on:click="sortedby('recent')"
      >Recent</button>
      <button
        type="button"
        class="btn btn-outline-primary btn-sm"
        v-on:click="sortedby('distance')"
      >Distance</button>
      <button
        type="button"
        class="btn btn-outline-primary btn-sm"
        v-on:click="sortedby('elapsed_time')"
      >Elapsed time</button>
      <button
        type="button"
        class="btn btn-outline-primary btn-sm"
        v-on:click="sortedby('average_speed')"
      >Average speed</button>
      <dynamic-chart
        :data="this.testact"
        :options="{
                responsive: false,
                maintainAspectRatio: true,
                width: 800,
                height: 300
            }"
      ></dynamic-chart>
      <activity-list :activities="this.testact.slice(0,20)"></activity-list>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    activities: {
      type: Array,
    },
  },
  data() {
    return {
      testact: this.activities,
    };
  },
  methods: {
    sortedby: function (sortTerm) {
      if (sortTerm == "recent") {
        console.log("Recent selected");
        return (this.testact = _.orderBy(this.testact, "start_date", "desc"));
      } else if (sortTerm == "distance") {
        console.log("Distance selected");
        return (this.testact = _.orderBy(this.testact, "distance", "desc"));
      } else if (sortTerm == "elapsed_time") {
        console.log("elapsed time selected");
        return (this.testact = _.orderBy(this.testact, "elapsed_time", "desc"));
      } else if (sortTerm == "average_speed") {
        console.log("Distance selected");
        this.testact = _.orderBy(this.testact, "average_speed", "desc");

        return this.testact.slice(0, 20);
      }
    },
  },
  mounted() {
    console.log("Main-app component mounted.");
  },
};
</script>
