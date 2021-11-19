<template>
  <div class="container">
    <header class="jumbotron">
      <div class="row text-center">
        <div class="col-md-3 col-sm-6">
          <router-link class="btn btn-primary btn-lg" to="transactions"><span>Transaction List</span></router-link>
        </div>
        <div class="col-md-3 col-sm-6">
          <router-link class="btn btn-primary btn-lg" to="createTransaction"><span>Create Transaction</span></router-link>
        </div>
        <div class="col-md-3 col-sm-6">
          <router-link class="btn btn-primary btn-lg" to="profile"><span>Profile</span></router-link>
        </div>
        <div class="col-md-3 col-sm-6">
          <router-link class="btn btn-primary btn-lg" to="categories"><span>Categories</span></router-link>
        </div>
      </div>
    </header>
  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      content: "",
    };
  },
};
</script>