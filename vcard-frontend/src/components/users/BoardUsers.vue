<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Users</h1>
  </div>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="userType"
        class="form-label"
      >Filter by user type:</label>
      <select
        class="form-select"
        id="userType"
        v-model="filterByType"
      >
        <option value="-1" selected>Any</option>
        <option value="0">Admin</option>
        <option value="1">vCard</option>
      </select>
    </div>
  </div>
  <div class="container">
    <user-table
    :users="users"
    :showId="false"
    @edit="editUser"
    @list="list"
    @block="blockUser"
  ></user-table>
  </div>
</div>
</template>

<script>
import UserService from "../../services/user.service";

import UserTable from "./UsersTable.vue"

export default {
  name: "Users",
  components: {
    UserTable
  },
  data() {
    return {
      content: "",
      users: [],
      filterByType: -1,
    };
  },
  computed: {
    totalUsers () {
      return this.users.length
    }
  },
  methods: {
    editUser(user){
      console.log(user);
    },
    async blockUser(userIndex){
      let user = this.users.data[userIndex];

      let blockedState = user.blocked;
      this.users.data[userIndex].blocked = 'loading'

      let message = `Do you really want to ${blockedState ? 'UNBLOCK' : 'BLOCK'} the ${user.user_type === 'A' ? 'admin' : 'vCard'} ${user.name} [${user.username}]`
      if (confirm(message)) {
        await UserService.blockUser(user).then(()=>{
          this.users.data[userIndex].blocked = !blockedState
        })
      }
    },
    list(link){
        UserService.getUserBoard(link).then(
            ({data}) => {
              this.users = data;
            },
            (error) => {
                this.content =
                (error.response &&
                    error.response.data &&
                    error.response.data.message) ||
                error.message ||
                error.toString();
            }
        );
    }
  },
  mounted() {
    this.list();
  },
};
</script>

<style scoped>
.filter-div {
  min-width: 12rem;
}
</style>