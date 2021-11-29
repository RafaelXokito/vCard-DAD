<template>
  <div v-if="isTableVisible">
    <table class="table">
      <thead>
        <tr>
          <th
            v-if="showId"
            class="align-middle"
          >#</th>
          <th
            v-if="showPhoto"
            class="align-middle"
          >Photo</th>
          <th class="align-middle">Name</th>
          <th
            v-if="showEmail"
            class="align-middle"
          >Email</th>
          <th class="align-middle" v-if="showBlock">
            Blocked
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(user, index) in users.data"
          :key="user.username"
          :class="user.user_type == 'A' ? 'table-success' : ''"
        >
          <td
            v-if="showId"
            class="align-middle"
          >{{ user.username }}</td>
          <td
            v-if="showPhoto"
            class="align-middle"
          >
            <img
              :src="this.baseURL + user.photo_url"
              class="rounded-circle img_photo"
            >
          </td>
          <td class="align-middle">{{ user.name }}</td>
          <td
            v-if="showEmail"
            class="align-middle"
          >{{ user.email }}</td>
          <td
            class="text-end align-middle"
          >
          <div class="d-flex justify-content-start">
              <button class="btn btn-xs" :class="users.data[index].blocked ? 'btn-success' : 'btn-danger'" @click.prevent="changeBlockUser(index)">
                <font-awesome-icon v-if="users.data[index].blocked && (users.data[index].blocked == '0' || users.data[index].blocked == '1')" :icon="['fas', 'check-circle']" />
                <font-awesome-icon v-else-if="!users.data[index].blocked && (users.data[index].blocked == '0' || users.data[index].blocked == '1')" :icon="['fas', 'times-circle']" />
                <span v-else class="spinner-border spinner-border-sm"></span>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-center">
        <pagination class="mx-auto" align="center" :data="users" @pagination-change-page="list"></pagination>
    </div>
  </div>
</template>

<script>
import Pagination from '../global/Pagination.vue'

export default {

  name: "UserTable",
  components:{
    Pagination
  },
  data() {
    return {
      isTableVisible: false,  
      loading: [],    
    }
  },
  props: {
    users: {
      type: Object,
      default: () => {},
    },
    showId: {
      type: Boolean,
      default: true,
    },
    showEmail: {
      type: Boolean,
      default: true,
    },
    showBlock: {
      type: Boolean,
      default: true,
    },
    showAdmin: {
      type: Boolean,
      default: true,
    },
    showGender: {
      type: Boolean,
      default: false,
    },
    showPhoto: {
      type: Boolean,
      default: true,
    },
    showEditButton: {
      type: Boolean,
      default: true,
    },
  },
  emits: [
    'edit',
    'list',
    'block'
  ],
  methods: {
    editClick (user) {
      this.$emit('edit', user)
    },
    async list(link){
        await this.$emit('list', link)
    },
    async changeBlockUser(userIndex){
      await this.$emit('block', userIndex);
    }
  },
  mounted() {
    this.loading = new Array(10).fill(0)
  },
  watch:{
    users(newVal){
      if (newVal) {
        this.isTableVisible = true;
      }
    }
  },
}
</script>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
</style>
