<template>
<div v-if="contentShow">
  <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top flex-md-nowrap p-0 shadow">
    <div v-if="currentUser != null" class="container-fluid">
      <a
        class="navbar-brand col-md-3 col-lg-2 me-0 px-3"
        href="#"
      ><img
          :src="logoImageURL"
          alt=""
          width="30"
          height="24"
          class="d-inline-block align-text-top"
        ></a>
      <button
        id="buttonSidebarExpandId"
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#sidebarMenu"
        aria-controls="sidebarMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdownMenuLink"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <img
                :src="baseURL + currentUser.photo_url"
                class="rounded-circle z-depth-0 avatar-img"
                alt="avatar image"
              >
              <span class="avatar-text">{{currentUser.username}}</span>
            </a>
            <ul
              class="dropdown-menu dropdown-menu-dark dropdown-menu-end"
              aria-labelledby="navbarDropdownMenuLink"
            >
              <li>
                <router-link
                  class="dropdown-item"
                  :class="{active: $route.name === 'profile'}"
                  to="/profile"
                ><font-awesome-icon :icon="['fas', 'address-card']" size="lg" /> Profile
                </router-link>
              </li>
              <li>
                <router-link
                  class="dropdown-item"
                  :class="{active: $route.name === 'changePassword'}"
                  to="/changePassword"
                >
                  <font-awesome-icon :icon="['fas', 'key']" size="lg" /> Change password
                </router-link>
              </li>
              <li>
                <router-link
                  class="dropdown-item"
                  :class="{active: $route.name === 'changeConfirmationCode'}"
                  to="/changeConfirmationCode"
                >
                  <font-awesome-icon :icon="['fas', 'fingerprint']" size="lg" /> Change confirmation code
                </router-link>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a
                  class="dropdown-item"
                  href="#"
                  @click.prevent="logOut"
                >
                  <i class="bi bi-arrow-right"></i>Logout
                </a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav
        v-if="currentUser != null"
        id="sidebarMenu"
        class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
      >
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <router-link
                class="nav-link py-3"
                :class="{active: $route.name === ('dashboard')}"
                to="/"
              ><font-awesome-icon :icon="['fas', 'home']" size="lg" />
                . Dashboard
              </router-link>
            </li>

            <!-- Admin -->
            <li v-if="currentUser.user_type == 'A'" class="nav-item">
              <router-link
                class="nav-link w-100 me-3 py-3"
                :class="{active: $route.name === ('users')}"
                :to="{name: 'users'}"
              >
                <font-awesome-icon :icon="['fas', 'users']" size="lg" />
                . Users
              </router-link>
            </li>

            <!-- All -->
            <li class="nav-item d-flex justify-content-between align-items-center pe-3">
              <router-link
                class="nav-link w-100 me-3 py-3"
                :class="{active: $route.name === ('transactions')}"
                :to="{name: 'transactions'}"
              >
                <font-awesome-icon :icon="['fas', 'money-bill-wave']" size="lg" />
                . Transactions
              </router-link>
              <router-link
                class="link-secondary"
                :to="{name: 'createTransaction'}"
                aria-label="Create Transaction"
              ><font-awesome-icon :icon="['fas', 'plus-circle']" size="lg" />
              </router-link>
            </li>
            <li class="nav-item d-flex justify-content-between align-items-center pe-3">
              <router-link
                class="nav-link w-100 me-3 py-3"
                :class="{active: $route.name.includes('categories')}"
                :to="{name: 'categoriesTable'}"
              >
                <font-awesome-icon :icon="['fas', 'layer-group']" size="lg" />
                . Categories
              </router-link>
              <router-link
                class="link-secondary"
                :to="{name: 'createCategory'}"
                aria-label="Create Category"
              ><font-awesome-icon :icon="['fas', 'plus-circle']" size="lg" />
              </router-link>
            </li>
          </ul>

          <div class="d-block d-md-none">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>User</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdownMenuLink2"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    :src="baseURL + currentUser.photo_url"
                    class="rounded-circle z-depth-0 avatar-img"
                    alt="avatar image"
                  >
                  <span class="avatar-text">{{currentUser.username}}</span>
                </a>
                <ul
                  class="dropdown-menu"
                  aria-labelledby="navbarDropdownMenuLink2"
                >
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{active: $route.name == 'profile'}"
                      :to="{name: 'profile'}"
                    ><font-awesome-icon :icon="['fas', 'address-card']" size="lg" /> Profile
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{active: $route.name === 'changePassword'}"
                      :to="{name: 'changePassword'}"
                    ><font-awesome-icon :icon="['fas', 'key']" size="lg" /> Change password</router-link>
                  </li>
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{active: $route.name === 'changeConfirmationCode'}"
                      :to="{name: 'changeConfirmationCode'}"
                    ><font-awesome-icon :icon="['fas', 'fingerprint']" size="lg" />Change confirmation code</router-link>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a
                      class="dropdown-item"
                      href="#"
                      @click.prevent="logOut"
                    ><i class="bi bi-arrow-right"></i>Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <main class="ms-sm-auto px-md-4" :class="{'col-md-12 col-lg-12': currentUser == null, 'col-md-9 col-lg-10': currentUser != null, }">
        <router-view></router-view>
      </main>

    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'RootComponent',
  computed: {
    contentShow(){
      return this.$store.state.auth.user && this.$store.state.auth.user.username;
    },
    currentUser() {
      return this.$store.state.auth.user;
    },
    showAdminBoard() {
      if (this.currentUser && this.currentUser['user_type']) {
        return this.currentUser['user_type'].includes('A');
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
  created() {
    this.$store.dispatch('auth/getMe');
  },
};
</script>

<style lang="css" scoped>
@import "./assets/css/dashboard.css";

.avatar-img {
  margin: -1.2rem 0.8rem -2rem 0.8rem;
  width: 3.3rem;
  height: 3.3rem;
}
.avatar-text {
  line-height: 2.2rem;
  margin: 1rem 0.5rem -2rem 0;
  padding-top: 1rem;
}

.dropdown-item {
  font-size: 0.875rem;
}

.btn:focus {
  outline: none;
  box-shadow: none;
}

#sidebarMenu {
  overflow-y: auto;
}

.fa-plus-circle:hover {
    color: black;
}
</style>