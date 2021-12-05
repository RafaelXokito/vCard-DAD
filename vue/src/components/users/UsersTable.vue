<template>
  <div v-if="isTableVisible">
    <div class="mb-3 d-flex justify-content-between flex-wrap">
      <div class="mx-2 mt-2 flex-grow-1 filter-div">
        <label
          for="name"
          class="form-label"
        >Filter by name:</label>
        <input
          class="form-control"
          id="name"
          name="name"
          placeholder="John Doe"
          v-model.lazy="filterByName"
        />
      </div>
      <div class="mx-2 mt-2 flex-grow-1 filter-div">
        <label
          for="email"
          class="form-label"
        >Filter by email:</label>
        <input
          class="form-control"
          id="email"
          name="email"
          placeholder="JohnDoe@email.com"
          v-model.lazy="filterByEmail"
        />
      </div>
      <div class="mx-2 mt-2 flex-grow-1 filter-div">
        <label
          for="email"
          class="form-label"
        >Filter by phone number:</label>
        <input
          class="form-control"
          id="phone_number"
          name="phone_number"
          placeholder="9XXXXXXXXX"
          v-model.lazy="filterByPhoneNumber"
        />
      </div>
      <div class="mx-2 mt-2 filter-div">
        <label
          for="userType"
          class="form-label"
        >Filter by user type:</label>
        <select
          class="form-select"
          id="userType"
          v-model.lazy="filterByType"
        >
          <option value="" selected>Any</option>
          <option value="A">Admin</option>
          <option value="V">vCard</option>
        </select>
      </div>
      <div class="mx-2 mt-2">
          <router-link
            class="btn btn-success px-4 btn-addtask"
            :to="{name: 'createAdmin'}"
          ><font-awesome-icon :icon="['fas', 'plus-circle']" size="lg" />&nbsp; Create Administrator</router-link>
      </div>
    </div>
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
            Max Debit
          </th>
          <th class="align-middle" v-if="showMaxDebit">
            Blocked
          </th>
          <th class="align-middle" v-if="showDelete">
            Delete
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
            v-if="showMaxDebit"
          >
          <div class="d-flex justify-content-start" v-if="user.user_type === 'V' && !(users.data[index].deleted)">
              <button class="btn btn-xs btn-warning" @click.prevent="changeMaxDebitVCard(index)">
                <font-awesome-icon v-if="!users.data[index].loading ?? true" :icon="['fas', 'credit-card']" />
                <span v-else class="spinner-border spinner-border-sm"></span>
              </button>
            </div>
          </td>
          <td
            class="text-end align-middle"
            v-if="showBlock"
          >
          <div class="d-flex justify-content-start" v-if="user.user_type === 'V' && !(users.data[index].deleted)">
              <button class="btn btn-xs" :class="users.data[index].blocked ? 'btn-success' : 'btn-danger'" @click.prevent="changeBlockUser(index)">
                <font-awesome-icon v-if="users.data[index].blocked && (users.data[index].blocked == '0' || users.data[index].blocked == '1')" :icon="['fas', 'check-circle']" />
                <font-awesome-icon v-else-if="!users.data[index].blocked && (users.data[index].blocked == '0' || users.data[index].blocked == '1')" :icon="['fas', 'times-circle']" />
                <span v-else class="spinner-border spinner-border-sm"></span>
              </button>
            </div>
          </td>
          <td
            class="text-end align-middle"
            v-if="showDelete"
          >
          <div class="d-flex justify-content-start">
              <button class="btn btn-xs" :class="users.data[index].deleted ? 'btn-success' : 'btn-danger'" @click.prevent="changedelete(index)">
                <font-awesome-icon v-if="users.data[index].deleted && (users.data[index].deleted == '0' || users.data[index].deleted == '1')" :icon="['fas', 'trash-restore']" />
                <font-awesome-icon v-else-if="!users.data[index].deleted && (users.data[index].deleted == '0' || users.data[index].deleted == '1')" :icon="['fas', 'trash-alt']" />
                <span v-else class="spinner-border spinner-border-sm"></span>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-center" v-if="users.links">
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
      filterByType: '',
      filterByName: '',
      filterByEmail: '',
      filterByPhoneNumber: '',
      optionsfilter: ''
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
    showMaxDebit: {
      type: Boolean,
      default: true,
    },
    showAdmin: {
      type: Boolean,
      default: true,
    },
    showDelete: {
      type: Boolean,
      default: true,
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
    'block',
    'maxDebit',
    'delete'
  ],
  methods: {
    editClick (user) {
      this.$emit('edit', user)
    },
    async list(link){
        await this.$emit('list', this.optionsfilter ? link+this.optionsfilter : link)
    },
    async changeBlockUser(userIndex){
      await this.$emit('block', userIndex);
    },
    async changeMaxDebitVCard(userIndex){
      await this.$emit('maxDebit', userIndex);
    },
    async changedelete(userIndex){
      await this.$emit('delete', userIndex);
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
    },
    async filterByType(newVal){
      this.isTableVisible = false
      this.optionsfilter = ""
      if (newVal) {
        this.optionsfilter += '&type='+newVal
      }
      if (this.filterByName) {
        this.optionsfilter += '&name='+this.filterByName
      }
      if (this.filterByEmail) {
        this.optionsfilter += '&email='+this.filterByEmail
      }
      if (this.filterByPhoneNumber) {
        this.optionsfilter += '&phone_number='+this.filterByPhoneNumber
      }
      await this.$emit('list', this.users.links.first + this.optionsfilter)
      this.isTableVisible = true
    },
    async filterByName(newVal){
      this.isTableVisible = false
      this.optionsfilter = ""
      if (newVal) {
        this.optionsfilter += '&name='+newVal
      }
      if (this.filterByType) {
        this.optionsfilter += '&type='+this.filterByType
      }
      if (this.filterByEmail) {
        this.optionsfilter += '&email='+this.filterByEmail
      }
      if (this.filterByPhoneNumber) {
        this.optionsfilter += '&phone_number='+this.filterByPhoneNumber
      }
      await this.$emit('list', this.users.links.first + this.optionsfilter)
      this.isTableVisible = true
    },
    async filterByEmail(newVal){
      this.isTableVisible = false
      this.optionsfilter = ""
      if (this.filterByName) {
        this.optionsfilter += '&name='+this.filterByName
      }
      if (this.filterByType) {
        this.optionsfilter += '&type='+this.filterByType
      }
      if (this.filterByPhoneNumber) {
        this.optionsfilter += '&phone_number='+this.filterByPhoneNumber
      }
      if (newVal) {
        this.optionsfilter += '&email='+newVal
      }
      await this.$emit('list', this.users.links.first + this.optionsfilter)
      this.isTableVisible = true
    },
    async filterByPhoneNumber(newVal){
      if (newVal !== '') {
        this.filterByType = 'V'
      } else {
        this.filterByType = ''
      }
      this.isTableVisible = false
      this.optionsfilter = ""
      if (this.filterByName) {
        this.optionsfilter += '&name='+this.filterByName
      }
      if (this.filterByType) {
        this.optionsfilter += '&type='+this.filterByType
      }
      if (this.filterByEmail) {
        this.optionsfilter += '&email='+this.filterByEmail
      }
      if (newVal) {
        this.optionsfilter += '&phone_number='+newVal
      }
      await this.$emit('list', this.users.links.first + this.optionsfilter)
      this.isTableVisible = true
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
.btn-addtask {
  margin-top: 1.85rem;
}
</style>
