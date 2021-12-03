<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
  </div>
  <div>
    <statistics></statistics>
  </div>
</div>
</template>

<script>
import statistics from './statistics/Statistics.vue';
export default {
  components: { statistics },
  name: "Dashboard",
  data() {
    return {
      content: ''
    };
  },
  computed: {
    currentUser() {
      return this.$store.state.auth.data;
    },
  }
};
</script>