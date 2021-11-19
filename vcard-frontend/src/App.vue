<template>
  <div id="app">
    <nav v-if="currentUser != null" class="navbar navbar-expand-lg navbar-light navbar-top fixed-top">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/">
          <img :src="logoImageURL" width="60" height="30" class="d-inline-block align-top" alt="">
        </router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item" v-if="currentUser.user_type == 'A'" :class="{active: $route.name === 'users'}">
              <router-link class="nav-link" to="users">Users</router-link>
            </li>
            <li class="nav-item" :class="{active: $route.name === 'transactions'}">
              <router-link class="nav-link" to="transactions">Transactions</router-link>
            </li>
          </ul>
          <div v-if="!currentUser" class="navbar-nav ml-auto">
            <li class="nav-item">
              <router-link to="register" class="nav-link">
                <font-awesome-icon icon="user-plus" /> Sign Up
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/login" class="nav-link">
                <font-awesome-icon icon="sign-in-alt" /> Login
              </router-link>
            </li>
          </div>
          <div class="navbar-nav" v-if="$route.name== 'createTransaction'">
            <li class="nav-item">
                <router-link to="register" class="nav-link">
                  {{ currentUser.balance }}<font-awesome-icon icon="euro-sign" /> 
                </router-link>
            </li>
          </div>
          <div v-if="currentUser" class="navbar-nav ml-auto">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img :src="baseURL + currentUser.photo_url" class="rounded-circle z-depth-0 avatar-img" style="width: 30px;" alt="avatar image">
                  {{ currentUser.username }}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <router-link to="/profile" class="dropdown-item">
                    Profile
                  </router-link>
                  <div class="dropdown-divider"></div>
                  <a href="#" @click.prevent="logOut" class="dropdown-item">
                    LogOut
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    
    <main class="container mt-5 pt-3">
      <router-view />
    </main>
  </div>
</template>

<script>
export default {
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    },
    showAdminBoard() {
      if (this.currentUser && this.currentUser['roles']) {
        return this.currentUser['roles'].includes('ROLE_ADMIN');
      }
      return false;
    },
  },
  methods: {
    logOut() {
      this.$store.dispatch('auth/logout');
      this.$router.push('/login');
    }
  },
};
</script>

<style scoped>
.navbar-top{
  background-color: #e7f0fe;
}
</style>