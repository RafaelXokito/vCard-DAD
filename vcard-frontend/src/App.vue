<template>
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
        >
        VCard</a>
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
              <span class="avatar-text">Nome de Teste</span>
            </a>
            <ul
              class="dropdown-menu dropdown-menu-dark dropdown-menu-end"
              aria-labelledby="navbarDropdownMenuLink"
            >
              <li>
                <router-link
                  class="dropdown-item"
                  :class="{active: $route.name == 'profile'}"
                  to="/profile"
                ><i class="bi bi-person-square"></i>Profile
                </router-link>
              </li>
              <li>
                <router-link
                  class="dropdown-item"
                  :class="{active: $route.name === 'changePassword'}"
                  to="/changePassword"
                >
                  <i class="bi bi-key-fill"></i>Change password
                </router-link>
              </li>
              <li>
                <router-link
                  class="dropdown-item"
                  :class="{active: $route.name === 'changeConfirmationCode'}"
                  to="/changeConfirmationCode"
                >
                  <i class="bi bi-key-fill"></i>Change confirmation code
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
                class="nav-link"
                :class="{active: $route.name === 'dashboard'}"
                to="/"
              ><i class="bi bi-house"></i>
                Dashboard
              </router-link>
            </li>

            <!-- Admin -->
            <li v-if="currentUser.user_type == 'A'" class="nav-item">
              <router-link
                class="nav-link w-100 me-3"
                :class="{active: $route.name === 'users'}"
                to="/users"
              >
                <i class="bi bi-people"></i>
                Users
              </router-link>
            </li>

            <!-- All -->
            <li class="nav-item d-flex justify-content-between align-items-center pe-3">
              <router-link
                class="nav-link w-100 me-3"
                :class="{active: $route.name === 'transactions'}"
                to="/transactions"
              >
                <i class="bi bi-list-check"></i>
                Transactions
              </router-link>
              <router-link
                class="link-secondary"
                to="/transactions/create"
                aria-label="Create Transaction"
              ><i class="bi bi-xs bi-plus-circle"></i>
              </router-link>
            </li>
            <li class="nav-item d-flex justify-content-between align-items-center pe-3">
              <router-link
                class="nav-link w-100 me-3"
                :class="{active: $route.name === 'categories'}"
                to="categories"
              >
                <i class="bi bi-list-check"></i>
                Categories
              </router-link>
              <router-link
                class="link-secondary"
                to="/categories/create"
                aria-label="Create Category"
              ><i class="bi bi-xs bi-plus-circle"></i>
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
                  <span class="avatar-text">Nome de Teste</span>
                </a>
                <ul
                  class="dropdown-menu"
                  aria-labelledby="navbarDropdownMenuLink2"
                >
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{active: $route.name == 'profile'}"
                      to="/profile"
                    ><i class="bi bi-person-square"></i>Profile
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{active: $route.name === 'changePassword'}"
                      to="/changePassword"
                    ><i class="bi bi-key-fill"></i>Change password</router-link>
                  </li>
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{active: $route.name === 'changeConfirmationCode'}"
                      to="changeConfirmationCode"
                    ><i class="bi bi-key-fill"></i>Change confirmation code</router-link>
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

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <router-view></router-view>
      </main>

    </div>
  </div>
</template>

<script>
export default {
  name: 'RootComponent',
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

<style lang="css">
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
</style>