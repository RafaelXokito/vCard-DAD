<template>
  <div class="container">
    <user-table
    :users="users"
    :showId="false"
    @edit="editUser"
    @list="list"
  ></user-table>
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
      users: []
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
    list(link){
        UserService.getUserBoard(link).then(
            ({data}) => {
              console.log(data);
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